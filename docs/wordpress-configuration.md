# Configuration Reference

Complete reference for all Patterson Navigation plugin settings.

## Table of Contents

- [Overview](#overview)
- [Admin Settings](#admin-settings)
  - [Universal Navigation Settings](#universal-navigation-settings)
  - [Subsidiary Configuration](#subsidiary-configuration)
  - [Main Navigation Settings](#main-navigation-settings)
  - [Brand Logo Settings](#brand-logo-settings)
  - [Search Settings](#search-settings)
  - [CTA Button Settings](#cta-button-settings)
  - [Design & Branding Settings](#design--branding-settings)
- [Advanced Usage: Per-Page Overrides](#advanced-usage-per-page-overrides)
  - [Navigation Mode Override](#navigation-mode-override)
  - [Overlay Background Override](#overlay-background-override)
  - [Brand Logo Override](#brand-logo-override)
  - [Combining Multiple Parameters](#combining-multiple-parameters)
- [Menu Configuration](#menu-configuration)
  - [Enable Custom Fields](#enable-custom-fields-in-menu-editor)
  - [Menu Item Descriptions](#menu-item-descriptions-child-items-only)
  - [Featured Content](#featured-content-top-level-items-only)
- [Best Practices](#best-practices)
- [Additional Documentation](#additional-documentation)

## Overview

The Patterson Navigation plugin provides a universal Patterson Companies navigation bar plus customizable brand-specific main navigation with support for mega-menus, mobile menus, and extensive accessibility features.

**Note**: The universal navigation (Patterson Companies top bar) is always present and cannot be disabled.

## Additional Documentation

- [WordPress Theme Compatibility](wordpress-theme-compatibility.md) - How to prevent theme focus style conflicts

## Admin Settings

Navigate to **WordPress Admin** → **Patterson Nav**

### Universal Navigation Settings

The universal navigation displays the Patterson Companies branding and links above the main navigation.

#### Universal Nav Menu

- **Type**: Menu Selector
- **Default**: None
- **Description**: Select which WordPress menu to display in the universal navigation bar
- **Note**: Create a menu in Appearance → Menus first. The universal nav bar itself is always visible - this setting controls which menu items appear in it.

### Subsidiary Configuration

#### Subsidiary

- **Type**: Dropdown Select
- **Default**: Custom
- **Options**: 
  - Ulterra
  - NexTier
  - Superior QC
  - Custom
- **Description**: Select which Patterson subsidiary this site represents. Preset configurations automatically apply the correct branding, colors, fonts, and logos.

#### Subsidiary Presets

When you select a subsidiary preset, the following settings are automatically configured:

**Ulterra**
- Brand Logo: Enabled (Ulterra logo)
- Brand Color: `#06929F` (Teal)
- Typekit Font: `eyo6evt`
- Logo Dimensions: 198px × 24px

**NexTier**
- Brand Logo: Enabled (NexTier logo)
- Brand Color: `#037D3F` (Green)
- Typekit Font: `bqc1fxq`
- Logo Dimensions: 198px × 24px

**Superior QC**
- Brand Logo: Enabled (Superior QC logo)
- Brand Color: `#DF181D` (Red)
- Typekit Font: `afd5ryn`
- Logo Dimensions: 198px × 24px

**Custom**
- Allows manual configuration of all branding settings
- Shows all customization fields for complete control
- Use this option for subsidiaries not listed or for unique configurations

**Note**: When a preset subsidiary is selected, the brand logo, color, and typekit fields are automatically configured and hidden. Switch to "Custom" to manually override any values.

### Main Navigation Settings

#### Main Nav Menu

- **Type**: Menu Selector
- **Default**: None
- **Description**: Select which WordPress menu to use for the main navigation
- **Note**: Supports dropdowns and featured content

### Brand Logo Settings

**Note**: These fields are only visible when "Custom" is selected in Subsidiary Configuration.

#### Enable Brand Logo

- **Type**: Checkbox
- **Default**: Unchecked
- **Description**: Shows your brand logo before the navigation menu items
- **Visibility**: Custom mode only

#### Brand Logo SVG Code

- **Type**: Textarea
- **Default**: Empty
- **Description**: Paste your complete SVG code for the brand logo
- **Visibility**: Custom mode only (when "Enable Brand Logo" is checked)
- **Important Requirements**:
  - Must be valid SVG code
  - Replace the **main color** (typically white) with `currentColor` for dark mode support
  - Keep accent/brand colors as defined values (e.g., `#06929F`)
  - Include a `viewBox` attribute for proper scaling
  - **Do not** upload as a file - paste the actual SVG code
  
**Why `currentColor`?**
Using `currentColor` for the main logo color allows it to automatically adapt when Navigation Mode is set to Dark Mode. The logo will be white in Light Mode and dark in Dark Mode.

**Example - Before:**

```svg
<svg viewBox="0 0 200 24" fill="none">
  <path fill="#ffffff" d="..." />
  <path fill="#06929F" d="..." />
</svg>
```

**Example - After (ready for dark mode):**

```svg
<svg viewBox="0 0 200 24" fill="none">
  <path fill="currentColor" d="..." />
  <path fill="#06929F" d="..." />
</svg>
```

**What to Replace:**
- `fill="#fff"` → `fill="currentColor"`
- `fill="#ffffff"` → `fill="currentColor"`  
- `fill="white"` → `fill="currentColor"`
- `stroke="#fff"` → `stroke="currentColor"`

**What to Keep:**
- Brand/accent colors like `#06929F`, `#037D3F`, etc.
- Any multi-color elements or graphics

**Security Note:** The plugin automatically sanitizes SVG code to remove scripts, event handlers, and unsafe content.

### Search Settings

#### Enable Search

- **Type**: Checkbox
- **Default**: Unchecked
- **Description**: Shows a search button in the navigation

#### Search Code/Shortcode

- **Type**: Textarea
- **Default**: Empty
- **Description**: Enter a shortcode (e.g., `[search_form]`) or custom HTML/JavaScript code
- **Example Shortcodes**:
  - `[search_form]` - Default WordPress search
  - `[your_search_plugin_shortcode]` - Third-party search plugin
- **Example HTML**: Custom search modal trigger button

### CTA Button Settings

#### Enable CTA Button

- **Type**: Checkbox
- **Default**: Unchecked
- **Description**: Shows a call-to-action button in the navigation

#### CTA Button Text

- **Type**: Text
- **Default**: Empty
- **Description**: The text to display on the CTA button
- **Example**: "Contact", "Get Started", "Request Demo"

#### CTA Button URL

- **Type**: Text
- **Default**: Empty
- **Description**: The URL the CTA button links to
- **Example**: `/contact`, `https://yoursite.com/demo`

### Design & Branding Settings

#### Mobile Breakpoint (px)

- **Type**: Number
- **Default**: 1420
- **Range**: 320-2000
- **Description**: The viewport width (in pixels) at which the navigation switches to the mobile hamburger menu
- **Note**: At this width and below, the desktop menu is hidden and the hamburger icon appears
- **Recommended**: 
  - 1420px - Default, works well for most sites
  - 1280px - If you have fewer menu items
  - 1600px - If you have many menu items or longer text
- **How it works**: When a custom breakpoint is set (different from 1420px), the plugin generates inline CSS to override the default breakpoint

#### Navigation Mode

- **Type**: Radio Buttons
- **Default**: Light Mode
- **Options**: 
  - **Light Mode**: White text on dark overlay (default)
  - **Dark Mode**: Dark text on light overlay
- **Description**: Choose the color scheme for the main navigation
- **What Changes in Dark Mode**:
  - Main navigation text changes from white to dark
  - Brand logo changes color (via `currentColor` in SVG)
  - Navigation icons (carets, search) change to dark
  - Scrolled overlay becomes light semi-transparent
  - Border adjusts for visibility on light background
- **What Stays The Same**:
  - Universal nav (top bar) always remains black with white text - this is Patterson branding and never changes
  - CTA button keeps its primary color
  - Accent colors in logos are preserved
- **Note**: Can be overridden per-page using shortcode or PHP function parameters
- **When to Use Dark Mode**:
  - Light hero images/backgrounds
  - Pages with light color schemes
  - Sites with light brand aesthetics
  - Better readability against light backgrounds
- **Light Mode Best For**:
  - Dark hero images/backgrounds (default)
  - Dramatic, bold designs
  - High contrast needed

#### Brand Primary Color

- **Type**: Color Picker
- **Default**: #e51b24 (Patterson Red)
- **Description**: Your brand's primary color used for:
  - CTA button background
  - Focus states
  - Active navigation states
  - Hover effects
- **Format**: Hex color code (e.g., #06929f)
- **Visibility**: Custom mode only
- **Note**: Automatically set when using subsidiary presets

#### Adobe Typekit Code

- **Type**: Text Field
- **Default**: akz7boc (Patterson default)
- **Description**: Your Adobe Typekit project ID for custom fonts
- **Example**: `eyo6evt`, `bqc1fxq`, `afd5ryn`
- **Visibility**: Custom mode only
- **Note**: Automatically set when using subsidiary presets
- **Reference**: See [Brand Typography section in Design Tokens README](../design-tokens/README.md#brand-typography) for complete list of Typekit links and codes for each subsidiary
- **How to Find**: 
  1. Log in to Adobe Fonts (fonts.adobe.com)
  2. Go to your Web Projects
  3. Find your project and copy the Project ID
  4. The ID appears in the embed code: `https://use.typekit.net/[PROJECT_ID].css`

#### Load Design Tokens File

- **Type**: Checkbox
- **Default**: Unchecked
- **Description**: Loads an external design tokens CSS file

#### Design Tokens URL

- **Type**: Text
- **Default**: Empty
- **Description**: URL to your design tokens CSS file
- **Example**: `/wp-content/themes/yourtheme/assets/css/tokens.css`
- **Note**: Only loads if "Load Design Tokens File" is enabled

## Advanced Usage: Per-Page Overrides

You can override specific navigation settings on individual pages or templates using shortcode attributes or PHP function parameters. These overrides take priority over admin settings and only apply to the specific page where the navigation is rendered.

### Navigation Mode Override

Override the navigation color scheme (light/dark mode) for specific pages or templates.

**Using Shortcode:**

```php
[patterson_navigation mode="dark"]
[patterson_navigation mode="light"]
```

**Using PHP Function:**

```php
<?php patterson_nav(array('mode' => 'dark')); ?>
<?php patterson_nav(array('mode' => 'light')); ?>
```

**Priority Order:**
1. Shortcode/function `mode` parameter (highest)
2. Admin "Navigation Mode" setting
3. Default: Light mode

### Overlay Background Override

Override the main navigation overlay background color that appears when the page is scrolled.

**Default Overlay Color**: `oklch(0.15 0 0 / 0.63)` - Semi-transparent dark overlay

**Using Shortcode:**

```php
[patterson_navigation overlay_bg="oklch(0.1 0 0 / 0.8)"]
```

**Using PHP Function:**

```php
<?php patterson_nav(array('overlay_bg' => 'oklch(0.1 0 0 / 0.8)')); ?>
```

#### Supported Color Formats

You can use any valid CSS color format:

**OKLCH (Recommended)**

```php
overlay_bg="oklch(0.1 0 0 / 0.8)"
```

**Hex Colors**

```php
overlay_bg="#000000"
```

**RGB/RGBA**

```php
overlay_bg="rgba(0, 0, 0, 0.8)"
```

**HSL/HSLA**

```php
overlay_bg="hsla(0, 0%, 0%, 0.8)"
```

#### Use Cases

**Darker overlay for better contrast:**

```php
<?php patterson_nav(array('overlay_bg' => 'oklch(0.05 0 0 / 0.9)')); ?>
```

**Lighter overlay for subtle effect:**

```php
<?php patterson_nav(array('overlay_bg' => 'oklch(0.3 0 0 / 0.5)')); ?>
```

**Brand-colored overlay:**

```php
<?php patterson_nav(array('overlay_bg' => 'oklch(0.2 0.15 250 / 0.85)')); ?>
```

**No override (use default):**

```php
<?php patterson_nav(); ?>
[patterson_navigation]
```

#### When to Use

- **Different hero images**: Some hero backgrounds may need darker or lighter overlays for better readability
- **Brand requirements**: Match the overlay to your brand's specific style guidelines
- **Accessibility**: Adjust overlay opacity to ensure sufficient contrast for text elements
- **Template variations**: Different page templates can have different overlay styles

#### Important Notes

- The overlay background only appears when the page is scrolled (when `.scrolled` class is applied)
- The universal nav (top bar) is not affected by this setting
- Changes apply only to the specific page/template where the navigation is rendered
- The setting does not persist across pages - set it per template as needed

### Brand Logo Override

Override the brand logo for specific pages or templates by providing a custom SVG file path or URL.

**What it does**: Replaces the admin-configured brand logo with a custom SVG file

**When to use**:
- Landing pages with special branding
- Campaign-specific pages
- Microsites within your main site
- Event-specific pages
- Seasonal variations of your logo

**Using Shortcode:**

```php
[patterson_navigation brand_logo_url="/wp-content/uploads/special-logo.svg"]
```

**Using PHP Function:**

**Absolute URL (full site URL):**

```php
<?php patterson_nav(array(
    'brand_logo_url' => 'https://yoursite.com/wp-content/uploads/special-logo.svg'
)); ?>
```

**Relative path from WordPress root:**

```php
<?php patterson_nav(array(
    'brand_logo_url' => '/wp-content/uploads/special-logo.svg'
)); ?>
```

**Theme directory path:**

```php
<?php patterson_nav(array(
    'brand_logo_url' => get_template_directory_uri() . '/assets/logos/campaign-logo.svg'
)); ?>
```

**Plugin path (if logo is in your plugin):**

```php
<?php patterson_nav(array(
    'brand_logo_url' => plugins_url('assets/logo.svg', __FILE__)
)); ?>
```

#### Supported Path Formats

The plugin intelligently handles multiple path formats:

1. **Full URLs** - `https://yoursite.com/wp-content/uploads/logo.svg`
2. **Site-relative paths** - `/wp-content/uploads/logo.svg`
3. **WordPress function paths** - `get_template_directory_uri() . '/assets/logo.svg'`
4. **Plugin-relative URLs** - Plugin URLs are automatically converted to file paths

#### Important Notes

- **SVG format required**: Only SVG files are supported for proper inline rendering
- **File must exist**: The file path must point to an actual file on the server
- **Dark mode compatibility**: Ensure your custom logo uses `currentColor` for main elements if you use dark mode
- **Priority**: The shortcode/function parameter takes highest priority over admin settings
- **Per-page only**: The override applies only to the specific page/template where it's used
- **Does not require admin logo**: You can use this parameter even if brand logo is disabled in admin settings

### Combining Multiple Parameters

You can combine multiple override parameters in a single navigation call:

**Shortcode (limited combinations):**

```php
[patterson_navigation mode="dark" overlay_bg="oklch(0.95 0 0 / 0.9)" brand_logo_url="/wp-content/uploads/dark-logo.svg"]
```

**PHP Function (recommended for multiple parameters):**

```php
<?php patterson_nav(array(
    'mode' => 'dark',
    'overlay_bg' => 'oklch(0.95 0 0 / 0.9)',
    'brand_logo_url' => get_template_directory_uri() . '/assets/logos/dark-logo.svg'
)); ?>
```

## Menu Configuration

### Setting Up Dropdown Menus with Descriptions and Featured Content

The navigation supports enhanced dropdown menus with item descriptions and featured content sections.

### Enable Custom Fields in Menu Editor

**Important**: Before you can see the custom fields, you must enable them:

1. Go to **Appearance** → **Menus**
2. Click **Screen Options** at the top right of the page
3. Check the **"Description"** checkbox
4. The Patterson custom fields will now appear when you expand menu items

**Note about newly added menu items**: When you first add menu items to your menu, WordPress doesn't properly recognize their hierarchy until after the first save. This means:
- Newly added items will initially show the "⭐ Enable Featured Content for Mega Menu" checkbox (regardless of whether they're top-level or child items)
- After saving the menu with your desired structure (dragging items to create parent-child relationships), the correct fields will appear:
  - **Top-level items** will show "⭐ Enable Featured Content for Mega Menu"
  - **Child items** (indented) will show "📝 Patterson Nav: Menu Item Description"

This is normal WordPress menu editor behavior, not a bug.

### Menu Item Descriptions (Child Items Only)

**What**: Short descriptions that appear below menu item titles in dropdown menus

**Where**: Only available on **child items** (sub-menu items), not on top-level items

**How to add**:
1. In the menu editor, expand a **child menu item** (indented item)
2. Scroll down to find the blue box: **"📝 Patterson Nav: Menu Item Description"**
3. Enter a short description (1-2 sentences)
4. Save the menu

**Example:**

```
Menu Item: Dashboard
Description: Access real-time insights and analytics
```

### Featured Content (Top-Level Items Only)

**What**: A featured content section that appears on the right side of mega-menu dropdowns

**Where**: Available on all **top-level menu items** (but only displays in dropdowns that have child items)

**How to add**:
1. In the menu editor, expand a **top-level menu item** that has children
2. Scroll down to find the yellow box: **"⭐ Enable Featured Content for Mega Menu"**
3. Check the checkbox to enable featured content
4. Fill in the featured content fields:
   - **Featured Image URL**: Click "Select Image" to choose an image
   - **Featured Title**: Main heading for the featured content
   - **Featured Description**: Text description
   - **Featured Link Text**: Call-to-action text (e.g., "Learn More")
   - **Featured Link URL**: Where the link goes
5. Save the menu

**Best Practices**:
- Use high-quality images (recommended: 455px wide × 255px tall)
- Keep titles short and compelling
- Descriptions should be 2-3 sentences max
- Use descriptive link text (not just "More" or "Learn More")

**Example:**

```
Featured Title: New Product Launch
Featured Description: Discover our latest innovation in drilling technology with enhanced performance and reliability.
Featured Link Text: View Product Details
Featured Link URL: /products/new-drill-system
```

## Best Practices

### Subsidiary Configuration

- **Use presets when available**: Ulterra, NexTier, and Superior QC have optimized brand configurations
- **Consistent branding**: Presets ensure consistent colors, fonts, and logos across Patterson subsidiaries
- **Custom only when needed**: Use Custom mode only for new subsidiaries or special cases
- **Test fonts**: After selecting a subsidiary, verify that the Adobe Typekit fonts load correctly
- **Dark mode ready**: All preset subsidiary logos are pre-configured with `currentColor` for automatic dark mode support

### Navigation Mode & Dark Mode

- **Test both modes**: Always test your navigation in both Light and Dark modes
- **Check logo visibility**: Ensure your logo is visible in both modes (use `currentColor` for main color)
- **Verify contrast**: Ensure text remains readable (minimum 4.5:1 contrast ratio for WCAG AA)
- **Hero backgrounds matter**: Choose Light mode for dark heroes, Dark mode for light heroes
- **Consider brand guidelines**: Some brands may have specific requirements for light vs. dark treatments
- **CTA button**: Ensure CTA button has sufficient contrast in both modes
- **Test scrolled state**: The overlay background changes when scrolled - test readability in both states

### Menu Structure

- **Universal Nav**: 4-6 items (About, Brands, Investors, Careers, etc.)
- **Main Nav**: 5-7 top-level items with dropdowns
- **Dropdown Items**: 4-10 items per dropdown (automatically split into 2 columns)

### Brand Logo

- Use SVG format for best quality
- Optimize file size (aim for <50KB)
- Ensure logo has sufficient contrast on dark overlay
- Test logo at different viewport sizes

### Mobile Breakpoint

- Test with your actual menu items
- Consider logo width + all menu items + search + CTA
- If items wrap or overlap on desktop, increase breakpoint
- Test on real devices, not just browser resize

### Colors

- Ensure sufficient contrast with white text (WCAG AA: 4.5:1 minimum)
- Test CTA button visibility against various hero backgrounds

### Search Integration

- Test search functionality after configuration
- Ensure search modal/overlay has appropriate z-index
- Consider using a dedicated search plugin for better UX

### Overlay Background Customization

- **Test with actual hero images**: View the scrolled nav against your real page backgrounds
- **Maintain contrast**: Ensure text remains readable (white text needs dark overlay, minimum 4.5:1 contrast)
- **Consistency across templates**: Use similar overlay settings across your site unless there's a specific reason to vary
- **Use OKLCH when possible**: Provides better color control and perceptual uniformity
- **Don't overdo transparency**: Too transparent (<0.6 opacity) may reduce text legibility
- **Document custom values**: If using custom overlay colors, document them in your theme for future reference

### Custom Logo SVG Preparation

- **Use `currentColor` for main elements**: Replace white/main colors with `currentColor` to support both light and dark modes
- **Preserve accent colors**: Keep brand colors and accents as defined hex/OKLCH values
- **Include viewBox**: Always include a `viewBox` attribute for proper scaling
- **Optimize SVG**: Remove unnecessary elements, comments, and metadata before pasting
- **Test in both modes**: Preview your logo in both Light and Dark navigation modes
- **Fallback consideration**: If your logo won't work in dark mode, consider only using Light mode
- **Multi-color logos**: For complex multi-color logos, use `currentColor` strategically on main shapes only
