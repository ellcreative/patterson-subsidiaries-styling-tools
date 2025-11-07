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
    event.preventDefault();
    const trigger = event.currentTarget;
    const dropdownId = trigger.getAttribute('aria-controls');
    const dropdown = document.getElementById(dropdownId);
    
    if (!dropdown) return;
    
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
    
    // Focus first focusable element in dropdown
    setTimeout(() => {
      const firstFocusable = dropdown.querySelector('a, button');
      if (firstFocusable) {
        // Don't auto-focus, let user tab into it naturally
        // firstFocusable.focus();
      }
    }, 50);
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
    
    // Close all mobile accordions
    const openAccordions = mobileMenu.querySelectorAll('.main-nav__mobile-link[aria-expanded="true"]');
    openAccordions.forEach(accordion => {
      const dropdownId = accordion.getAttribute('aria-controls');
      const dropdown = document.getElementById(dropdownId);
      if (dropdown) {
        dropdown.hidden = true;
        accordion.setAttribute('aria-expanded', 'false');
      }
    });
  }
  
  function initMobileAccordion() {
    const accordionTriggers = document.querySelectorAll('.main-nav__mobile-link[aria-controls]');
    
    accordionTriggers.forEach(trigger => {
      trigger.addEventListener('click', () => {
        const dropdownId = trigger.getAttribute('aria-controls');
        const dropdown = document.getElementById(dropdownId);
        
        if (!dropdown) return;
        
        const isExpanded = trigger.getAttribute('aria-expanded') === 'true';
        
        if (isExpanded) {
          dropdown.hidden = true;
          trigger.setAttribute('aria-expanded', 'false');
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
  // RESPONSIVE BEHAVIOR
  // ============================================
  
  function handleResize() {
    const mobileBreakpoint = 1024;
    
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
  // INITIALIZATION
  // ============================================
  
  function init() {
    initDesktopDropdowns();
    initMobileMenu();
    initSearch();
    
    console.log('Site Navigation initialized');
  }
  
  // Initialize when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
  
})();

