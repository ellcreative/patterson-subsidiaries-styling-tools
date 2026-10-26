# Patterson Subsidiary Navigation System

A unified, accessible navigation system for Patterson subsidiary brands.

## Quick Start

1. **Install** the WordPress plugin from `patterson-navigation/`
2. **Activate** and configure in **Patterson Nav** settings
3. **Create menus** in Appearance → Menus
4. **Add to theme** using `<?php patterson_nav(); ?>` or `[patterson_navigation]` shortcode

See **[Installation Guide](docs/installation.md)** for complete instructions including classical and block theme setup.

## Features

- ✅ Two-tier navigation (Universal Patterson nav + brand-specific nav)
- ✅ Mega-menu dropdowns with multi-column layouts and featured content
- ✅ WCAG 2.2 Level AA accessible with full keyboard and screen reader support
- ✅ Mobile responsive with hamburger menu (drawer closes when a panel link is activated, e.g. same-page section anchors)
- ✅ Brand customization via CSS custom properties
- ✅ WordPress plugin with admin settings UI

## What's Included

```
patterson-subsidiaries-styling-tools/
├── patterson-navigation/   # WordPress plugin (ready to install)
├── design-tokens/          # CSS custom properties for brand consistency
├── prototypes/             # Static HTML/CSS/JS prototype
└── docs/                   # Installation and configuration guides
```

## Design source of truth

Visual nav styling (type, color, spacing, `--nav-*` tokens) is designed and first implemented on the **corporate Craft site** (`patenergy.com` / Patterson Craft SCSS: `_navigation-tokens.scss`, `_navigation.scss`). This WordPress plugin is the **WP port** of that look for subsidiaries.

- Port Craft token/selector deltas into `patterson-navigation/assets/css/` when the corporate nav changes.
- Keep the WP **mobile breakpoint default at 1420px** (admin-configurable). Craft hard-codes 1315px; denser subsidiary menus may need the higher default.
- Prefer shared short token names (`--nav-*`, `--font-body`) over site-specific prefixes unless a theme collision is proven.

## Documentation

- **[Installation Guide](docs/installation.md)** - Complete setup for classical and block themes
- **[Configuration Reference](docs/wordpress-configuration.md)** - All plugin settings and menu configuration
- **[Theme Compatibility](docs/wordpress-theme-compatibility.md)** - Preventing theme focus style conflicts
- **[Design Tokens README](design-tokens/README.md)** - Customizing colors, spacing, and typography

## Requirements

- **WordPress** 6.0+ and PHP 7.4+
- **Browser** with CSS custom properties support (Chrome 76+, Edge 17+, Safari 9+, Firefox 103+)

## Live Sites

- ulterra.com (WordPress 6.8.3)
- nextierofs.com (WordPress 6.8.3)
- patenergy.com (Craft CMS 4.16.13)
- superiorqc.com (WordPress 6.9)

## License

Proprietary - Patterson Companies
