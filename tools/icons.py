# -*- coding: utf-8 -*-
"""
Inline SVG icons.

These replace the four icon fonts the old WordPress theme loaded
(Font Awesome, Flaticon x10, Elegant Icons, Material Design Iconic) — about
150 KB of CSS and 1.4 MB of font files — with a handful of inlined paths.

All icons use a 24x24 viewBox and `currentColor`, so they inherit text colour.
"""

_STROKE = ('viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" '
           'stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"')
_SOLID = 'viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"'

_PATHS = {
    # navigation / chrome
    "chevron-down":  (_STROKE, '<polyline points="6 9 12 15 18 9"/>'),
    "chevron-right": (_STROKE, '<polyline points="9 18 15 12 9 6"/>'),
    "chevron-left":  (_STROKE, '<polyline points="15 18 9 12 15 6"/>'),
    "arrow-right":   (_STROKE, '<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>'),
    "arrow-up":      (_STROKE, '<line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/>'),
    "close":         (_STROKE, '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>'),

    # contact
    "phone": (_STROKE, '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 '
                       '19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 '
                       '2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 '
                       '2.81.7A2 2 0 0 1 22 16.92z"/>'),
    "mail":  (_STROKE, '<rect x="2" y="4" width="20" height="16" rx="2"/><polyline points="22 6 12 13 2 6"/>'),
    "pin":   (_STROKE, '<path d="M21 10c0 7-9 13-9 13س".replace("س","")/>'),
    "clock": (_STROKE, '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>'),
    "whatsapp": (_SOLID, '<path d="M17.47 14.38c-.3-.15-1.75-.86-2.02-.96-.27-.1-.47-.15-.67.15-.2.3-.77.96-.94 '
                         '1.16-.17.2-.35.22-.65.07-.3-.15-1.25-.46-2.38-1.47-.88-.78-1.47-1.75-1.65-2.05-.17-.3-.02-.46.13-.61.14-.14.3-.35.45-.53.15-.18.2-.3.3-.5.1-.2.05-.38-.02-.53-.08-.15-.67-1.61-.92-2.2-.24-.58-.49-.5-.67-.51h-.57c-.2 '
                         '0-.52.07-.79.38-.27.3-1.04 1.01-1.04 2.47 0 1.46 1.06 2.87 1.21 3.07.15.2 2.1 3.2 5.08 '
                         '4.49.71.31 1.26.49 1.69.63.71.22 1.36.19 1.87.12.57-.09 1.75-.72 2-1.41.25-.69.25-1.28.17-1.41-.07-.13-.27-.2-.57-.35z'
                         'M12.04 2.5A9.46 9.46 0 0 0 4 16.86L2.5 21.5l4.77-1.5a9.46 9.46 0 1 0 4.77-17.5zm0 '
                         '17.16a7.7 7.7 0 0 1-3.92-1.07l-.28-.17-2.9.91.93-2.83-.18-.29a7.7 7.7 0 1 1 6.35 3.45z"/>'),

    # trust / feature
    "check":     (_STROKE, '<polyline points="20 6 9 17 4 12"/>'),
    "check-circle": (_STROKE, '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>'),
    "ruler":     (_STROKE, '<path d="M2 15.5 8.5 22 22 8.5 15.5 2z"/><path d="M6 12l2 2"/><path d="M9 9l2 2"/><path d="M12 6l2 2"/>'),
    "truck":     (_STROKE, '<rect x="1" y="6" width="14" height="11" rx="1"/><path d="M15 9h4l3 3v5h-7z"/>'
                           '<circle cx="6" cy="19" r="2"/><circle cx="18" cy="19" r="2"/>'),
    "shield":    (_STROKE, '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>'),
    "sparkle":   (_STROKE, '<path d="M12 3l1.9 5.1L19 10l-5.1 1.9L12 17l-1.9-5.1L5 10l5.1-1.9z"/>'),
    "sun":       (_STROKE, '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4'
                           'M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>'),
    "wrench":    (_STROKE, '<path d="M14.7 6.3a4 4 0 0 0 5 5l-9.4 9.4a2.1 2.1 0 0 1-3-3z"/><path d="M14.7 6.3 18 3"/>'),
    "users":     (_STROKE, '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>'
                           '<path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>'),
    "zoom":      (_STROKE, '<circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>'
                           '<line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/>'),
    "menu":      (_STROKE, '<line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/>'
                           '<line x1="3" y1="18" x2="21" y2="18"/>'),
    "facebook":  (_SOLID, '<path d="M22 12a10 10 0 1 0-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.5 1.49-3.89 3.77-3.89'
                          '1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.78l-.45 2.89h-2.33v6.99'
                          'A10 10 0 0 0 22 12z"/>'),
    "instagram": (_STROKE, '<rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/>'
                           '<circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/>'),
}

# The pin path above is written out properly here to keep the table readable.
_PATHS["pin"] = (_STROKE,
                 '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>')


def icon(name, cls=""):
    """Return an inline <svg> for `name`. Unknown names render nothing."""
    entry = _PATHS.get(name)
    if not entry:
        return ""
    attrs, body = entry
    class_attr = ' class="%s"' % cls if cls else ""
    return '<svg xmlns="http://www.w3.org/2000/svg" %s%s>%s</svg>' % (attrs, class_attr, body)
