# Patterson Subsidiaries Styling Tools

A unified navigation system and design token framework for Patterson subsidiary brands.

## Repository Structure

```
patterson-subsidiaries-styling-tools/
├── design-tokens/          # Brand design tokens (CSS, SCSS, JSON)
├── navigation-plugin/      # Menu/navigation plugin
│   ├── shared/            # Framework-agnostic code
│   ├── wordpress/         # WordPress implementation
│   └── craft/             # Craft CMS implementation
├── docs/                  # Documentation
└── prototypes/            # Static HTML prototypes
```

## Components

### 1. Design Tokens
Centralized design system tokens (colors, typography, spacing) used across all Patterson subsidiary sites and the navigation plugin.

### 2. Navigation Plugin
Multi-CMS navigation plugin supporting:
- WordPress (6.8.3+)
- Craft CMS (4.16+)

**Features:**
- Two-tier navigation (universal + main nav)
- Mega-menu dropdowns with multi-column layout
- Featured content areas per dropdown
- Configurable search integration
- Brand color theming
- Mobile hamburger menu
- WCAG 2.2 AA accessible
- Legacy browser support

## Sites Using This System

1. **patenergy.com** - Craft CMS 4.16.13
2. **ulterra.com** - WordPress 6.8.3
3. **nextierofs.com** - WordPress 6.8.3
4. **superiorqc.com** - WordPress 6.9

## Getting Started

See [docs/getting-started.md](docs/getting-started.md) for installation and configuration instructions.

## Development

### Requirements
- PHP 7.4+ (for WordPress plugin)
- Craft CMS 4+ (for Craft plugin)
- Modern browser or legacy browser support (Chrome 76+, Edge 17+, Safari 9+, Firefox 103+)

### Local Development
```bash
# Clone the repository
git clone [repository-url]
cd patterson-subsidiaries-styling-tools

# View static prototype
open prototypes/index.html
```

## Documentation

- [Installation Guide](docs/installation.md)
- [Configuration](docs/configuration.md)
- [Design Tokens](docs/design-tokens.md)
- [WordPress Setup](docs/wordpress-setup.md)
- [Craft CMS Setup](docs/craft-setup.md)
- [Customization](docs/customization.md)

## License

Proprietary - Patterson Companies

## Support

Contact the development team for support and questions.

