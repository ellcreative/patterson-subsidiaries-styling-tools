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

## Icon System

The navigation system uses embedded SVG icons with `currentColor` for automatic color inheritance. All icons are defined in the plugin's renderer class (`patterson-navigation/includes/class-renderer.php`).

### Available Icons

#### angle-right (9×14px)
Used for: Button hover states, navigation arrows

```html
<svg class="nav-icon" aria-hidden="true" width="9" height="14" viewBox="0 0 9 14" fill="none" xmlns="http://www.w3.org/2000/svg">
  <path d="M8.33398 6.80664L7.57031 7.57031L2.25781 12.8828L1.52734 13.6465L0 12.1191L0.763672 11.3887L5.3125 6.80664L0.763672 2.25781L0 1.49414L1.52734 0L2.25781 0.763672L7.57031 6.07617L8.33398 6.80664Z" fill="currentColor"/>
</svg>
```

#### square-arrow-up-right (13×13px)
Used for: External links

```html
<svg class="nav-icon nav-icon--external" aria-hidden="true" width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
  <path d="M10.9375 1.3125H1.3125V10.9375H10.9375V1.3125ZM12.25 0V1.3125V10.9375V12.25H10.9375H1.3125H0V10.9375V1.3125V0H1.3125H10.9375H12.25ZM8.53125 3.0625H9.1875V3.71875V8.09375V8.75H7.875V8.09375V5.30469L4.18359 8.99609L3.71875 9.46094L2.78906 8.53125L3.25391 8.06641L6.94531 4.375H4.375H3.71875V3.0625H4.375H8.53125Z" fill="currentColor"/>
</svg>
```

#### search (19×19px)
Used for: Search button

```html
<svg class="nav-icon" aria-hidden="true" width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
  <path d="M13.6562 7.71875C13.6562 5.60352 12.5059 3.67383 10.6875 2.59766C8.83203 1.52148 6.56836 1.52148 4.75 2.59766C2.89453 3.67383 1.78125 5.60352 1.78125 7.71875C1.78125 9.87109 2.89453 11.8008 4.75 12.877C6.56836 13.9531 8.83203 13.9531 10.6875 12.877C12.5059 11.8008 13.6562 9.87109 13.6562 7.71875ZM12.5059 13.8047C11.1699 14.8438 9.5 15.4375 7.71875 15.4375C3.45117 15.4375 0 11.9863 0 7.71875C0 3.48828 3.45117 0 7.71875 0C11.9492 0 15.4375 3.48828 15.4375 7.71875C15.4375 9.53711 14.8066 11.207 13.7676 12.543L18.3691 17.1445L19 17.7754L17.7383 19L17.1074 18.3691L12.5059 13.7676V13.8047Z" fill="currentColor"/>
</svg>
```

#### angle-down (16×9px)
Used for: Dropdown menu toggles

```html
<svg class="nav-icon" aria-hidden="true" width="16" height="9" viewBox="0 0 16 9" fill="none" xmlns="http://www.w3.org/2000/svg">
  <path d="M7.57812 8.90625L6.91406 8.24219L0.664062 1.99219L0 1.32812L1.32812 0L1.99219 0.664062L7.57812 6.28906L13.1641 0.703125L13.8281 0.0390625L15.1562 1.32812L14.4922 1.99219L8.24219 8.24219L7.57812 8.90625Z" fill="currentColor"/>
</svg>
```

#### close (18×18px)
Used for: Mobile menu close button

```html
<svg class="nav-icon" aria-hidden="true" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
  <path d="M2 2L16 16M16 2L2 16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
</svg>
```

### Icon Usage

Icons use `currentColor` for `fill` or `stroke` attributes, which means they automatically inherit the text color from their parent element. This makes them work seamlessly in both light and dark contexts.

**In PHP (WordPress plugin):**
```php
echo Patterson_Nav_Renderer::get_icon('angle-right');
```

**In HTML:**
```html
<button class="c-button c-button--primary">
  <span>Learn More</span>
  <svg class="nav-icon" aria-hidden="true" width="9" height="14" viewBox="0 0 9 14" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M8.33398 6.80664L7.57031 7.57031L2.25781 12.8828L1.52734 13.6465L0 12.1191L0.763672 11.3887L5.3125 6.80664L0.763672 2.25781L0 1.49414L1.52734 0L2.25781 0.763672L7.57031 6.07617L8.33398 6.80664Z" fill="currentColor"/>
  </svg>
</button>
```

### Icon Styling

Icons are styled through the `.nav-icon` class in `tokens.css`:

```css
.nav-icon {
  display: inline-block;
  flex-shrink: 0;
  vertical-align: middle;
}
```

For buttons, icons are hidden by default and appear on hover:

```css
.c-button .nav-icon {
  opacity: 0;
  inline-size: 0;
  transition: opacity 300ms, inline-size 300ms;
}

.c-button:hover .nav-icon,
.c-button:focus-visible .nav-icon {
  opacity: 1;
  inline-size: var(--button-icon-size);
}
```

