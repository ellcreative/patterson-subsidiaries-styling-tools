# Navigation Configuration

This directory contains the **single source of truth** for navigation configuration used by both the WordPress plugin and prototypes.

## Files

### `navigation-config.json`

Single source of truth for navigation and company information.

**Structure:**
- `universal_nav` - Patterson universal navigation bar configuration
  - `logo` - Logo settings (URL, aria-label, alt text, dimensions)
  - `links` - Array of navigation links (title, URL)
- `company_info` - Company information for copyright, etc.
- `social_media` - Social media profile URLs
- `contact` - Contact information

**Usage:**

**In Prototypes (JavaScript):**
The prototype automatically loads this config file using `prototypes/js/load-config.js`:
```javascript
fetch('../patterson-navigation/config/navigation-config.json')
  .then(response => response.json())
  .then(config => {
    // Populates desktop and mobile universal nav automatically
  });
```

**In WordPress Plugin (PHP):**
The plugin automatically loads this config file in `class-renderer.php`:
```php
$config = json_decode(
  file_get_contents(PATTERSON_NAV_PLUGIN_DIR . 'config/navigation-config.json'),
  true
);
$universal_links = $config['universal_nav']['links'];
```

## Updating Configuration

To update navigation links or company information:

1. Edit this `navigation-config.json` file
2. Changes will automatically be reflected in **both** the WordPress plugin and prototypes
3. No copying or syncing required!
