# Patterson Subsidiary Navigation System

A unified, accessible navigation system for Patterson subsidiary brands with design tokens and WordPress plugin support.

## Quick Start

### For WordPress Sites

1. **Install Plugin**
   ```bash
   # Copy wordpress folder to your plugins directory
   cp -r wordpress /path/to/wp-content/plugins/patterson-navigation
   ```

2. **Activate & Configure**
   - Activate plugin in WordPress Admin → Plugins
   - Go to **Patterson Nav** settings
   - Select your menus and configure options
   
3. **Add to Theme**
   ```php
   <?php patterson_nav(); ?>
   ```

**That's it!** See [Installation Guide](docs/installation.md) for detailed steps.

## Features

- ✅ **Two-tier navigation** - Universal Patterson nav + brand-specific nav
- ✅ **Mega-menu dropdowns** - Multi-column layouts with featured content
- ✅ **Fully accessible** - WCAG 2.2 Level AA compliant
- ✅ **Mobile responsive** - Hamburger menu with slide-out panel
- ✅ **Keyboard navigation** - Full keyboard and screen reader support
- ✅ **Brand customization** - Easy color theming via CSS custom properties
- ✅ **WordPress plugin** - Easy integration with admin settings

## What's Included

```
patterson-subsidiaries-styling-tools/
├── design-tokens/          # CSS custom properties for all brands
├── prototypes/             # Static HTML/CSS/JS prototype
├── wordpress/              # WordPress plugin (ready to install)
└── docs/                   # Documentation
```

## Documentation

### Getting Started
- **[Installation Guide](docs/installation.md)** - Step-by-step setup
- **[Implementation Guide](docs/implementation-guide.md)** - Integration patterns
- **[WordPress Configuration](docs/wordpress-configuration.md)** - All settings explained

### Design Tokens
See [design-tokens/README.md](design-tokens/README.md) for usage.

## Design Tokens

The `design-tokens/tokens.css` file provides consistent styling across all brands:

```css
/* In your site's CSS */
:root {
  --primary-color: #YOUR_BRAND_COLOR;
}
```

All spacing, typography, colors, and transitions use these tokens for consistency.

## Browser Support

- Chrome 76+
- Edge 17+
- Safari 9+
- Firefox 103+

## Requirements

- **WordPress**: 6.0+ and PHP 7.4+
- **Design Tokens**: Modern browser with CSS custom properties support

## Live Sites Using This System

1. **ulterra.com** - WordPress 6.8.3
2. **nextierofs.com** - WordPress 6.8.3
3. **patenergy.com** - Craft CMS 4.16.13
4. **superiorqc.com** - WordPress 6.9

## Support

Contact the development team for questions or issues.

## License

Proprietary - Patterson Companies
