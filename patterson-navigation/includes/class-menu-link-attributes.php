<?php
/**
 * Menu item anchor attributes (target, rel) for Patterson Navigation
 */

if (!defined('ABSPATH')) {
    exit;
}

class Patterson_Nav_Menu_Link_Attributes {

    /**
     * Whether a URL is external to this WordPress site (hostname comparison).
     *
     * @param string $url
     * @return bool
     */
    public static function is_external_url($url) {
        $home_host = parse_url(home_url(), PHP_URL_HOST);
        $link_host = parse_url($url, PHP_URL_HOST);

        if (!$link_host) {
            return false;
        }

        return $home_host !== $link_host;
    }

    /**
     * Build a string of HTML attributes for a nav menu item anchor (leading space if non-empty).
     *
     * @param object $item    Nav menu item from wp_get_nav_menu_items().
     * @param string $context Optional context for filters (e.g. main-nav, dropdown-item).
     * @return string Safe attribute fragment, e.g. ' target="_blank" rel="noopener noreferrer"'.
     */
    public static function html_fragment_for_item($item, $context = '') {
        $url = isset($item->url) ? (string) $item->url : '';

        $target_attr = self::resolve_target_attribute($item, $url, $context);
        $rel_tokens = self::build_rel_tokens($item, $target_attr === '_blank');

        $out = '';
        if ($target_attr !== '') {
            $out .= ' target="' . esc_attr($target_attr) . '"';
        }
        if (!empty($rel_tokens)) {
            $out .= ' rel="' . esc_attr(implode(' ', $rel_tokens)) . '"';
        }

        return $out;
    }

    /**
     * Resolve target value: empty string means omit attribute.
     *
     * @param object $item
     * @param string $url
     * @param string $context
     * @return string '' | '_blank' | '_self' | '_parent' | '_top'
     */
    private static function resolve_target_attribute($item, $url, $context) {
        $raw = isset($item->target) ? trim((string) $item->target) : '';
        $allowed = array('_blank', '_self', '_parent', '_top');

        if ($raw !== '') {
            $lower = strtolower($raw);
            if ($lower === 'blank') {
                return '_blank';
            }
            if (in_array($lower, $allowed, true)) {
                return $lower;
            }
            return '';
        }

        $force = (bool) apply_filters('patterson_nav_force_new_tab_external', false, $item, $context);
        if ($force && self::is_external_url($url) && self::url_allows_new_tab_fallback($url)) {
            return '_blank';
        }

        return '';
    }

    /**
     * Skip schemes / URLs where forcing a new tab is inappropriate.
     *
     * @param string $url
     * @return bool
     */
    private static function url_allows_new_tab_fallback($url) {
        $url = trim($url);
        if ($url === '' || $url === '#') {
            return false;
        }

        $scheme = parse_url($url, PHP_URL_SCHEME);
        if ($scheme !== null && $scheme !== '') {
            $s = strtolower($scheme);
            if (!in_array($s, array('http', 'https'), true)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Merge XFN / menu relationship tokens with noopener/noreferrer when using a new browsing context.
     *
     * @param object $item
     * @param bool   $opens_new_tab
     * @return string[]
     */
    private static function build_rel_tokens($item, $opens_new_tab) {
        $tokens = array();

        if (!empty($item->xfn)) {
            $parts = preg_split('/\s+/', (string) $item->xfn, -1, PREG_SPLIT_NO_EMPTY);
            foreach ($parts as $part) {
                $t = strtolower(trim($part));
                if (self::is_valid_rel_token($t)) {
                    $tokens[] = $t;
                }
            }
            $tokens = array_values(array_unique($tokens));
        }

        if ($opens_new_tab) {
            foreach (array('noopener', 'noreferrer') as $req) {
                if (!in_array($req, $tokens, true)) {
                    $tokens[] = $req;
                }
            }
        }

        return $tokens;
    }

    /**
     * Allow XFN / relationship tokens safe for rel="" (alphanumeric + hyphen).
     *
     * @param string $token Lowercase.
     * @return bool
     */
    private static function is_valid_rel_token($token) {
        return (bool) preg_match('/^[a-z][a-z0-9\-]*$/', $token) && strlen($token) <= 48;
    }
}

