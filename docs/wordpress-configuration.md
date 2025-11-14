# Configuration Reference

Complete reference for all Patterson Navigation plugin settings.

## Additional Documentation

- [WordPress Theme Compatibility](wordpress-theme-compatibility.md) - How to prevent theme focus style conflicts

## Admin Settings

Navigate to **WordPress Admin** → **Patterson Nav**

## General Settings

### Enable Universal Nav
- **Type**: Checkbox
- **Default**: Unchecked
- **Description**: Enables the Patterson Companies universal navigation bar that appears above the main navigation

## Universal Navigation Settings

### Universal Nav Menu
- **Type**: Menu Selector
- **Default**: None
- **Description**: Select which WordPress menu to display in the universal navigation bar
- **Note**: Create a menu in Appearance → Menus first

## Main Navigation Settings

### Main Nav Menu
- **Type**: Menu Selector
- **Default**: None
- **Description**: Select which WordPress menu to use for the main navigation
- **Note**: Supports dropdowns and featured content

### Brand Logo Settings

#### Enable Brand Logo
- **Type**: Checkbox
- **Default**: Unchecked
- **Description**: Shows your brand logo before the navigation menu items

#### Brand Logo URL
- **Type**: Media Upload
- **Default**: Empty
- **Description**: Upload or select your brand logo from the media library
- **Recommended Format**: SVG or PNG with transparent background
- **Recommended Size**: 198px × 24px
- **Note**: Click "Upload Logo" to open the media library

#### Logo Width (px)
- **Type**: Number
- **Default**: 198
- **Range**: 1-500
- **Description**: The width of your logo in pixels

#### Logo Height (px)
- **Type**: Number
- **Default**: 24
- **Range**: 1-500
- **Description**: The height of your logo in pixels

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

## Design & Branding Settings

### Mobile Breakpoint (px)
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

### Brand Primary Color
- **Type**: Color Picker
- **Default**: #e51b24 (Patterson Red)
- **Description**: Your brand's primary color used for:
  - CTA button background
  - Focus states
  - Active navigation states
  - Hover effects
- **Format**: Hex color code (e.g., #06929f)

### Load Design Tokens File
- **Type**: Checkbox
- **Default**: Unchecked
- **Description**: Loads an external design tokens CSS file

### Design Tokens URL
- **Type**: Text
- **Default**: Empty
- **Description**: URL to your design tokens CSS file
- **Example**: `/wp-content/themes/yourtheme/assets/css/tokens.css`
- **Note**: Only loads if "Load Design Tokens File" is enabled

## Menu Configuration

### Setting Up Dropdown Menus with Descriptions and Featured Content

The navigation supports enhanced dropdown menus with item descriptions and featured content sections.

#### Enable Custom Fields in Menu Editor

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

#### Menu Item Descriptions (Child Items Only)

**What**: Short descriptions that appear below menu item titles in dropdown menus

**Where**: Only available on **child items** (sub-menu items), not on top-level items

**How to add**:
1. In the menu editor, expand a **child menu item** (indented item)
2. Scroll down to find the blue box: **"📝 Patterson Nav: Menu Item Description"**
3. Enter a short description (1-2 sentences)
4. Save the menu

**Example**:
```
Menu Item: Dashboard
Description: Access real-time insights and analytics
```

#### Featured Content (Top-Level Items Only)

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

**Example**:
```
Featured Title: New Product Launch
Featured Description: Discover our latest innovation in drilling technology with enhanced performance and reliability.
Featured Link Text: View Product Details
Featured Link URL: /products/new-drill-system
```

## Best Practices

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

