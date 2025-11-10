/**
 * Load navigation configuration and populate links dynamically
 */
(function() {
  'use strict';

  // Load config and populate navigation
  fetch('../patterson-navigation/config/navigation-config.json')
    .then(response => response.json())
    .then(config => {
      populateUniversalNav(config);
      populateMobileUniversalNav(config);
    })
    .catch(error => {
      console.error('Failed to load navigation config:', error);
    });

  /**
   * Populate desktop universal navigation
   */
  function populateUniversalNav(config) {
    const logoLink = document.querySelector('.universal-nav__logo a');
    const menu = document.querySelector('.universal-nav__menu');

    if (logoLink && config.universal_nav.logo) {
      logoLink.href = config.universal_nav.logo.url;
      logoLink.setAttribute('aria-label', config.universal_nav.logo.aria_label);
      
      const img = logoLink.querySelector('img');
      if (img) {
        img.alt = config.universal_nav.logo.alt;
        img.width = config.universal_nav.logo.width;
        img.height = config.universal_nav.logo.height;
      }
    }

    if (menu && config.universal_nav.links) {
      menu.innerHTML = '';
      config.universal_nav.links.forEach(link => {
        const li = document.createElement('li');
        const a = document.createElement('a');
        a.href = link.url;
        a.textContent = link.title;
        li.appendChild(a);
        menu.appendChild(li);
      });
    }
  }

  /**
   * Populate mobile universal navigation
   */
  function populateMobileUniversalNav(config) {
    const logoLink = document.querySelector('.main-nav__mobile-universal-logo a');
    const menu = document.querySelector('.main-nav__mobile-universal-menu');

    if (logoLink && config.universal_nav.logo) {
      logoLink.href = config.universal_nav.logo.url;
      logoLink.setAttribute('aria-label', config.universal_nav.logo.aria_label);
      
      const img = logoLink.querySelector('img');
      if (img) {
        img.alt = config.universal_nav.logo.alt;
        img.width = config.universal_nav.logo.width;
        img.height = config.universal_nav.logo.height;
      }
    }

    if (menu && config.universal_nav.links) {
      menu.innerHTML = '';
      config.universal_nav.links.forEach(link => {
        const li = document.createElement('li');
        const a = document.createElement('a');
        a.href = link.url;
        a.textContent = link.title;
        li.appendChild(a);
        menu.appendChild(li);
      });
    }
  }
})();

