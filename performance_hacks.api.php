<?php
/**
 * @file
 * API documentation for performance hacks.
 */

/**
 * Add a custom cache key for node view render caching.
 *
 * @param $node
 *   The node being cached.
 * @param $view_mode
 *   The view mode being cached.
 *
 * @return
 *   A string to be added to the cache key for this node and view mode.
 */
function hook_performance_hacks_custom_cache_key($node, $view_mode) {
  if ($view_mode == 'full') {
    // Some custom code themes the node different depending on a $_GET param.
    if (isset($_GET['foo'])) {
      return $_GET['foo'];
    }
  }
} 

/**
 * Allow for custom elements to be appended to the cache key.
 *
 * @param $keys
 *   Array cache keys to be cleared.
 * @param $nid
 *   Integer node id.
 */
function hook_performance_hacks_custom_cache_keys_alter(&$keys, $nid) {
  $themes = array_keys(list_themes());

  $new_keys = array();
  foreach ($themes as $theme) {
    foreach ($keys as $key) {
      $new_keys[] = $key . ":$theme";
    }
  }

  $key = $new_keys;
}

/**
 * Allow static $_GET values to be removed when caching an item.
 *
 * By default the presence of a $_GET value results in an item not entering the
 * render cache however some $_GET values can be 'static' and have no effect on
 * what is being rendered.
 *
 * @param $get
 *   Array of get parameters.
 */
function hook_performance_hacks_cache_get_alter(&$get) {
  if (isset($get['XDEBUG_PROFILE'])) {
    unset($get['XDEBUG_PROFILE']);
  }
}
