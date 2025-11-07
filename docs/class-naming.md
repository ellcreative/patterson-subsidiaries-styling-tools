# Class Naming Convention

## Overview

The navigation system uses generic, brand-agnostic class names to work across all Patterson subsidiary brands. Only the universal navigation is Patterson-specific.

## Class Structure

### Universal Navigation (Patterson-specific)
- `.universal-nav` - The Patterson universal navigation wrapper
- `.universal-nav__logo` - Patterson logo container
- `.universal-nav__menu` - Universal navigation menu list

### Main Navigation (Brand-specific)
- `.main-nav` - The brand's main navigation wrapper
- `.main-nav__menu` - Main menu list
- `.main-nav__item` - Menu item
- `.main-nav__link` - Menu link/button
- `.main-nav__dropdown` - Dropdown mega menu
- `.main-nav__dropdown-item` - Item within dropdown
- `.main-nav__cta` - Call-to-action button
- `.main-nav__search` - Search button
- `.main-nav__mobile-toggle` - Mobile hamburger toggle
- `.main-nav__mobile-menu` - Mobile menu panel
- `.main-nav__mobile-link` - Mobile menu link

### Shared Classes
- `.site-navigation` - Overall navigation wrapper (contains both universal + main)
- `.nav-container` - Max-width container
- `.nav-icon` - SVG icon styling

## CSS Custom Properties

### Site Colors (Override per brand)
```css
:root {
  --primary-color: #e51b24;           /* Brand primary color */
  --primary-color-hover: ...;         /* Auto-calculated hover state */
  --primary-color-active: ...;        /* Auto-calculated active state */
}
```

### Usage in Components
```css
.main-nav__cta {
  background: var(--primary-color);
}

.main-nav__link:hover {
  color: var(--primary-color);
}
```

## Why This Structure?

### Separation of Concerns
- **Universal Nav**: Always Patterson branding, consistent across all subsidiaries
- **Main Nav**: Brand-specific, different for each subsidiary

### Brand Flexibility
- Each brand sets their own `--primary-color`
- Generic class names don't imply a specific brand
- Easy to style per brand without class name conflicts

### WordPress Plugin Structure
The plugin name is "Patterson Subsidiary Navigation" because it's distributed by Patterson, but the classes are generic so they work for:
- Patterson-UTI
- Ulterra
- NexTier
- Superior QC
- Any future subsidiaries

## Examples

### Patterson-UTI Implementation
```css
:root {
  --primary-color: #e51b24; /* Patterson red */
}
```

### Ulterra Implementation  
```css
:root {
  --primary-color: #0066cc; /* Ulterra blue */
}
```

### NexTier Implementation
```css
:root {
  --primary-color: #ff6600; /* NexTier orange */
}
```

All use the same HTML structure and classes:
```html
<header class="site-navigation">
  <nav class="universal-nav"><!-- Patterson nav --></nav>
  <nav class="main-nav"><!-- Brand-specific nav --></nav>
</header>
```

## Migration from Old Names

If you have older versions with `patterson-nav__` prefixes:

| Old Class | New Class |
|-----------|-----------|
| `.patterson-nav` | `.site-navigation` |
| `.patterson-nav__universal` | `.universal-nav` |
| `.patterson-nav__main` | `.main-nav` |
| `.patterson-nav__*` | `.main-nav__*` |
| `--brand-primary` | `--primary-color` |

