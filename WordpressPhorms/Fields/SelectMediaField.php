<?php if (!defined('PHORMS_ROOT')) {
    die('Phorms not loaded properly');
}
/**
 * @package    WordpressPhorms
 * @subpackage Fields
 */


/**
 * Phorm_Field_Text
 *
 * A simple text field.
 *
 * @author     Muhammed K K
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 * @package    WordpressPhorms
 * @subpackage Fields
 */
class WordpressSelectMediaField extends Phorm_Field_Text {

    /**
     * @param string $label      the field's text label
     * @param array  $attributes a list of key/value pairs representing HTML attributes
     *
     */
    public function __construct($label, array $attributes = array()) {
        parent::__construct($label, 255, 255, array(), $attributes);
        WordpressSelectMediaWidget::wp_hooks();
    }

    /**
     * Returns a new WordpressSelectMediaWidget.
     *
     * @return WordpressSelectMediaWidget
     */
    protected function get_widget() {
        return new WordpressSelectMediaWidget();
    }

}