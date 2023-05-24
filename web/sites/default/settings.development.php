<?php

// phpcs:ignoreFile

$databases['default']['default'] = array (
  'database' => 'drupal_development',
  'username' => 'root',
  'password' => 'root',
  'prefix' => '',
  'host' => 'db',
  'port' => '',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
);

/**
 * Salt for one-time login links, cancel links, form tokens, etc.
 */
$settings['hash_salt'] = '5ZlzlpQXOEEO-BmpzAjPhSat6h7XK1lf6ah0JtPOn4OYzRYU_STkZLuJkZZfVMkI9ogWqv7u2Q';

/**
 * Private file path.
 *
 * @var string
 */
$settings['file_private_path'] = '/var/www/web/sites/default/files/private';

/**
 * Drupal Configuration for development.
 *
 * Disable all caches capabilities.
 */
$settings['cache']['bins']['render'] = 'cache.backend.null';
$settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';
$settings['cache']['bins']['page'] = 'cache.backend.null';
$config['system.logging']['error_level'] = 'all';
$config['system.performance']['cache']['page']['use_internal'] = FALSE;
$config['system.performance']['css']['preprocess'] = FALSE;
$config['system.performance']['css']['gzip'] = FALSE;
$config['system.performance']['js']['preprocess'] = FALSE;
$config['system.performance']['js']['gzip'] = FALSE;
$config['system.performance']['response']['gzip'] = FALSE;
$config['views.settings']['ui']['show']['sql_query']['enabled'] = TRUE;
$config['views.settings']['ui']['show']['performance_statistics'] = TRUE;
$config['devel.settings']['devel_dumper'] = 'var_dumper';
$config['devel.settings']['error_handlers'] = [4 => '4'];
$config['devel.toolbar.settings']['toolbar_items'] = [
  'devel.cache_clear', 'devel.container_info.service', 'devel.field_info_page', 'devel.run_cron', 'devel.admin_settings_link',
  'devel.menu_rebuild', 'devel.reinstall', 'devel.route_info', 'devel.configs_list',
];

/**
 * Load services definition file.
 */
$settings['container_yamls'][] = $app_root . '/' . $site_path . '/services.development.yml';
