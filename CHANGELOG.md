# Changelog

History below begins at commit `51409128900581156f45a2db0f22cd1110b72329`. Sections are ordered **newest first** (by date heading). Each heading’s date is the corresponding git commit, or today’s date for work not yet committed.

## 2026-04-24

- **Patterson Navigation — menu targets:** The “Open link in a new tab” choice in Appearance → Menus is output on every rendered menu anchor (desktop bar, dropdown columns, mobile); `rel` merges XFN and adds `noopener` / `noreferrer` when opening a new tab. Sites can use the `patterson_nav_force_new_tab_external` filter to open external URLs in a new tab when WordPress did not set `target`.

## 2026-04-23

- **Mobile navigation:** Activating a real link inside the mobile panel (including in-page anchors) closes the drawer and returns focus to the menu control; behavior is aligned in the WordPress plugin and the static prototype, with docs updated to match.

## 2026-04-06

- **Patterson Navigation — settings:** Custom SVG in the admin settings is supported with sanitization; sensitive fields (e.g. Typekit ID, SVG logo, brand color) are editable only for users who should change them, with clearer save feedback in the admin UI.
