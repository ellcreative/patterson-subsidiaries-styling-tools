/**
 * Universal & Main Navigation
 * JavaScript functionality for desktop dropdowns, mobile menu, and accessibility
 */

(function() {
  'use strict';

  // ============================================
  // STATE
  // ============================================
  
  let currentOpenDropdown = null;
  let focusTrapElements = [];
  
  // ============================================
  // DESKTOP DROPDOWN FUNCTIONALITY
  // ============================================
  
  function initDesktopDropdowns() {
    const dropdownTriggers = document.querySelectorAll('.main-nav__link[aria-controls]');
    
    dropdownTriggers.forEach(trigger => {
      // Click handler
      trigger.addEventListener('click', handleDropdownClick);
      
      // Keyboard handler
      trigger.addEventListener('keydown', handleDropdownKeydown);
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', handleOutsideClick);
    
    // Close dropdown on Escape key
    document.addEventListener('keydown', handleEscapeKey);
  }
  
  function handleDropdownClick(event) {
    const trigger = event.currentTarget;
    const dropdownId = trigger.getAttribute('aria-controls');
    const dropdown = document.getElementById(dropdownId);
    
    if (!dropdown) return;
    
    // Always prevent default when trigger has a dropdown
    // The user can access the link via the dropdown header if needed
    event.preventDefault();
    
    const isExpanded = trigger.getAttribute('aria-expanded') === 'true';
    
    // Close currently open dropdown if different
    if (currentOpenDropdown && currentOpenDropdown !== dropdown) {
      closeDropdown(currentOpenDropdown);
    }
    
    if (isExpanded) {
      closeDropdown(dropdown);
    } else {
      openDropdown(dropdown, trigger);
    }
  }
  
  function handleDropdownKeydown(event) {
    if (event.key === 'Enter' || event.key === ' ') {
      event.preventDefault();
      handleDropdownClick(event);
    }
  }
  
  function openDropdown(dropdown, trigger) {
    dropdown.hidden = false;
    trigger.setAttribute('aria-expanded', 'true');
    currentOpenDropdown = dropdown;
    
    // Focus first focusable element in dropdown after animation
    setTimeout(() => {
      const firstFocusable = dropdown.querySelector('a, button');
      if (firstFocusable) {
        firstFocusable.focus();
      }
    }, 100);
  }
  
  function closeDropdown(dropdown) {
    if (!dropdown) return;
    
    const trigger = document.querySelector(`[aria-controls="${dropdown.id}"]`);
    dropdown.hidden = true;
    
    if (trigger) {
      trigger.setAttribute('aria-expanded', 'false');
    }
    
    if (currentOpenDropdown === dropdown) {
      currentOpenDropdown = null;
    }
  }
  
  function handleOutsideClick(event) {
    if (!currentOpenDropdown) return;
    
    const trigger = document.querySelector(`[aria-controls="${currentOpenDropdown.id}"]`);
    
    // Check if click is outside both trigger and dropdown
    if (
      !currentOpenDropdown.contains(event.target) &&
      !trigger.contains(event.target)
    ) {
      closeDropdown(currentOpenDropdown);
    }
  }
  
  function handleEscapeKey(event) {
    if (event.key === 'Escape' && currentOpenDropdown) {
      const trigger = document.querySelector(`[aria-controls="${currentOpenDropdown.id}"]`);
      closeDropdown(currentOpenDropdown);
      
      // Return focus to trigger
      if (trigger) {
        trigger.focus();
      }
    }
  }
  
  // ============================================
  // MOBILE MENU FUNCTIONALITY
  // ============================================
  
  function initMobileMenu() {
    const mobileToggle = document.querySelector('.main-nav__mobile-toggle');
    const mobileClose = document.querySelector('.main-nav__mobile-close');
    const mobileMenu = document.getElementById('mobile-menu');
    const backdrop = document.querySelector('.main-nav__backdrop');
    
    if (!mobileToggle || !mobileMenu) return;
    
    // Toggle mobile menu
    mobileToggle.addEventListener('click', () => {
      toggleMobileMenu();
    });
    
    // Close mobile menu
    if (mobileClose) {
      mobileClose.addEventListener('click', () => {
        closeMobileMenu();
      });
    }
    
    // Close on backdrop click
    if (backdrop) {
      backdrop.addEventListener('click', () => {
        closeMobileMenu();
      });
    }
    
    // Close on Escape key
    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape' && mobileMenu.hidden === false) {
        closeMobileMenu();
        mobileToggle.focus();
      }
    });
    
    // Mobile accordion items
    initMobileAccordion();
  }
  
  function toggleMobileMenu() {
    const mobileToggle = document.querySelector('.main-nav__mobile-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const backdrop = document.querySelector('.main-nav__backdrop');
    const isOpen = mobileToggle.getAttribute('aria-expanded') === 'true';
    
    if (isOpen) {
      closeMobileMenu();
    } else {
      openMobileMenu();
    }
  }
  
  function openMobileMenu() {
    const mobileToggle = document.querySelector('.main-nav__mobile-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const backdrop = document.querySelector('.main-nav__backdrop');
    
    mobileMenu.hidden = false;
    backdrop.hidden = false;
    mobileToggle.setAttribute('aria-expanded', 'true');
    document.body.classList.add('mobile-menu-open');
    
    // Set up focus trap
    setupFocusTrap(mobileMenu);
    
    // Focus first focusable element
    setTimeout(() => {
      const firstFocusable = mobileMenu.querySelector('button, a');
      if (firstFocusable) {
        firstFocusable.focus();
      }
    }, 50);
  }
  
  function closeMobileMenu() {
    const mobileToggle = document.querySelector('.main-nav__mobile-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const backdrop = document.querySelector('.main-nav__backdrop');
    
    mobileMenu.hidden = true;
    backdrop.hidden = true;
    mobileToggle.setAttribute('aria-expanded', 'false');
    document.body.classList.remove('mobile-menu-open');
    
    // Remove focus trap
    removeFocusTrap();
    
    // Close all mobile accordions (including nested ones)
    const openAccordions = mobileMenu.querySelectorAll('.main-nav__mobile-link[aria-expanded="true"], .main-nav__mobile-link--nested[aria-expanded="true"]');
    openAccordions.forEach(accordion => {
      const dropdownId = accordion.getAttribute('aria-controls');
      const dropdown = document.getElementById(dropdownId);
      if (dropdown) {
        dropdown.hidden = true;
        accordion.setAttribute('aria-expanded', 'false');
        
        // Also close any nested accordions within this dropdown
        const nestedAccordions = dropdown.querySelectorAll('.main-nav__mobile-link--nested[aria-expanded="true"]');
        nestedAccordions.forEach(nestedAccordion => {
          const nestedDropdownId = nestedAccordion.getAttribute('aria-controls');
          const nestedDropdown = document.getElementById(nestedDropdownId);
          if (nestedDropdown) {
            nestedDropdown.hidden = true;
            nestedAccordion.setAttribute('aria-expanded', 'false');
          }
        });
      }
    });
  }
  
  function initMobileAccordion() {
    // Handle both top-level and nested accordion triggers
    const accordionTriggers = document.querySelectorAll(
      '.main-nav__mobile-link[aria-controls], .main-nav__mobile-link--nested[aria-controls]'
    );
    
    accordionTriggers.forEach(trigger => {
      trigger.addEventListener('click', (event) => {
        event.preventDefault(); // Prevent any default action
        
        const dropdownId = trigger.getAttribute('aria-controls');
        const dropdown = document.getElementById(dropdownId);
        
        if (!dropdown) return;
        
        const isExpanded = trigger.getAttribute('aria-expanded') === 'true';
        
        // Close sibling accordions at the same level (optional - for cleaner UX)
        const parentContainer = trigger.closest('.main-nav__mobile-items, .main-nav__mobile-dropdown');
        if (parentContainer) {
          const siblingTriggers = parentContainer.querySelectorAll(':scope > li > [aria-controls], :scope > [aria-controls]');
          siblingTriggers.forEach(siblingTrigger => {
            if (siblingTrigger !== trigger && siblingTrigger.getAttribute('aria-expanded') === 'true') {
              const siblingDropdownId = siblingTrigger.getAttribute('aria-controls');
              const siblingDropdown = document.getElementById(siblingDropdownId);
              if (siblingDropdown) {
                siblingDropdown.hidden = true;
                siblingTrigger.setAttribute('aria-expanded', 'false');
              }
            }
          });
        }
        
        // Toggle current accordion
        if (isExpanded) {
          dropdown.hidden = true;
          trigger.setAttribute('aria-expanded', 'false');
          
          // Close any nested accordions when parent closes
          const nestedTriggers = dropdown.querySelectorAll('[aria-controls][aria-expanded="true"]');
          nestedTriggers.forEach(nestedTrigger => {
            const nestedDropdownId = nestedTrigger.getAttribute('aria-controls');
            const nestedDropdown = document.getElementById(nestedDropdownId);
            if (nestedDropdown) {
              nestedDropdown.hidden = true;
              nestedTrigger.setAttribute('aria-expanded', 'false');
            }
          });
        } else {
          dropdown.hidden = false;
          trigger.setAttribute('aria-expanded', 'true');
        }
      });
    });
  }
  
  // ============================================
  // FOCUS TRAP (for mobile menu)
  // ============================================
  
  function setupFocusTrap(container) {
    focusTrapElements = container.querySelectorAll(
      'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'
    );
    
    if (focusTrapElements.length === 0) return;
    
    const firstElement = focusTrapElements[0];
    const lastElement = focusTrapElements[focusTrapElements.length - 1];
    
    container.addEventListener('keydown', handleFocusTrap);
    
    function handleFocusTrap(event) {
      if (event.key !== 'Tab') return;
      
      if (event.shiftKey) {
        // Shift + Tab
        if (document.activeElement === firstElement) {
          event.preventDefault();
          lastElement.focus();
        }
      } else {
        // Tab
        if (document.activeElement === lastElement) {
          event.preventDefault();
          firstElement.focus();
        }
      }
    }
    
    // Store handler for removal
    container._focusTrapHandler = handleFocusTrap;
  }
  
  function removeFocusTrap() {
    const mobileMenu = document.getElementById('mobile-menu');
    if (mobileMenu && mobileMenu._focusTrapHandler) {
      mobileMenu.removeEventListener('keydown', mobileMenu._focusTrapHandler);
      delete mobileMenu._focusTrapHandler;
    }
    focusTrapElements = [];
  }
  
  // ============================================
  // KEYBOARD NAVIGATION ENHANCEMENTS
  // ============================================
  
  function initKeyboardNavigation() {
    // Arrow key navigation for main nav items
    const mainNavItems = document.querySelectorAll('.main-nav__link');
    
    mainNavItems.forEach((item, index) => {
      item.addEventListener('keydown', (event) => {
        let nextItem = null;
        
        switch(event.key) {
          case 'ArrowRight':
            event.preventDefault();
            nextItem = mainNavItems[index + 1] || mainNavItems[0];
            nextItem.focus();
            break;
          case 'ArrowLeft':
            event.preventDefault();
            nextItem = mainNavItems[index - 1] || mainNavItems[mainNavItems.length - 1];
            nextItem.focus();
            break;
          case 'ArrowDown':
            // Open dropdown if closed
            if (item.getAttribute('aria-expanded') === 'false') {
              event.preventDefault();
              item.click();
            }
            break;
        }
      });
    });
    
    // Arrow key navigation within dropdowns
    document.querySelectorAll('.main-nav__dropdown').forEach(dropdown => {
      const dropdownLinks = dropdown.querySelectorAll('a');
      
      dropdownLinks.forEach((link, index) => {
        link.addEventListener('keydown', (event) => {
          let nextLink = null;
          
          switch(event.key) {
            case 'ArrowDown':
              event.preventDefault();
              nextLink = dropdownLinks[index + 1] || dropdownLinks[0];
              nextLink.focus();
              break;
            case 'ArrowUp':
              event.preventDefault();
              nextLink = dropdownLinks[index - 1] || dropdownLinks[dropdownLinks.length - 1];
              nextLink.focus();
              break;
          }
        });
      });
    });
  }
  
  // ============================================
  // RESPONSIVE BEHAVIOR
  // ============================================
  
  function handleResize() {
    // Read breakpoint from data attribute (set by PHP based on plugin settings)
    const siteNav = document.getElementById('site-navigation');
    const mobileBreakpoint = siteNav ? parseInt(siteNav.dataset.mobileBreakpoint, 10) : 1420;
    
    if (window.innerWidth > mobileBreakpoint) {
      // Close mobile menu if open
      const mobileMenu = document.getElementById('mobile-menu');
      if (mobileMenu && !mobileMenu.hidden) {
        closeMobileMenu();
      }
    } else {
      // Close desktop dropdowns if open
      if (currentOpenDropdown) {
        closeDropdown(currentOpenDropdown);
      }
    }
  }
  
  // Debounce resize handler
  let resizeTimeout;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(handleResize, 150);
  });
  
  // ============================================
  // SEARCH FUNCTIONALITY (Placeholder)
  // ============================================
  
  function initSearch() {
    const searchButtons = document.querySelectorAll('.main-nav__search');
    
    searchButtons.forEach(button => {
      button.addEventListener('click', () => {
        // Placeholder for search functionality
        alert('Search functionality will be integrated via shortcode/embed');
      });
    });
  }
  
  // ============================================
  // EXTERNAL LINK DETECTION
  // ============================================
  
  function isExternalLink(url) {
    try {
      const linkHost = new URL(url, window.location.href).hostname;
      const currentHost = window.location.hostname;
      return linkHost !== currentHost && linkHost !== '';
    } catch {
      return false; // Invalid URL
    }
  }
  
  function getExternalIconSVG() {
    return `<svg class="nav-icon nav-icon--external" aria-hidden="true" width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M10.9375 1.3125H1.3125V10.9375H10.9375V1.3125ZM12.25 0V1.3125V10.9375V12.25H10.9375H1.3125H0V10.9375V1.3125V0H1.3125H10.9375H12.25ZM8.53125 3.0625H9.1875V3.71875V8.09375V8.75H7.875V8.09375V5.30469L4.18359 8.99609L3.71875 9.46094L2.78906 8.53125L3.25391 8.06641L6.94531 4.375H4.375H3.71875V3.0625H4.375H8.53125Z" fill="currentColor"/>
    </svg>`;
  }
  
  function processExternalLinks() {
    // Process desktop dropdown links
    const dropdownLinks = document.querySelectorAll('.main-nav__dropdown-item');
    
    dropdownLinks.forEach(link => {
      // Skip if already processed (either by server or previous JS run)
      if (link.dataset.externalProcessed === 'true' || 
          link.querySelector('.nav-icon--external')) {
        return;
      }
      
      // Mark as processed to prevent duplicate runs
      link.dataset.externalProcessed = 'true';
      
      // Check if external
      if (isExternalLink(link.href)) {
        link.dataset.external = 'true';
        
        // Find the title span to insert after it
        const titleSpan = link.querySelector('.main-nav__dropdown-item-title');
        if (titleSpan) {
          titleSpan.insertAdjacentHTML('beforeend', getExternalIconSVG());
        } else {
          // Fallback: add to end of link
          link.insertAdjacentHTML('beforeend', getExternalIconSVG());
        }
      }
    });
    
    // Process mobile dropdown links (2nd and 3rd level)
    const mobileLinks = document.querySelectorAll('.main-nav__mobile-dropdown a, .main-nav__mobile-subitem');
    
    mobileLinks.forEach(link => {
      // Skip if already processed
      if (link.dataset.externalProcessed === 'true' || 
          link.querySelector('.nav-icon--external')) {
        return;
      }
      
      // Mark as processed
      link.dataset.externalProcessed = 'true';
      
      // Check if external
      if (isExternalLink(link.href)) {
        link.dataset.external = 'true';
        link.insertAdjacentHTML('beforeend', getExternalIconSVG());
      }
    });
  }
  
  // ============================================
  // SCROLL BEHAVIOR
  // ============================================
  
  function initScrollBehavior() {
    const siteNav = document.getElementById('site-navigation');
    if (!siteNav) return;
    
    let lastScrollTop = 0;
    let ticking = false;
    let hasScrolledOnce = false;
    
    function updateNavigationOnScroll() {
      const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
      
      // Mark that user has scrolled at least once
      if (!hasScrolledOnce) {
        hasScrolledOnce = true;
        // Enable transitions now that user is scrolling
        siteNav.classList.add('scroll-behavior-ready');
      }
      
      // Add 'scrolled' class when scrolled past 1px
      if (scrollTop > 1) {
        siteNav.classList.add('scrolled');
      } else {
        siteNav.classList.remove('scrolled');
      }
      
      lastScrollTop = scrollTop;
      ticking = false;
    }
    
    function requestTick() {
      if (!ticking) {
        window.requestAnimationFrame(updateNavigationOnScroll);
        ticking = true;
      }
    }
    
    // Reset scroll to top on page load to ensure universal nav is fully visible
    // This prevents browser scroll restoration from cutting off the nav
    if (window.pageYOffset > 0 || document.documentElement.scrollTop > 0) {
      window.scrollTo(0, 0);
    }
    
    // Set up scroll listener
    window.addEventListener('scroll', requestTick, { passive: true });
  }
  
  // ============================================
  // INITIALIZATION
  // ============================================
  
  function init() {
    initDesktopDropdowns();
    initMobileMenu();
    initSearch();
    initKeyboardNavigation();
    processExternalLinks();
    initScrollBehavior();
    
    console.log('Site Navigation initialized - WCAG 2.2 AA compliant');
  }
  
  // Initialize when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
  
})();

