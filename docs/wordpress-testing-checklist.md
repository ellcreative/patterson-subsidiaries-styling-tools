# WordPress Plugin Testing Checklist

**Plugin:** Patterson Subsidiary Navigation  
**Version:** 1.0.0  
**Date:** November 7, 2025

## Pre-Testing Requirements

### WordPress Environment
- [ ] WordPress 6.0 or higher
- [ ] PHP 7.4 or higher
- [ ] Design tokens CSS file accessible (if using tokens)
- [ ] Two WordPress menus created:
  - Universal Navigation menu (top bar)
  - Main Navigation menu (with sub-items)

### Installation Steps
1. [ ] Upload plugin to `/wp-content/plugins/patterson-navigation/`
2. [ ] Activate plugin in WordPress admin
3. [ ] Verify no PHP errors in debug.log
4. [ ] Navigate to Settings > Patterson Navigation

## Initial Configuration

### Admin Settings
- [ ] Universal Navigation section loads correctly
- [ ] Main Navigation section loads correctly
- [ ] Brand Logo upload/configuration works
- [ ] Search settings save correctly
- [ ] CTA button settings save correctly
- [ ] Brand color picker works
- [ ] Mobile breakpoint field accepts values
- [ ] Design tokens URL field accepts values
- [ ] All settings persist after save

### Menu Setup
1. [ ] Create/assign Universal Navigation menu
   - [ ] Add 5-6 top-level items
   - [ ] Test menu selection in plugin settings
   
2. [ ] Create/assign Main Navigation menu
   - [ ] Add parent items (5 recommended)
   - [ ] Add child items under each parent (3-5 per parent)
   - [ ] Add menu item descriptions (optional)
   - [ ] Configure featured content via menu meta boxes (if enabled)

## Frontend Display Testing

### Shortcode Placement
- [ ] Add `[patterson_navigation]` to header template
- [ ] OR use `<?php patterson_nav(); ?>` in theme files
- [ ] OR use block editor shortcode block
- [ ] Navigation renders without errors
- [ ] No console errors in browser DevTools

### Visual Testing (Desktop > 1420px)

#### Universal Navigation
- [ ] Displays across full width
- [ ] Logo/brand name visible
- [ ] Menu items display horizontally
- [ ] Links are clickable and work
- [ ] Hover states work
- [ ] Focus states visible (keyboard)

#### Main Navigation
- [ ] Brand logo displays (if enabled)
- [ ] Main menu items display horizontally
- [ ] CTA button displays (if enabled)
- [ ] Search button/field displays (if enabled)
- [ ] Down arrows appear on parent items

#### Dropdown Menus
- [ ] Click parent item opens dropdown
- [ ] Dropdown displays full width
- [ ] Menu items show in columns (2 column layout)
- [ ] Item titles are bold
- [ ] Item descriptions show (if added)
- [ ] Featured content displays (if configured)
- [ ] Featured image loads
- [ ] Featured link works
- [ ] Click outside closes dropdown
- [ ] Click another parent closes current dropdown
- [ ] Escape key closes dropdown

### Visual Testing (Mobile < 1420px)

#### Layout Changes
- [ ] Universal navigation menu hidden
- [ ] Main navigation menu hidden
- [ ] Hamburger icon displays
- [ ] Brand logo displays (if enabled)
- [ ] CTA and search hidden in header

#### Mobile Menu
- [ ] Click hamburger opens mobile menu
- [ ] Menu slides in from right
- [ ] Dark backdrop appears behind menu
- [ ] Close button (X) appears in menu header
- [ ] Search button appears in mobile menu (if enabled)
- [ ] Main menu items display vertically
- [ ] Parent items show down arrows
- [ ] Click parent expands sub-items
- [ ] Sub-items display with indentation
- [ ] CTA button displays at bottom
- [ ] Universal nav items display at bottom
- [ ] Scroll works if content overflows

#### Mobile Menu Closing
- [ ] Click X button closes menu
- [ ] Click backdrop closes menu
- [ ] Escape key closes menu
- [ ] Body scroll locks when menu open
- [ ] Body scroll unlocks when menu closed

### Responsive Breakpoint
- [ ] Test default breakpoint (1420px)
- [ ] Change breakpoint in settings
- [ ] Verify menu switches at new breakpoint
- [ ] Test smooth transition between layouts

## Accessibility Testing (WCAG 2.2 AA)

### Skip Link
- [ ] Press Tab on page load
- [ ] Skip link appears at top of page
- [ ] Link text: "Skip to main content"
- [ ] Click/Enter jumps to main content
- [ ] Verify `id="main-content"` exists on page

### Keyboard Navigation - Desktop
- [ ] Tab moves through all interactive elements
- [ ] Focus visible on all elements
- [ ] Tab order is logical (left to right, top to bottom)
- [ ] Shift+Tab moves backwards correctly

#### Main Menu
- [ ] Tab to menu items
- [ ] Left/Right arrows move between menu items
- [ ] Enter/Space opens dropdown
- [ ] Down arrow opens dropdown
- [ ] Focus moves to first dropdown item when opened
- [ ] Up/Down arrows navigate dropdown items
- [ ] Tab continues through dropdown items
- [ ] Escape closes dropdown and returns focus to trigger

#### Search and CTA
- [ ] Tab to search button
- [ ] Enter/Space activates search
- [ ] Tab to CTA button
- [ ] Enter activates CTA link

### Keyboard Navigation - Mobile
- [ ] Tab to hamburger button
- [ ] Enter/Space opens mobile menu
- [ ] Focus trapped within mobile menu when open
- [ ] Tab cycles through menu items
- [ ] Enter/Space on parent item expands sub-items
- [ ] Tab to close button
- [ ] Enter/Space closes menu
- [ ] Escape closes menu and returns focus to hamburger
- [ ] Focus returns to hamburger after closing

### Screen Reader Testing

#### NVDA (Windows) / JAWS
- [ ] Navigation regions announced ("Utility navigation", "Main navigation")
- [ ] Menu buttons announced with expanded/collapsed state
- [ ] Dropdown state changes announced
- [ ] All links announced with descriptive text
- [ ] No "More" or generic link text
- [ ] Images marked as decorative not announced
- [ ] Skip link announced and functional

#### VoiceOver (macOS)
- [ ] Navigate with VO+arrow keys
- [ ] All elements have accessible names
- [ ] Button roles announced correctly
- [ ] Link roles announced correctly
- [ ] Navigation landmarks recognized
- [ ] Can navigate by landmarks (VO+U, then navigate)

### Semantic HTML Validation
- [ ] Run HTML validator (validator.w3.org)
- [ ] No heading elements (`<h2>`, `<h3>`) in navigation
- [ ] Proper ARIA attributes:
  - [ ] `aria-label` on nav elements
  - [ ] `aria-expanded` on dropdown buttons
  - [ ] `aria-controls` connecting buttons to dropdowns
  - [ ] `aria-hidden="true"` on decorative icons
  - [ ] `aria-label` on icon-only buttons
- [ ] No redundant roles on semantic elements

### Focus Indicators
- [ ] All interactive elements have visible focus
- [ ] Focus ring is 2px solid brand color
- [ ] Focus offset provides spacing
- [ ] Focus visible on both light and dark backgrounds
- [ ] No focus on mouse click (focus-visible working)

## Browser Compatibility

### Desktop Browsers
- [ ] Chrome/Edge (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)
- [ ] Check for console errors in each

### Mobile Browsers
- [ ] Safari iOS (latest)
- [ ] Chrome Android (latest)
- [ ] Touch targets minimum 44x44px
- [ ] No zoom disabled issues

## Performance Testing

### Loading
- [ ] CSS loads before content displays (no FOUC)
- [ ] JavaScript loads without blocking render
- [ ] No layout shifts when JS initializes
- [ ] Featured images use lazy loading

### Network Throttling
- [ ] Test on Slow 3G connection
- [ ] Navigation remains functional
- [ ] CSS loads first
- [ ] Images load progressively

### JavaScript Disabled
- [ ] Navigation HTML renders
- [ ] Links remain clickable
- [ ] Basic functionality maintained
- [ ] Consider fallback for dropdowns

## Integration Testing

### Theme Compatibility
- [ ] Works with block themes
- [ ] Works with classic themes
- [ ] No style conflicts with theme CSS
- [ ] Z-index layering works correctly
- [ ] Backdrop blur fallback works

### Plugin Compatibility
- [ ] Test with popular caching plugins
- [ ] Test with SEO plugins (Yoast, Rank Math)
- [ ] Test with page builders (Elementor, Divi)
- [ ] No JavaScript conflicts

### Custom Scenarios
- [ ] Test with design tokens enabled
- [ ] Test with design tokens disabled
- [ ] Test without brand logo
- [ ] Test without search
- [ ] Test without CTA
- [ ] Test with only universal nav
- [ ] Test with only main nav

## Content Testing

### Menu Descriptions
- [ ] Add descriptions to menu items
- [ ] Verify descriptions display in dropdowns
- [ ] Test with long descriptions
- [ ] Test with HTML in descriptions (should be escaped)

### Featured Content
- [ ] Configure featured content via meta boxes
- [ ] Upload featured image
- [ ] Add featured title and description
- [ ] Add featured link with custom text
- [ ] Verify featured content displays
- [ ] Test with missing featured image
- [ ] Test with missing featured link

### Long Text Handling
- [ ] Very long menu item names
- [ ] Long menu descriptions
- [ ] Many menu items (10+ top level)
- [ ] Many sub-items (15+ under one parent)
- [ ] Featured content with long text

## Error Handling

### Missing Configuration
- [ ] No menus assigned (graceful degradation)
- [ ] Empty menus (no errors)
- [ ] Menu deleted after assignment
- [ ] Invalid menu ID in settings

### Edge Cases
- [ ] Single menu item
- [ ] Menu with only children (no parents)
- [ ] Nested menu items beyond 2 levels
- [ ] Special characters in menu names
- [ ] Emoji in menu names

## Security Testing

### Output Escaping
- [ ] Review all `echo` statements use proper escaping
- [ ] `esc_html()` for text content
- [ ] `esc_attr()` for attributes
- [ ] `esc_url()` for URLs
- [ ] `wp_kses_post()` for HTML content

### Input Validation
- [ ] Settings validated on save
- [ ] Color field validates hex colors
- [ ] URL fields validate URLs
- [ ] Number fields validate integers
- [ ] Menu IDs validated as integers

### Nonce Verification
- [ ] Admin forms use nonces
- [ ] Nonces verified before processing
- [ ] Capability checks in place

## Documentation Verification

### Code Comments
- [ ] PHP files properly documented
- [ ] JavaScript functions documented
- [ ] CSS sections clearly labeled

### User Documentation
- [ ] Installation instructions clear
- [ ] Configuration steps documented
- [ ] Shortcode usage explained
- [ ] Helper function documented
- [ ] Troubleshooting guide available

## Pre-Launch Checklist

### Code Quality
- [ ] No PHP errors or warnings
- [ ] No JavaScript console errors
- [ ] No CSS validation errors (where reasonable)
- [ ] Code follows WordPress coding standards
- [ ] All TODOs resolved or documented

### Accessibility Audit
- [ ] Run axe DevTools (0 errors)
- [ ] Run WAVE (0 errors)
- [ ] Lighthouse Accessibility score: 100
- [ ] Manual keyboard test passed
- [ ] Manual screen reader test passed

### Performance Audit
- [ ] Lighthouse Performance score: 90+
- [ ] CSS file minified for production
- [ ] JavaScript file minified for production
- [ ] No unused code

### Final Testing
- [ ] Fresh WordPress install test
- [ ] Test with default WordPress theme
- [ ] Test activation/deactivation
- [ ] Test uninstall (cleanup)
- [ ] Test update process (if applicable)

## Known Issues / Limitations

Document any known issues or limitations discovered during testing:

1. [Add issues here]

## Testing Sign-off

- [ ] All critical tests passed
- [ ] No blocking issues found
- [ ] Accessibility confirmed WCAG 2.2 AA
- [ ] Ready for production deployment

**Tested by:** _________________  
**Date:** _________________  
**WordPress Version:** _________________  
**PHP Version:** _________________  
**Theme Used:** _________________

---

## Quick Test Commands

### WordPress Debug Mode
Add to wp-config.php:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

### Browser DevTools Accessibility Audit
```javascript
// Console command to check for common issues
document.querySelectorAll('button:not([aria-label]):not([aria-labelledby])').length
document.querySelectorAll('img:not([alt])').length
document.querySelectorAll('a[href="#"]:not([aria-label])').length
```

### Test Focus Order
```javascript
// Log focus order
document.addEventListener('focus', (e) => {
  console.log('Focus:', e.target);
}, true);
```

## Support Resources

- WCAG 2.2 Guidelines: https://www.w3.org/WAI/WCAG22/Understanding/
- WordPress Coding Standards: https://developer.wordpress.org/coding-standards/
- Accessibility Audit Report: `/docs/accessibility-audit-report.md`

