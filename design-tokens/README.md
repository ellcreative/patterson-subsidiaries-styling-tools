# Patterson Design Tokens

Single source of truth for design tokens across all Patterson subsidiary sites.

## Usage

Include `tokens.css` in your site - it works for all environments:
- ✅ WordPress (with or without preprocessors)
- ✅ Craft CMS (with or without build tools)
- ✅ Sites with Sass/SCSS
- ✅ Sites with vanilla CSS

### HTML
```html
<link rel="stylesheet" href="path/to/tokens.css">
```

### CSS
```css
@import url('path/to/tokens.css');
```

### Sass/SCSS
```scss
@import 'path/to/tokens.css';
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

