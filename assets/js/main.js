/*!
 * Alam Stores — front-end behaviour
 * Dependency-free. Sticky/glass header, slide-out drawer, hero slider
 * (5 s auto-advance), lightbox, scroll reveal, scroll-to-top and the B2B
 * quote form, which logs each lead locally and hands it to the mail client.
 * The partner logo marquee is pure CSS — no JavaScript involved.
 */
(function () {
  'use strict';

  var $ = function (s, c) { return (c || document).querySelector(s); };
  var $$ = function (s, c) { return Array.prototype.slice.call((c || document).querySelectorAll(s)); };
  var on = function (el, ev, fn, o) { if (el) el.addEventListener(ev, fn, o || false); };
  var reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---------------------------------------------------------- header --- */
  function initHeader() {
    var header = $('#site-header');
    if (!header) return;
    var stuck = false;
    function update() {
      var should = (window.pageYOffset || document.documentElement.scrollTop) > 12;
      if (should !== stuck) {
        stuck = should;
        header.classList.toggle('is-stuck', stuck);
      }
    }
    on(window, 'scroll', update, { passive: true });
    update();
  }

  /* ---------------------------------------------------------- drawer --- */
  function initDrawer() {
    var drawer = $('#drawer');
    var backdrop = $('#drawer-backdrop');
    var toggle = $('#nav-toggle');
    var close = $('#drawer-close');
    if (!drawer || !toggle) return;

    var lastFocus = null;

    function setOpen(open) {
      drawer.classList.toggle('is-open', open);
      drawer.setAttribute('aria-hidden', open ? 'false' : 'true');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      toggle.setAttribute('aria-label', open ? 'Fermer le menu' : 'Ouvrir le menu');
      document.body.classList.toggle('is-locked', open);
      if (backdrop) {
        backdrop.hidden = false;
        backdrop.classList.toggle('is-open', open);
        if (!open) {
          window.setTimeout(function () {
            if (!drawer.classList.contains('is-open')) backdrop.hidden = true;
          }, 340);
        }
      }
      if (open) {
        lastFocus = document.activeElement;
        (close || drawer).focus();
      } else if (lastFocus) {
        lastFocus.focus();
      }
    }

    on(toggle, 'click', function () { setOpen(!drawer.classList.contains('is-open')); });
    on(close, 'click', function () { setOpen(false); });
    on(backdrop, 'click', function () { setOpen(false); });
    on(document, 'keydown', function (e) {
      if (e.key === 'Escape' && drawer.classList.contains('is-open')) setOpen(false);
    });
    /* Following a link closes the drawer. */
    $$('a', drawer).forEach(function (a) {
      on(a, 'click', function () { setOpen(false); });
    });

    /* Accordion submenus */
    $$('.drawer__expand', drawer).forEach(function (btn) {
      on(btn, 'click', function () {
        var sub = btn.closest('.drawer__row').nextElementSibling;
        if (!sub) return;
        var open = sub.classList.toggle('is-open');
        btn.setAttribute('aria-expanded', open ? 'true' : 'false');
      });
    });

    /* Keep focus inside the drawer while it is open. */
    on(drawer, 'keydown', function (e) {
      if (e.key !== 'Tab' || !drawer.classList.contains('is-open')) return;
      var items = $$('a[href], button:not([disabled])', drawer)
        .filter(function (el) { return el.offsetParent !== null; });
      if (!items.length) return;
      var first = items[0], last = items[items.length - 1];
      if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
      else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
    });
  }

  /* ------------------------------------------------------ hero slider --- */
  function initHero() {
    var hero = $('[data-hero]');
    if (!hero) return;
    var slides = $$('.hero__slide', hero);
    var dotsWrap = $('[data-hero-dots]', hero);
    if (slides.length < 2) return;

    var index = 0, timer = null;
    var dots = slides.map(function (_, i) {
      if (!dotsWrap) return null;
      var b = document.createElement('button');
      b.type = 'button';
      b.className = 'hero__dot' + (i === 0 ? ' is-active' : '');
      b.setAttribute('aria-label', 'Visuel ' + (i + 1));
      on(b, 'click', function () { go(i); restart(); });
      dotsWrap.appendChild(b);
      return b;
    });

    function go(i) {
      index = (i + slides.length) % slides.length;
      slides.forEach(function (s, n) {
        s.classList.toggle('is-active', n === index);
        s.setAttribute('aria-hidden', n === index ? 'false' : 'true');
      });
      dots.forEach(function (d, n) { if (d) d.classList.toggle('is-active', n === index); });
    }
    /* Auto-advance every 5 s. */
    var INTERVAL = 5000;
    function start() { if (!reduced) timer = window.setInterval(function () { go(index + 1); }, INTERVAL); }
    function stop() { if (timer) { window.clearInterval(timer); timer = null; } }
    function restart() { stop(); start(); }

    on(hero, 'mouseenter', stop);
    on(hero, 'mouseleave', start);
    on(document, 'visibilitychange', function () { document.hidden ? stop() : restart(); });

    var x0 = null;
    on(hero, 'touchstart', function (e) { x0 = e.touches[0].clientX; stop(); }, { passive: true });
    on(hero, 'touchend', function (e) {
      if (x0 === null) return;
      var dx = e.changedTouches[0].clientX - x0;
      if (Math.abs(dx) > 45) go(index + (dx < 0 ? 1 : -1));
      x0 = null;
      start();
    });

    start();
  }

  /* -------------------------------------------------------- lightbox --- */
  function initLightbox() {
    var links = $$('a[data-lightbox]');
    if (!links.length) return;

    var box = document.createElement('div');
    box.className = 'lightbox';
    box.setAttribute('role', 'dialog');
    box.setAttribute('aria-modal', 'true');
    box.setAttribute('aria-label', 'Galerie');
    box.hidden = true;
    box.innerHTML =
      '<button type="button" class="lightbox__close" aria-label="Fermer">' +
      '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" ' +
      'stroke-width="2" stroke-linecap="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/>' +
      '<line x1="6" y1="6" x2="18" y2="18"/></svg></button>' +
      '<button type="button" class="lightbox__prev" aria-label="Image précédente">' +
      '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" ' +
      'stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
      '<polyline points="15 18 9 12 15 6"/></svg></button>' +
      '<figure><img alt=""><figcaption></figcaption></figure>' +
      '<button type="button" class="lightbox__next" aria-label="Image suivante">' +
      '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" ' +
      'stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
      '<polyline points="9 18 15 12 9 6"/></svg></button>';
    document.body.appendChild(box);

    var img = $('img', box), cap = $('figcaption', box);
    var group = [], current = 0, lastFocus = null;

    function show(i) {
      current = (i + group.length) % group.length;
      var a = group[current];
      img.src = a.getAttribute('href');
      img.alt = a.getAttribute('data-caption') || '';
      cap.textContent = a.getAttribute('data-caption') || '';
    }
    function open(a) {
      group = links.filter(function (l) {
        return l.getAttribute('data-lightbox') === a.getAttribute('data-lightbox');
      });
      lastFocus = document.activeElement;
      box.hidden = false;
      document.body.classList.add('is-locked');
      show(group.indexOf(a));
      $('.lightbox__close', box).focus();
    }
    function close() {
      box.hidden = true;
      img.removeAttribute('src');
      document.body.classList.remove('is-locked');
      if (lastFocus) lastFocus.focus();
    }

    links.forEach(function (a) { on(a, 'click', function (e) { e.preventDefault(); open(a); }); });
    on($('.lightbox__close', box), 'click', close);
    on($('.lightbox__next', box), 'click', function () { show(current + 1); });
    on($('.lightbox__prev', box), 'click', function () { show(current - 1); });
    on(box, 'click', function (e) { if (e.target === box) close(); });
    on(document, 'keydown', function (e) {
      if (box.hidden) return;
      if (e.key === 'Escape') close();
      if (e.key === 'ArrowRight') show(current + 1);
      if (e.key === 'ArrowLeft') show(current - 1);
    });

    var tx = null;
    on(box, 'touchstart', function (e) { tx = e.touches[0].clientX; }, { passive: true });
    on(box, 'touchend', function (e) {
      if (tx === null) return;
      var dx = e.changedTouches[0].clientX - tx;
      if (Math.abs(dx) > 45) show(current + (dx < 0 ? 1 : -1));
      tx = null;
    });
  }

  /* ------------------------------------------------------- scroll top --- */
  function initToTop() {
    var btn = $('#to-top');
    if (!btn) return;
    var shown = false;
    function update() {
      var should = (window.pageYOffset || document.documentElement.scrollTop) > 520;
      if (should !== shown) { shown = should; btn.classList.toggle('is-visible', shown); }
    }
    on(window, 'scroll', update, { passive: true });
    on(btn, 'click', function () {
      window.scrollTo({ top: 0, behavior: reduced ? 'auto' : 'smooth' });
    });
    update();
  }

  /* ----------------------------------------------------- scroll reveal --- */
  function initReveal() {
    var items = $$('.reveal');
    if (!items.length) return;
    if (reduced || !('IntersectionObserver' in window)) {
      items.forEach(function (el) { el.classList.add('is-in'); });
      return;
    }
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-in');
          io.unobserve(entry.target);
        }
      });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.06 });
    items.forEach(function (el) { io.observe(el); });
  }

  /* ------------------------------------------------------- B2B quote form --- */
  /* Saves every submission to a local lead store (readable from admin.html)
     and then sends the visitor to WhatsApp with the request pre-filled.

     NOTE: localStorage is per-browser and per-device. A lead submitted on a
     visitor's phone is stored on THAT phone — admin.html on another machine
     will not see it. WhatsApp is the delivery channel; the local store is a
     convenience log for leads captured on this device. */
  var LEADS_KEY = 'alamstores.leads';

  /* Sends the lead to the database. The page navigates to WhatsApp a moment
     later, which would abort a normal fetch — sendBeacon is queued by the
     browser and delivered regardless. Failure is silent on purpose: the
     visitor's request must go through even when the API is down. */
  function postLead(lead) {
    var url = 'api/lead.php';
    var payload = JSON.stringify(lead);
    try {
      if (navigator.sendBeacon) {
        return navigator.sendBeacon(url, new Blob([payload], { type: 'application/json' }));
      }
      if (window.fetch) {
        fetch(url, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          credentials: 'same-origin',
          keepalive: true,
          body: payload
        }).catch(function () {});
      }
    } catch (e) { /* no back-end on this host */ }
    return false;
  }

  function saveLead(lead) {
    try {
      var raw = localStorage.getItem(LEADS_KEY);
      var list = raw ? JSON.parse(raw) : [];
      if (!Array.isArray(list)) list = [];
      list.push(lead);
      localStorage.setItem(LEADS_KEY, JSON.stringify(list));
      return true;
    } catch (e) {
      return false;   // private mode, quota, or blocked storage
    }
  }

  function initQuoteForm() {
    var form = $('[data-quote-form]');
    if (!form) return;

    var statut = $('[data-statut]', form);
    var companyWrap = $('[data-b2b-field]', form);
    var company = companyWrap ? $('input', companyWrap) : null;
    var status = $('.form-status', form);

    /* Order and labels of everything sent and stored. */
    var FIELDS = [
      ['statut', 'Statut'],
      ['company', 'Entreprise'],
      ['category', 'Catégorie'],
      ['firstname', 'Prénom'],
      ['lastname', 'Nom'],
      ['email', 'E-mail'],
      ['phone', 'Téléphone'],
      ['address', 'Adresse'],
      ['zip', 'Code postal'],
      ['city', 'Ville'],
      ['country', 'Pays']
    ];

    function val(name) {
      var el = form.elements[name];
      return el && el.value ? el.value.trim() : '';
    }

    /* The company name is required only for the two professional statuses. */
    function isB2B() {
      if (!statut) return false;
      var opt = statut.options[statut.selectedIndex];
      return !!(opt && opt.getAttribute('data-b2b'));
    }

    function syncB2B() {
      if (!companyWrap || !company) return;
      var on_ = isB2B();
      companyWrap.hidden = !on_;
      company.required = on_;
      if (!on_) { company.value = ''; showError(company, ''); }
    }

    function showError(field, msg) {
      if (!field) return;
      field.classList.toggle('is-invalid', !!msg);
      field.setAttribute('aria-invalid', msg ? 'true' : 'false');
      var slot = form.querySelector('[data-error-for="' + field.name + '"]');
      if (slot) slot.textContent = msg || '';
    }

    function validate() {
      var first = null;
      $$('[required]', form).forEach(function (f) {
        if (f.closest('[hidden]')) return;
        var msg = '';
        if (!f.value.trim()) msg = 'Ce champ est obligatoire.';
        else if (f.type === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(f.value)) {
          msg = 'Adresse e-mail invalide.';
        } else if (f.type === 'tel' && f.value.replace(/[^0-9]/g, '').length < 8) {
          msg = 'Numéro de téléphone incomplet.';
        }
        showError(f, msg);
        if (msg && !first) first = f;
      });
      if (first) {
        first.focus();
        if (status) {
          status.textContent = 'Merci de compléter les champs obligatoires.';
          status.className = 'form-status is-error';
        }
        return false;
      }
      if (status) { status.textContent = ''; status.className = 'form-status'; }
      return true;
    }

    /* WhatsApp renders *text* in bold, so the labels stand out in the chat. */
    function compose() {
      var lines = ['*Demande de devis — alamstores.ma*', ''];
      FIELDS.forEach(function (pair) {
        var v = val(pair[0]);
        if (v) lines.push('*' + pair[1] + '* : ' + v);
      });
      var msg = val('message');
      if (msg) lines.push('', '*Message :*', msg);
      return lines.join('\n');
    }

    /* wa.me wants digits only — no +, spaces or dashes. */
    function waUrl() {
      var number = (form.getAttribute('data-whatsapp') || '').replace(/[^0-9]/g, '');
      if (!number) return '';
      return 'https://wa.me/' + number + '?text=' + encodeURIComponent(compose());
    }

    if (statut) { on(statut, 'change', syncB2B); syncB2B(); }
    $$('input, select, textarea', form).forEach(function (f) {
      on(f, 'input', function () { if (f.classList.contains('is-invalid')) showError(f, ''); });
    });

    on(form, 'submit', function (e) {
      e.preventDefault();
      if (!validate()) return;

      var lead = { timestamp: new Date().toISOString() };
      FIELDS.forEach(function (pair) { lead[pair[0]] = val(pair[0]); });
      lead.message = val('message');

      // Three destinations, in order of reliability: the database (shared,
      // survives the visitor's device), this browser's local log, and finally
      // WhatsApp, which is what the visitor actually sees.
      postLead(lead);
      var stored = saveLead(lead);

      var url = waUrl();

      /* Shown when the redirect below is blocked (pop-up blockers, in-app
         browsers), so the visitor still has a one-tap way through. */
      if (status) {
        status.textContent = (stored ? 'Demande enregistrée. ' : '')
          + 'WhatsApp s’ouvre avec votre demande pré-remplie — il ne reste qu’à l’envoyer. ';
        status.className = 'form-status is-ok';
        if (url) {
          var link = document.createElement('a');
          link.href = url;
          link.target = '_blank';
          link.rel = 'noopener';
          link.setAttribute('data-wa-link', '');
          link.textContent = 'Ouvrir WhatsApp';
          status.appendChild(link);
        }
      }

      if (url) window.location.href = url;
    });
  }


  /* ------------------------------------------------------- video pop-up --- */
  /* A YouTube player is only ever built on click. Nothing is requested from
     youtube.com while the page is simply being read, so the page stays as
     light and as private as it was before a video was attached to a product. */
  function initVideo() {
    var box = null;
    var frame = null;
    var lastFocus = null;

    function build() {
      box = document.createElement('div');
      box.className = 'vbox';
      box.setAttribute('role', 'dialog');
      box.setAttribute('aria-modal', 'true');
      box.setAttribute('aria-label', 'Vidéo du produit');
      box.hidden = true;
      box.innerHTML = '<button type="button" class="vbox__close" aria-label="Fermer">'
                    + '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>' + '</button><div class="vbox__frame"></div>';
      document.body.appendChild(box);
      frame = $('.vbox__frame', box);

      on($('.vbox__close', box), 'click', close);
      on(box, 'click', function (e) { if (e.target === box) close(); });
      on(document, 'keydown', function (e) {
        if (e.key === 'Escape' && box && !box.hidden) close();
      });
    }

    function open(id) {
      if (!/^[A-Za-z0-9_-]{11}$/.test(id)) return;
      if (!box) build();
      lastFocus = document.activeElement;
      // The URL is rebuilt from the id alone, so nothing from the catalogue
      // can smuggle parameters into the player.
      frame.innerHTML = '<iframe src="https://www.youtube-nocookie.com/embed/'
        + id + '?autoplay=1&rel=0&modestbranding=1" title="Vidéo du produit"'
        + ' allow="accelerometer; autoplay; encrypted-media; picture-in-picture"'
        + ' referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>';
      box.hidden = false;
      document.body.classList.add('is-locked');
      $('.vbox__close', box).focus();
    }

    function close() {
      if (!box) return;
      box.hidden = true;
      frame.innerHTML = '';          // stops playback and drops the connection
      document.body.classList.remove('is-locked');
      if (lastFocus) lastFocus.focus();
    }

    // Delegated: product cards can arrive from the API after this runs.
    on(document, 'click', function (e) {
      var trigger = e.target.closest ? e.target.closest('[data-video]') : null;
      if (!trigger) return;
      e.preventDefault();
      open(trigger.getAttribute('data-video'));
    });
  }

  /* --------------------------------------------------- catalogue (API) --- */
  /* Products entered in the back-office. The page already carries whatever was
     baked in at build time; this refreshes it from the database. If the API is
     not there — static hosting, PHP down — the static cards simply stay. */
  function initCatalog() {
    var mount = $('[data-catalog]');
    if (!mount || !window.fetch) return;

    var slug = mount.getAttribute('data-catalog');
    var list = $('[data-catalog-list]', mount);
    if (!slug || !list) return;

    fetch('api/catalog.php?category=' + encodeURIComponent(slug), {
      credentials: 'same-origin',
      headers: { 'Accept': 'application/json' }
    })
      .then(function (r) { return r.ok ? r.json() : null; })
      .then(function (data) {
        var items = data && data.products && data.products[slug];
        if (!items || !items.length) return;
        list.innerHTML = items.map(function (p) {
          return '<li>' + card(p, false) + '</li>';
        }).join('');
        mount.hidden = false;
        mount.classList.add('is-in');
      })
      .catch(function () { /* the statically built cards remain */ });
  }

  function esc(value) {
    return String(value == null ? '' : value)
      .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  /* Mirrors product_card() in tools/build_pages.py: a page must look the same
     whether its cards were baked in or fetched. */
  function card(prod, isVariant) {
    var photo = prod.images && prod.images[0] ? prod.images[0].path : '';
    var vid = prod.video && prod.video.id ? prod.video.id : '';

    var media = '';
    if (photo) {
      media = '<div class="product__media"><img src="' + esc(photo) + '" alt="'
            + esc(prod.title) + '" loading="lazy" decoding="async">'
            + (vid ? '<button class="product__play" type="button" data-video="' + esc(vid)
                   + '" aria-label="Voir la vidéo : ' + esc(prod.title) + '">' + '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8"/></svg>'
                   + '</button>' : '')
            + '</div>';
    }

    var specs = (prod.specs || []).filter(function (sp) { return sp.label || sp.value; });
    var specHtml = specs.length
      ? '<dl class="product__specs">' + specs.map(function (sp) {
          return '<div><dt>' + esc(sp.label) + '</dt><dd>' + esc(sp.value) + '</dd></div>';
        }).join('') + '</dl>'
      : '';

    var actions = (prod.docs || []).map(function (d) {
      return '<a class="btn btn--ghost btn--sm" href="' + esc(d.path) + '" download>'
           + '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>' + '<span>' + esc(d.label || 'Fiche technique (PDF)') + '</span></a>';
    });
    if (vid && !photo) {
      actions.push('<button class="btn btn--ghost btn--sm" type="button" data-video="'
        + esc(vid) + '">' + '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8"/></svg>' + '<span>Voir la vidéo</span></button>');
    }
    if (!isVariant) {
      actions.push('<a class="btn btn--primary btn--sm" href="devis.html">'
        + '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l1.9 5.1L19 10l-5.1 1.9L12 17l-1.9-5.1L5 10l5.1-1.9z"/></svg>' + '<span>Demander un devis</span></a>');
    }

    var subs = (prod.variants || []).filter(function (v) { return v.title; });
    var variantHtml = subs.length
      ? '<div class="product__variants"><h4>Déclinaisons</h4><ul>' + subs.map(function (v) {
          return '<li>' + card(v, true) + '</li>';
        }).join('') + '</ul></div>'
      : '';

    return '<article class="product' + (isVariant ? ' product--sub' : '') + '">' + media
         + '<div class="product__body"><h3>' + esc(prod.title) + '</h3>'
         + (prod.description ? '<p>' + esc(prod.description) + '</p>' : '')
         + specHtml + variantHtml
         + (actions.length ? '<div class="product__actions">' + actions.join('') + '</div>' : '')
         + '</div></article>';
  }

  /* ------------------------------------------------------------ misc --- */
  function initMisc() {
    var year = $('#year');
    if (year) year.textContent = String(new Date().getFullYear());

    var here = location.pathname.split('/').pop() || 'index.html';
    $$('.nav__list a[href], .drawer__list a[href]').forEach(function (a) {
      if (a.getAttribute('href') !== here) return;
      a.classList.add('is-current');
      a.setAttribute('aria-current', 'page');
      var item = a.closest('.nav__item');
      while (item) {
        item.classList.add('has-current');
        item = item.parentElement ? item.parentElement.closest('.nav__item') : null;
      }
    });
  }

  function init() {
    initHeader();
    initDrawer();
    initHero();
    initLightbox();
    initToTop();
    initReveal();
    initQuoteForm();
    initVideo();
    initCatalog();
    initMisc();
  }

  if (document.readyState === 'loading') on(document, 'DOMContentLoaded', init);
  else init();
})();
