# Patterson Design Tokens

Single source of truth for design tokens across all Patterson subsidiary sites.

## Brand Typography

Each Patterson subsidiary uses Adobe Typekit for custom fonts. Include the appropriate link in your site's `<head>` section:

### Patterson UTI

```html
<link rel="stylesheet" href="https://use.typekit.net/peq2jlg.css">
```

**Typekit Code**: `peq2jlg`

### Nextier

```html
<link rel="stylesheet" href="https://use.typekit.net/bqc1fxq.css">
```

**Typekit Code**: `bqc1fxq`

### Ulterra

```html
<link rel="stylesheet" href="https://use.typekit.net/eyo6evt.css">
```

**Typekit Code**: `eyo6evt`

### SuperiorQC

```html
<link rel="stylesheet" href="https://use.typekit.net/afd5ryn.css">
```

**Typekit Code**: `afd5ryn`

**Note**: The Patterson Navigation plugin automatically loads the correct Typekit file based on your subsidiary configuration. Only include these manually if you're implementing brand styles outside of the plugin.

## Location

The tokens file is located at:
```
patterson-navigation/assets/css/tokens.css
```

This file is included automatically when using the Patterson Navigation plugin.

## Usage

The Patterson Navigation plugin automatically enqueues the tokens.css file.

### For non-WordPress projects
Include the tokens file directly:

#### HTML
```html
<link rel="stylesheet" href="path/to/patterson-navigation/assets/css/tokens.css">
```

#### CSS
```css
@import url('path/to/patterson-navigation/assets/css/tokens.css');
```

#### Sass/SCSS
```scss
@import 'path/to/patterson-navigation/assets/css/tokens.css';
```

## Customization Per Site

Override the primary site color in your site's CSS:

```css
:root {
  --primary-color: #YOUR_BRAND_COLOR;
}
```

All other tokens will automatically adjust based on this.

## Using Tokens

```css
.my-component {
  color: var(--primary-color);
  padding: var(--space-4);
  font-family: var(--font-heading);
  border-radius: var(--border-radius-default);
  transition: all var(--transition-speed-default) var(--transition-easing-default);
}
```

## Browser Support

CSS custom properties are supported by:
- Chrome 49+ (2016)
- Edge 15+ (2017)
- Safari 9.1+ (2016)
- Firefox 31+ (2014)

All Patterson subsidiary sites meet these requirements.

