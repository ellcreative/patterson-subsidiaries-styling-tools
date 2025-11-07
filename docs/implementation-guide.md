# Implementation Guide

## Navigation Positioning

The site navigation is designed to overlay your hero/header component. It uses `position: absolute` to sit on top of your site's background image or hero section.

The navigation consists of two parts:
1. **Universal Nav** (`.universal-nav`) - Patterson-specific, always visible
2. **Main Nav** (`.main-nav`) - Your brand's navigation

### HTML Structure

Your site's hero/header should be wrapped in a `position: relative` container:

```html
<div class="your-hero-section" style="position: relative;">
  
  <!-- Site Navigation overlays here -->
  <header class="site-navigation">
    <nav class="universal-nav"><!-- Patterson nav --></nav>
    <nav class="main-nav"><!-- Your brand nav --></nav>
  </header>
  
  <!-- Your hero content -->
  <div class="hero-content">
    <h1>Your Title</h1>
    <p>Your content...</p>
  </div>
  
</div>
```

### CSS Requirements

Your hero/header wrapper needs:

```css
.your-hero-section {
  position: relative; /* Required for absolute positioning */
  min-height: 100vh; /* Or your desired height */
  background-image: url('your-hero-image.jpg');
  background-size: cover;
  background-position: center;
}
```

Your hero content should account for the navigation height (134px):

```css
.your-hero-content {
  padding-top: calc(134px + 2rem); /* Nav height + spacing */
}
```

### WordPress Implementation

#### In your theme's header.php or page template:

```php
<div class="site-hero">
  <?php patterson_nav(); ?>
  
  <div class="hero-content">
    <!-- Your hero content -->
  </div>
</div>
```

#### In your theme's style.css:

```css
.site-hero {
  position: relative;
  min-height: 100vh;
  background: url('assets/hero-bg.jpg') center/cover;
}

.hero-content {
  padding-top: 150px; /* Account for nav */
}
```

### Craft CMS Implementation

#### In your layout template:

```twig
<div class="site-hero">
  {{ craft.pattersonNav.render() }}
  
  <div class="hero-content">
    {# Your hero content #}
  </div>
</div>
```

## Navigation Height

The navigation has a fixed total height:
- Universal Nav: 46px
- Main Nav: 88px
- **Total: 134px**

Make sure your hero content accounts for this when positioning elements.

## Mobile Considerations

On mobile (<1024px), the navigation height is reduced:
- Universal Nav: 46px (min)
- Main Nav: 64px (min)
- **Total: ~110px**

The mobile menu is a slide-in panel and doesn't affect layout.

## Z-Index

The navigation uses `z-index: var(--z-index-fixed)` which is `1030` by default.

If your hero has interactive elements (video, carousel, etc.), make sure their z-index is lower than the navigation.

## Brand Customization

Each brand should override the `--primary-color` CSS custom property:

```css
:root {
  --primary-color: #YOUR_BRAND_COLOR;
}
```

This color will be used for:
- CTA button background
- Hover states
- Active states
- Any brand-specific styling

## Best Practices

1. **Always wrap the nav in a `position: relative` container**
2. **Account for nav height in your hero content padding**
3. **Use white text on the main nav items** (they're designed for dark backgrounds)
4. **Set your brand's primary color** via CSS custom property
5. **Test dropdowns on various background colors** to ensure visibility
6. **The universal nav has a semi-transparent dark background** - it works on any background

## Examples

### Example 1: Simple Hero

```php
<header class="page-hero" style="position: relative; background: url('hero.jpg') center/cover; min-height: 100vh;">
  <?php patterson_nav(); ?>
  
  <div style="padding-top: 200px; padding-left: 80px;">
    <h1 style="color: white; font-size: 96px;">Welcome</h1>
  </div>
</header>
```

### Example 2: Video Background

```php
<div class="video-hero" style="position: relative; overflow: hidden;">
  <?php patterson_nav(); ?>
  
  <video autoplay muted loop style="position: absolute; width: 100%; height: 100%; object-fit: cover; z-index: 0;">
    <source src="hero-video.mp4" type="video/mp4">
  </video>
  
  <div style="position: relative; z-index: 1; padding-top: 200px;">
    <h1>Your Content</h1>
  </div>
</div>
```

### Example 3: Image Slider Background

```php
<div class="slider-hero" style="position: relative;">
  <?php patterson_nav(); ?>
  
  <div class="slider" style="height: 100vh;">
    <!-- Your slider images here -->
  </div>
  
  <div class="hero-text" style="position: absolute; top: 200px; left: 80px; z-index: 2;">
    <h1>Your Title</h1>
  </div>
</div>
```

## Troubleshooting

### Navigation is not visible
- Check that the parent container has `position: relative`
- Verify z-index isn't being overridden
- Ensure the navigation CSS is loaded

### Text is hard to read
- Check your background image brightness
- Consider adding an overlay: `background: rgba(0,0,0,0.5);`
- Adjust the design tokens brand color for better contrast

### Dropdowns are cut off
- Ensure no parent has `overflow: hidden`
- Check that the dropdown z-index is higher than other elements

### Mobile menu doesn't open
- Verify JavaScript is loading
- Check browser console for errors
- Ensure no other scripts are interfering with event handlers

