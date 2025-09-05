<?php
if ( ! defined('ABSPATH') ) exit;

/**
 * Anne’s Filipino Catering – GitHub Updater
 * - Checks GitHub Releases for a higher tag than the installed version
 * - Surfaces the update in WP → Plugins and installs the ZIP from the release
 *
 * Repo: albertadrianne-bit/annes-filipino-catering
 */

class AnnesFS_Github_Updater {
  private $repo         = 'albertadrianne-bit/annes-filipino-catering';
  private $api_base     = 'https://api.github.com/repos';
  private $zip_template = 'https://github.com/%s/releases/download/%s/annes-filipino-catering-%s.zip';
  private $slug;        // plugin slug (file)
  private $basename;    // plugin basename
  private $version;     // installed version
  private $transient_key = 'annesfs_github_release';

  // OPTIONAL: set a token for private repos (classic PAT with repo scope)
  private $token = ''; // e.g. 'ghp_XXXXXXXXXXXXXXXXXXXX'

  public function __construct(){
    $this->basename = plugin_basename( dirname(__DIR__) . '/annes-filipino-catering.php' );
    $this->slug     = 'annes-filipino-catering/annes-filipino-catering.php';

    // Read installed plugin version from the main file define
    if ( ! defined('ANNESFS_VER') ) {
      // Fallback: read from plugin header if define is missing
      $data = get_file_data( dirname(__DIR__).'/annes-filipino-catering.php', ['Version' => 'Version'], 'plugin' );
      $this->version = $data['Version'] ?? '0.0.0';
    } else {
      $this->version = ANNESFS_VER;
    }

    add_filter('pre_set_site_transient_update_plugins', [ $this, 'check_for_update' ]);
    add_filter('plugins_api', [ $this, 'plugins_api' ], 10, 3);

    // Clear cache button: ?annesfs-clear-cache=1
    add_action('admin_init', function(){
      if ( current_user_can('manage_options') && isset($_GET['annesfs-clear-cache']) ) {
        delete_site_transient( $this->transient_key );
        wp_safe_redirect( remove_query_arg('annesfs-clear-cache') );
        exit;
      }
    });
  }

  private function api_headers(){
    $h = [
      'Accept'     => 'application/vnd.github+json',
      'User-Agent' => 'annesfs-updater'
    ];
    if ( $this->token ) {
      $h['Authorization'] = 'token '.$this->token;
    }
    return $h;
  }

  private function get_latest_release(){
    // cache to avoid rate limits
    $cached = get_site_transient( $this->transient_key );
    if ( $cached ) return $cached;

    $url = sprintf('%s/%s/releases/latest', $this->api_base, $this->repo);
    $resp = wp_remote_get($url, [ 'timeout'=>15, 'headers'=>$this->api_headers() ]);
    if ( is_wp_error($resp) ) return null;

    $code = wp_remote_retrieve_response_code($resp);
    $body = json_decode( wp_remote_retrieve_body($resp), true );

    if ( $code === 200 && !empty($body['tag_name']) ) {
      // Persist minimal fields we need
      $data = [
        'tag'         => ltrim($body['tag_name'], 'v'), // handle v2.2.4 tags
        'name'        => $body['name'] ?? $body['tag_name'],
        'body'        => $body['body'] ?? '',
        'zipball_url' => $body['zipball_url'] ?? '',
      ];
      // store 30 minutes
      set_site_transient( $this->transient_key, $data, 30 * MINUTE_IN_SECONDS );
      return $data;
    }
    return null;
  }

  public function check_for_update( $transient ){
    if ( empty($transient->checked) ) return $transient;

    $release = $this->get_latest_release();
    if ( ! $release ) return $transient;

    $remote_version = $release['tag']; // already cleaned of the leading v
    // compare versions
    if ( version_compare( $remote_version, $this->version, '>' ) ) {
      $zip = sprintf( $this->zip_template, $this->repo, 'v'.$remote_version, $remote_version );

      $info = (object)[
        'slug'        => $this->slug,
        'plugin'      => $this->basename,
        'new_version' => $remote_version,
        'url'         => 'https://github.com/'.$this->repo,
        'package'     => $zip,
        // optional metadata for UI:
        'tested'      => get_bloginfo('version'),
        'requires'    => '6.0',
      ];

      $transient->response[ $this->basename ] = $info;
    }
    return $transient;
  }

  public function plugins_api( $res, $action, $args ){
    if ( $action !== 'plugin_information' ) return $res;
    if ( empty($args->slug) || $args->slug !== $this->slug ) return $res;

    $release = $this->get_latest_release();
    if ( ! $release ) return $res;

    $zip = sprintf( $this->zip_template, $this->repo, 'v'.$release['tag'], $release['tag'] );

    $res = (object)[
      'name'          => "Anne’s Filipino Catering",
      'slug'          => $this->slug,
      'version'       => $release['tag'],
      'author'        => '<a href="https://www.annesfilipinocatering.com">Anne’s Filipino Sweet Spot</a>',
      'homepage'      => 'https://github.com/'.$this->repo,
      'sections'      => [
        'description' => wp_kses_post( nl2br( $release['body'] ?: 'Custom WooCommerce plugin for catering orders.' ) ),
        'changelog'   => wp_kses_post( nl2br( $release['body'] ?: '' ) ),
      ],
      'download_link' => $zip,
      'requires'      => '6.0',
      'tested'        => get_bloginfo('version'),
    ];
    return $res;
  }
}

// Boot
add_action('plugins_loaded', function(){ new AnnesFS_Github_Updater(); });
