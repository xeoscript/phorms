<?php if (!defined('PHORMS_ROOT')) {
    die('Phorms not loaded properly');
}

/**
 * @package    WordpressPhorms
 * @subpackage Widgets
 */

/**
 * Phorm_Widget_Text
 *
 * A basic select media widget.
 *
 * @author     Muhammed K K
 * @package    WordpressPhorms
 * @subpackage Widgets
 */
class WordpressSelectMediaWidget extends Phorm_Widget_Text {

    function __construct() {

    }

    private static function register_script() {
        if (defined('WORDPRESS_SELECT_MEDIA_WIDGET_REGISTERED') && WORDPRESS_SELECT_MEDIA_WIDGET_REGISTERED) {
            return;
        }

        wp_register_script('wordpress-select-media-widget', PHORMS_WORDPRESS_ASSETS_DIR . 'js/select-media-widget.js', array('jquery', 'media-upload', 'thickbox'));

        define('WORDPRESS_SELECT_MEDIA_WIDGET_REGISTERED', TRUE);
    }

    public static function enqueue_scripts() {
        WordpressSelectMediaWidget::register_script();

        wp_enqueue_script('jquery');
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
        wp_enqueue_script('wordpress-select-media-widget');
    }

    public static function enqueue_styles() {
        wp_enqueue_style('thickbox');
    }

    public static function wp_hooks() {
        /** @noinspection PhpUndefinedConstantInspection */
        add_action('admin_print_scripts', array(WordpressSelectMediaWidget, 'enqueue_scripts'));
        /** @noinspection PhpUndefinedConstantInspection */
        add_action('admin_print_styles', array(WordpressSelectMediaWidget, 'enqueue_styles'));
    }

    protected function serialize($value, array $attributes = array()) {
        $code = '<input type="button" class="select-media-widget" value="Select Image" />';
        $code = parent::serialize($value, $attributes) . $code;
        return $code;
    }

}