/*!
 * Alam Stores - static site behaviour
 *
 * Replaces the jQuery + Bootstrap + select2 + prettyPhoto + owl-carousel stack
 * that the WordPress theme used to load (~300 KB) with a dependency-free
 * implementation of the behaviour those plugins actually provided on the
 * front end: collapsible navigation, dropdown submenus, the hero slider,
 * the image lightbox and the scroll-to-top control.
 */
(function () {
  'use strict';

  var on = function (el, ev, fn, opts) { el && el.addEventListener(ev, fn, opts || false); };
  var $ = function (sel, ctx) { return (ctx || document).querySelector(sel); };
  var $$ = function (sel, ctx) { return Array.prototype.slice.call((ctx || document).querySelectorAll(sel)); };
  var prefersReduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ------------------------------------------------------------------ *
   * Mobile navigation
   * Mirrors the Bootstrap 4 collapse markup the theme's header emitted.
   * ------------------------------------------------------------------ */
  function initNav() {
    var toggler = $('.navbar-toggler');
    var target = toggler && document.getElementById(
      (toggler.getAttribute('data-target') || '').replace('#', '')
    );
    if (!toggler || !target) return;

    function setOpen(open) {
      target.classList.toggle('show', open);
      toggler.setAttribute('aria-expanded', open ? 'true' : 'false');
    }

    on(toggler, 'click', function (e) {
      e.preventDefault();
      setOpen(!target.classList.contains('show'));
    });

    /* Submenu toggles. On desktop the dropdowns open on hover via CSS; the
       button is the keyboard- and touch-accessible path. */
    $$('.menu-item-has-children > .dropdown-toggle').forEach(function (btn) {
      on(btn, 'click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var li = btn.parentNode;
        var isOpen = li.classList.contains('active_sub');
        /* Close siblings so only one branch is open at a time. */
        $$('.active_sub', li.parentNode).forEach(function (o) {
          if (o !== li) o.classList.remove('active_sub');
        });
        li.classList.toggle('active_sub', !isOpen);
        btn.setAttribute('aria-expanded', !isOpen ? 'true' : 'false');
      });
    });

    /* Close the menu when a real link is followed or focus leaves it. */
    on(document, 'click', function (e) {
      if (!target.contains(e.target) && !toggler.contains(e.target)) {
        setOpen(false);
        $$('.active_sub').forEach(function (o) { o.classList.remove('active_sub'); });
      }
    });
    on(document, 'keydown', function (e) {
      if (e.key === 'Escape') {
        setOpen(false);
        $$('.active_sub').forEach(function (o) { o.classList.remove('active_sub'); });
      }
    });
  }

  /* ------------------------------------------------------------------ *
   * Hero slider
   * ------------------------------------------------------------------ */
  function initSlider() {
    $$('[data-slider]').forEach(function (root) {
      var slides = $$('.slide', root);
      if (slides.length < 2) { if (slides[0]) slides[0].classList.add('is-active'); return; }

      var dotsWrap = $('.slider-dots', root);
      var index = 0;
      var timer = null;
      var delay = parseInt(root.getAttribute('data-interval'), 10) || 6000;

      var dots = slides.map(function (_, i) {
        if (!dotsWrap) return null;
        var b = document.createElement('button');
        b.type = 'button';
        b.className = 'slider-dot';
        b.setAttribute('aria-label', 'Diapositive ' + (i + 1));
        on(b, 'click', function () { go(i); restart(); });
        dotsWrap.appendChild(b);
        return b;
      });

      function go(i) {
        index = (i + slides.length) % slides.length;
        slides.forEach(function (s, n) {
          var active = n === index;
          s.classList.toggle('is-active', active);
          s.setAttribute('aria-hidden', active ? 'false' : 'true');
        });
        dots.forEach(function (d, n) { d && d.classList.toggle('is-active', n === index); });
      }
      function next() { go(index + 1); }
      function prev() { go(index - 1); }
      function restart() { stop(); start(); }
      function start() { if (!prefersReduced) timer = setInterval(next, delay); }
      function stop() { if (timer) { clearInterval(timer); timer = null; } }

      on($('.slider-next', root), 'click', function () { next(); restart(); });
      on($('.slider-prev', root), 'click', function () { prev(); restart(); });
      on(root, 'mouseenter', stop);
      on(root, 'mouseleave', start);
      on(root, 'focusin', stop);
      on(root, 'focusout', start);

      /* Touch swipe */
      var x0 = null;
      on(root, 'touchstart', function (e) { x0 = e.touches[0].clientX; stop(); }, { passive: true });
      on(root, 'touchend', function (e) {
        if (x0 === null) return;
        var dx = e.changedTouches[0].clientX - x0;
        if (Math.abs(dx) > 40) { dx < 0 ? next() : prev(); }
        x0 = null;
        start();
      });

      go(0);
      start();
    });
  }

  /* ------------------------------------------------------------------ *
   * Lightbox (replaces prettyPhoto)
   * ------------------------------------------------------------------ */
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
      '<button type="button" class="lightbox-close" aria-label="Fermer">&times;</button>' +
      '<button type="button" class="lightbox-prev" aria-label="Image précédente">&#10094;</button>' +
      '<figure class="lightbox-figure"><img alt=""><figcaption></figcaption></figure>' +
      '<button type="button" class="lightbox-next" aria-label="Image suivante">&#10095;</button>';
    document.body.appendChild(box);

    var img = $('img', box);
    var cap = $('figcaption', box);
    var current = 0;
    var group = [];
    var lastFocus = null;

    function show(i) {
      current = (i + group.length) % group.length;
      var a = group[current];
      img.src = a.getAttribute('href');
      img.alt = a.getAttribute('data-caption') || '';
      cap.textContent = a.getAttribute('data-caption') || '';
      cap.hidden = !cap.textContent;
    }
    function open(a) {
      group = links.filter(function (l) {
        return l.getAttribute('data-lightbox') === a.getAttribute('data-lightbox');
      });
      lastFocus = document.activeElement;
      box.hidden = false;
      document.body.classList.add('lightbox-open');
      show(group.indexOf(a));
      $('.lightbox-close', box).focus();
    }
    function close() {
      box.hidden = true;
      img.removeAttribute('src');
      document.body.classList.remove('lightbox-open');
      lastFocus && lastFocus.focus();
    }

    links.forEach(function (a) {
      on(a, 'click', function (e) { e.preventDefault(); open(a); });
    });
    on($('.lightbox-close', box), 'click', close);
    on($('.lightbox-next', box), 'click', function () { show(current + 1); });
    on($('.lightbox-prev', box), 'click', function () { show(current - 1); });
    on(box, 'click', function (e) { if (e.target === box) close(); });
    on(document, 'keydown', function (e) {
      if (box.hidden) return;
      if (e.key === 'Escape') close();
      if (e.key === 'ArrowRight') show(current + 1);
      if (e.key === 'ArrowLeft') show(current - 1);
    });
  }

  /* ------------------------------------------------------------------ *
   * Scroll to top - same markup and thresholds the theme's script used
   * ------------------------------------------------------------------ */
  function initScrollUp() {
    var a = document.createElement('a');
    a.id = 'scrollUp';
    a.href = '#top';
    a.title = 'Haut de page';
    a.setAttribute('aria-label', 'Retour en haut de page');
    a.innerHTML = '<i class="fas fa-angle-up" aria-hidden="true"></i>';
    document.body.appendChild(a);

    var shown = false;
    function update() {
      var should = (window.pageYOffset || document.documentElement.scrollTop) > 600;
      if (should !== shown) {
        shown = should;
        a.classList.toggle('is-visible', shown);
      }
    }
    on(window, 'scroll', update, { passive: true });
    on(a, 'click', function (e) {
      e.preventDefault();
      window.scrollTo({ top: 0, behavior: prefersReduced ? 'auto' : 'smooth' });
    });
    update();
  }

  /* ------------------------------------------------------------------ *
   * Smooth in-page anchors
   * ------------------------------------------------------------------ */
  function initAnchors() {
    $$('a[href^="#"]:not([href="#"]):not([data-lightbox])').forEach(function (a) {
      on(a, 'click', function (e) {
        var t = document.getElementById(a.getAttribute('href').slice(1));
        if (!t) return;
        e.preventDefault();
        t.scrollIntoView({ behavior: prefersReduced ? 'auto' : 'smooth', block: 'start' });
        t.setAttribute('tabindex', '-1');
        t.focus({ preventScroll: true });
      });
    });
  }

  /* ------------------------------------------------------------------ *
   * Quote form - client-side validation only.
   * The static site has no backend, so the form posts to whatever endpoint
   * is configured in its `action` attribute (a mail service, Formspree,
   * etc.). Until one is set the form reports that it is not connected
   * rather than silently losing the message.
   * ------------------------------------------------------------------ */
  function initForms() {
    $$('form[data-validate]').forEach(function (form) {
      var status = $('.form-status', form);
      on(form, 'submit', function (e) {
        var invalid = null;
        $$('[required]', form).forEach(function (field) {
          var bad = !field.value.trim() || (field.type === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value));
          field.classList.toggle('is-invalid', bad);
          if (bad && !invalid) invalid = field;
        });
        if (invalid) {
          e.preventDefault();
          invalid.focus();
          if (status) {
            status.textContent = 'Merci de compléter les champs obligatoires.';
            status.className = 'form-status is-error';
          }
          return;
        }
        if (!form.getAttribute('action')) {
          e.preventDefault();
          if (status) {
            status.textContent = "Ce formulaire n'est pas encore relié à un service d'envoi. "
              + 'Renseignez l\'attribut action du formulaire dans devis.html.';
            status.className = 'form-status is-error';
          }
        }
      });
    });
  }

  function init() {
    initNav();
    initSlider();
    initLightbox();
    initScrollUp();
    initAnchors();
    initForms();
    /* Flag the active nav entry for the current document. */
    var here = location.pathname.split('/').pop() || 'index.html';
    $$('.navbar a[href]').forEach(function (a) {
      if (a.getAttribute('href') === here) {
        a.classList.add('is-current');
        a.setAttribute('aria-current', 'page');
        var li = a.closest('.menu-item-has-children');
        if (li) li.classList.add('has-current');
      }
    });
  }

  if (document.readyState === 'loading') {
    on(document, 'DOMContentLoaded', init);
  } else {
    init();
  }
})();
