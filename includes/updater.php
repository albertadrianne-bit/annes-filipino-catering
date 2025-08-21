<?php
if ( ! defined('ABSPATH') ) exit;

/**
 * Lightweight GitHub updater for a single plugin.
 * Shows WP "Update now" when a newer GitHub release tag exists.
 *
 * Works with public repos. If you make it private, add a token header.
 */
class AnnesFS_GitHub_Updater {
  private $user;
  private $repo;
  private $plugin_file;   // e.g., annes-filipino-catering/annes-filipino-catering.php
  private $plugin_slug;   // e.g., annes-filipino-catering/annes-filipino-catering.php
  private $plugin_dir;    // e.g., annes-filipino-catering
  private $api_base = 'https://api.github.com/repos/';
  private $cache_key = 'annesfs_github_release';
  private $cache_ttl = 6 * HOUR_IN_SECONDS; // check every 6h

  function __construct( $user, $repo, $plugin_file ) {
    $this->user        = $user;
    $this->repo        = $repo;
    $this->plugin_file = $plugin_file;
    $this->plugin_slug = plugin_basename($plugin_file);
    $this->plugin_dir  = dirname($this->plugin_slug);

    add_filter('pre_set_site_transient_update_plugins', [$this,'check_for_update']);
    add_filter('plugins_api',                           [$this,'plugins_api'], 10, 3);
    add_filter('upgrader_post_install',                 [$this,'fix_install_dir'], 10, 3);
  }

  private function get_plugin_version() {
    if ( ! function_exists('get_plugin_data') ) {
      require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    $data = get_plugin_data( WP_PLUGIN_DIR . '/' . $this->plugin_slug, false, false );
    return $data['Version'] ?? '0.0.0';
  }

  private function fetch_latest_release() {
    $cached = get_site_transient($this->cache_key);
    if ( $cached ) return $cached;

    $url  = $this->api_base . $this->user . '/' . $this->repo . '/releases/latest';
    $args = [
      'headers' => [
        'Accept'     => 'application/vnd.github+json',
        'User-Agent' => 'WordPress/' . get_bloginfo('version') . '; ' . home_url('/'),
      ],
      'timeout' => 15,
    ];
    $res = wp_remote_get($url, $args);
    if ( is_wp_error($res) ) return false;

    $code = wp_remote_retrieve_response_code($res);
    $body = json_decode( wp_remote_retrieve_body($res), true );
    if ( $code !== 200 || ! is_array($body) ) return false;

    // Prefer the first attached asset (a zip you upload on the Release).
    $package = '';
    if ( ! empty($body['assets'][0]['browser_download_url']) ) {
      $package = $body['assets'][0]['browser_download_url'];
    } else {
      // Fallback to GitHub source zipball if no asset attached.
      $package = $body['zipball_url'];
    }

    $release = [
      'new_version' => ltrim( $body['tag_name'] ?? '', 'v' ),
      'package'     => $package,
      'url'         => $body['html_url'] ?? '',
      'name'        => $body['name'] ?? ($this->repo . ' ' . ($body['tag_name'] ?? '')),
    ];

    set_site_transient($this->cache_key, $release, $this->cache_ttl);
    return $release;
  }

  public function check_for_update( $transient ) {
    if ( empty($transient->checked) ) return $transient;

    $current = $this->get_plugin_version();
    $rel     = $this->fetch_latest_release();
    if ( ! $rel || empty($rel['new_version']) ) return $transient;

    if ( version_compare( $rel['new_version'], $current, '>' ) ) {
      $obj = new stdClass();
      $obj->slug        = $this->plugin_dir;                // directory slug
      $obj->plugin      = $this->plugin_slug;               // plugin file
      $obj->new_version = $rel['new_version'];
      $obj->url         = $rel['url'];
      $obj->package     = $rel['package'];                  // zip url
      $transient->response[ $this->plugin_slug ] = $obj;
    }
    return $transient;
  }

  public function plugins_api( $result, $action, $args ) {
    if ( $action !== 'plugin_information' || empty($args->slug) || $args->slug !== $this->plugin_dir ) {
      return $result;
    }
    $rel = $this->fetch_latest_release();
    if ( ! $rel ) return $result;

    $obj = new stdClass();
    $obj->name          = 'Anne’s Filipino Catering';
    $obj->slug          = $this->plugin_dir;
    $obj->version       = $rel['new_version'];
    $obj->author        = '<a href="https://www.annesfilipinocatering.com">Anne’s Filipino Sweet Spot</a>';
    $obj->homepage      = $rel['url'];
    $obj->sections      = [
      'description' => 'Catering bundles, quote flow, quick-view variations, delivery logic, and guest estimator.',
      'changelog'   => 'See GitHub Releases for detailed notes.',
    ];
    $obj->download_link = $rel['package'];
    return $obj;
  }

  /**
   * When using GitHub "zipball", WP unpacks to a folder like user-repo-commit/.
   * This renames/moves it to our real plugin directory so activation continues to work.
   */
  public function fix_install_dir( $response, $hook_extra, $result ) {
    if ( empty($hook_extra['plugin']) || $hook_extra['plugin'] !== $this->plugin_slug ) return $response;

    $dest = WP_PLUGIN_DIR . '/' . $this->plugin_dir;
    if ( ! empty($result['destination']) && is_dir($result['destination']) && $result['destination'] !== $dest ) {
      // Move the unpacked folder to expected plugin dir.
      global $wp_filesystem;
      if ( ! $wp_filesystem ) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        WP_Filesystem();
      }
      if ( $wp_filesystem && ! $wp_filesystem->move( $result['destination'], $dest, true ) ) {
        // If move fails, leave as-is; user can re-install manually.
        return $response;
      }
      $response['destination'] = $dest;
    }
    return $response;
  }
}
