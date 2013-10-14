<?php

/**
 * Define some constants
 */
define('PHORMS_WORDPRESS', true);
define('PHORMS_WORDPRESS_DIR', dirname(__FILE__));

if (!defined('PHORMS_WORDPRESS_ASSETS_DIR')) {
    define('PHORMS_WORDPRESS_ASSETS_DIR', plugin_dir_url(__FILE__) . 'assets/');
}


/**
 * Load the core Phorms Library
 */
include(PHORMS_WORDPRESS_DIR . '/' . 'phorms.php');

include(PHORMS_ROOT . 'WordpressPhorms/AbstractWordpressPhorm.class.php');
include(PHORMS_ROOT . 'WordpressPhorms/WordpressPostMetaPhorm.class.php');

/**
 * Fields
 */
include(PHORMS_ROOT . 'WordpressPhorms/Fields/SelectMediaField.php');


/**
 * Widgets
 */
include(PHORMS_ROOT . 'WordpressPhorms/Widgets/SelectMediaWidget.php');