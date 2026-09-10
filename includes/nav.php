<?php
declare(strict_types=1);

/**
 * Navigation, pied de page et cartes de catégories, construits au moment de
 * l'affichage.
 *
 * L'arbre vient de la table `categories`, donc du back-office : masquer une
 * catégorie la retire du menu, du tiroir mobile, du pied de page et de la page
 * d'accueil, et met sa page en 404. Base injoignable : on retombe sur l'arbre
 * figé à la génération (includes/nav-static.php), pour que le menu ne
 * disparaisse jamais.
 */

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/icons.php';

/** Le modèle figé : entrées fixes, libellés courts, colonnes du pied de page. */
function alam_nav_static(): array
{
    static $model = null;
    if ($model === null) {
        $file = __DIR__ . '/nav-static.php';
        $model = is_file($file) ? (require $file) : ['top' => [], 'footer' => []];
    }
    return $model;
}

/** Les catégories de la base, indexées par identifiant. Null si indisponible. */
function alam_categories(): ?array
{
    static $rows = false;
    if ($rows !== false) {
        return $rows;
    }
    $rows = null;
    $pdo = alam_db();
    if (!$pdo) {
        return null;
    }
    try {
        $out = [];
        foreach ($pdo->query('SELECT id, slug, name, parent_id, family, position, is_visible
                                FROM categories ORDER BY position, id') as $row) {
            $out[(int) $row['id']] = [
                'id'         => (int) $row['id'],
                'slug'       => $row['slug'],
                'name'       => $row['name'],
                'parent_id'  => $row['parent_id'] !== null ? (int) $row['parent_id'] : null,
                'family'     => $row['family'],
                'is_visible' => (bool) $row['is_visible'],
            ];
        }
        $rows = $out;
    } catch (Throwable $e) {
        error_log('[alamstores] catégories indisponibles : ' . $e->getMessage());
        $rows = null;
    }
    return $rows;
}

/** Adresse d'une catégorie : sa page si elle existe, sinon la page générique. */
function alam_category_url(string $slug): string
{
    static $seen = [];
    if (!isset($seen[$slug])) {
        $seen[$slug] = is_file(ALAM_ROOT . '/' . $slug . '.php')
            ? $slug . '.php'
            : 'categorie.php?c=' . rawurlencode($slug);
    }
    return $seen[$slug];
}

/**
 * Vrai si la page d'une catégorie doit répondre.
 *
 * Une catégorie absente de la base — parce que la base est éteinte, ou parce
 * que la page n'en est pas une — reste accessible : on ne cache une page que
 * sur une décision explicite prise dans le back-office.
 */
function alam_category_visible(string $slug): bool
{
    $categories = alam_categories();
    if ($categories === null) {
        return true;
    }
    foreach ($categories as $cat) {
        if ($cat['slug'] === $slug) {
            return $cat['is_visible'] && alam_ancestors_visible($cat, $categories);
        }
    }
    return true;
}

/** Une sous-catégorie d'une catégorie masquée est masquée elle aussi. */
function alam_ancestors_visible(array $cat, array $categories): bool
{
    $parent = $cat['parent_id'];
    for ($i = 0; $i < 10 && $parent !== null; $i++) {
        if (!isset($categories[$parent])) {
            return true;
        }
        if (!$categories[$parent]['is_visible']) {
            return false;
        }
        $parent = $categories[$parent]['parent_id'];
    }
    return true;
}

/** Les enfants visibles d'une catégorie, en arbre. */
function alam_children(string $slug, ?array $categories = null): array
{
    $categories = $categories ?? alam_categories();
    if ($categories === null) {
        return [];
    }
    $parentId = null;
    foreach ($categories as $cat) {
        if ($cat['slug'] === $slug) {
            $parentId = $cat['id'];
            break;
        }
    }
    if ($parentId === null) {
        return [];
    }
    $out = [];
    foreach ($categories as $cat) {
        if ($cat['parent_id'] === $parentId && $cat['is_visible']) {
            $out[] = [
                'slug'     => $cat['slug'],
                'label'    => $cat['name'],
                'href'     => alam_category_url($cat['slug']),
                'children' => alam_children($cat['slug'], $categories),
            ];
        }
    }
    return $out;
}

/**
 * Le menu complet : les entrées fixes du site, dont les branches catégories
 * sont remplacées par ce que dit la base.
 */
function alam_nav_model(): array
{
    static $model = null;
    if ($model !== null) {
        return $model;
    }
    $static     = alam_nav_static();
    $categories = alam_categories();
    $model      = [];

    foreach ($static['top'] as $entry) {
        if ($categories === null) {
            $model[] = $entry;              // base éteinte : l'arbre figé
            continue;
        }
        $known = null;
        foreach ($categories as $cat) {
            if ($cat['slug'] === $entry['slug']) {
                $known = $cat;
                break;
            }
        }
        if ($known === null) {
            $model[] = $entry;              // Accueil, Société, Service…
            continue;
        }
        if (!$known['is_visible']) {
            continue;                        // masquée dans le back-office
        }
        $entry['label']    = $known['name'];
        $entry['children'] = alam_children($known['slug'], $categories);
        $model[] = $entry;
    }
    return $model;
}

// ---------------------------------------------------------------- rendu -----

/** Le menu du bandeau, avec ses sous-menus déroulants. */
function alam_nav_html(): string
{
    $out = [];
    foreach (alam_nav_model() as $entry) {
        $slug = alam_e($entry['slug']);
        $href = alam_e($entry['href']);
        if (!$entry['children']) {
            $out[] = '<li class="nav__item"><a class="nav__link" href="' . $href
                   . '" data-slug="' . $slug . '">' . alam_e($entry['label']) . '</a></li>';
            continue;
        }
        $subs = '';
        foreach ($entry['children'] as $child) {
            $inner = '';
            foreach ($child['children'] as $grand) {
                $inner .= '<li><a href="' . alam_e($grand['href']) . '" data-slug="'
                        . alam_e($grand['slug']) . '">' . alam_e($grand['label']) . '</a></li>';
            }
            if ($inner !== '') {
                $subs .= '<li class="nav__item"><a href="' . alam_e($child['href'])
                       . '" data-slug="' . alam_e($child['slug']) . '">' . alam_e($child['label'])
                       . ' ' . alam_icon('chevron-right', 'nav__caret') . '</a>'
                       . '<ul class="nav__sub">' . $inner . '</ul></li>';
            } else {
                $subs .= '<li><a href="' . alam_e($child['href']) . '" data-slug="'
                       . alam_e($child['slug']) . '">' . alam_e($child['label']) . '</a></li>';
            }
        }
        $out[] = '<li class="nav__item"><a class="nav__link" href="' . $href . '" data-slug="'
               . $slug . '">' . alam_e($entry['label']) . ' ' . alam_icon('chevron-down', 'nav__caret')
               . '</a><ul class="nav__sub">' . $subs . '</ul></li>';
    }
    return implode("\n            ", $out);
}

/** Le tiroir mobile : mêmes entrées, en accordéon. */
function alam_drawer_html(): string
{
    $out = [];
    foreach (alam_nav_model() as $entry) {
        $href  = alam_e($entry['href']);
        $slug  = alam_e($entry['slug']);
        $label = alam_e($entry['label']);
        if (!$entry['children']) {
            $out[] = '<li><div class="drawer__row"><a href="' . $href . '" data-slug="'
                   . $slug . '">' . $label . '</a></div></li>';
            continue;
        }
        $subs = '';
        foreach ($entry['children'] as $child) {
            $subs .= '<li><a href="' . alam_e($child['href']) . '" data-slug="'
                   . alam_e($child['slug']) . '">' . alam_e($child['label']) . '</a></li>';
            foreach ($child['children'] as $grand) {
                $subs .= '<li><a href="' . alam_e($grand['href']) . '" data-slug="'
                       . alam_e($grand['slug']) . '">&nbsp;&nbsp;' . alam_e($grand['label'])
                       . '</a></li>';
            }
        }
        $out[] = '<li><div class="drawer__row"><a href="' . $href . '" data-slug="' . $slug
               . '">' . $label . '</a>'
               . '<button class="drawer__expand" type="button" aria-expanded="false"'
               . ' aria-label="Afficher les pages ' . $label . '">' . alam_icon('chevron-down')
               . '</button></div>'
               . '<ul class="drawer__sub"><div>' . $subs . '</div></ul></li>';
    }
    return implode("\n            ", $out);
}

/** Les deux colonnes de liens du pied de page. */
function alam_footer_columns_html(): string
{
    $out = '';
    foreach (alam_nav_static()['footer'] as $column) {
        $items = alam_children($column['slug']);
        if (!$items && alam_categories() === null) {
            $items = $column['children'];       // base éteinte : liste figée
        }
        if (!$items) {
            continue;
        }
        $lis = '';
        foreach ($items as $item) {
            $lis .= '<li><a href="' . alam_e($item['href']) . '">' . alam_e($item['label']) . '</a></li>';
        }
        $out .= '<div><h3>' . alam_e($column['title']) . '</h3><ul>' . $lis . '</ul></div>';
    }
    return $out;
}
