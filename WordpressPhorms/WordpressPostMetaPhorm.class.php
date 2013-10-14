<?php

/*
 * TODO: Find a method to add prefix in id and name of the fields.
 */

abstract class WordpressPostMetaPhorm extends AbstractWordpressPhorm {
    private $has_error;

    protected $meta_box_id = NULL;
    protected $prefix = NULL;
    protected $name = NULL;
    protected $nonce = NULL;

    protected $post_type = 'post';
    protected $position = 'normal';

    /**
     *
     */
    function __construct() {
        // Some Validation
        if ($this->prefix == NULL) {
            throw new ErrorException("Prefix is required.");
        }
        if ($this->name == NULL) {
            throw new ErrorException("Name is required.");
        }
        if ($this->nonce == NULL) {
            throw new ErrorException("Nonce is required.");
        }
        if ($this->meta_box_id == NULL) {
            throw new ErrorException("Meta box ID is required.");
        }

        parent::__construct();

        $this->nonce_name = $this->prefix . $this->meta_box_id;
        $this->add_wp_hooks();
    }

    /**
     * Add the wordpress meta boxes and actions.
     */
    protected function add_wp_hooks() {
        add_meta_box($this->meta_box_id, $this->name, array($this, 'display_box'), $this->post_type, $this->position);
        add_action('add_meta_boxes', array($this, 'display_box'));
        add_action('save_post', array($this, 'save_data'));
        add_action('save_post', array($this, 'validate_save'));
        add_action('admin_notices', array($this, 'show_error_message'));
    }

    /**
     * Loads the data to the form from the database.
     * This function will only execute if this is not
     * a post back from the user.
     */
    protected function load_data($post) {
        // Don't load data if this is a post back.
        if ($this->bound) {
            return;
        }

        $fields = $this->fields();
        $values = array();
        $post_id = $post->ID;

        foreach ($fields as $field) {
            $name = $this->prefix . $field;
            $values[$name] = get_post_meta($post_id, $name, true);
        }

        $this->set_data($values, true);
    }

    /**
     * Checks whether the post meta items can be saved now.
     *
     * @return bool
     */
    protected function can_save() {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return false;
        }

        $nonce_name = $this->nonce_name;
        if (!isset($_POST[$nonce_name])) {
            return false;
        }
        $nonce = $_POST[$nonce_name];
        if (!wp_verify_nonce($nonce, $this->nonce)) {
            return false;
        }
        return true;
    }

    /**
     * @param $name  string The name of the field
     * @param $value string the value of the field
     *
     * @return string
     */
    protected function sanitize_field($name, $value) {
        return sanitize_text_field($value);
    }

    /**
     * Saved the data when update or publish is clicked. This function
     * will check if this can be saved using can_save function. If the
     * data can be saved then it will be saved. Otherwise it will be
     * ignored.
     *
     * @param $post_id int The ID of the post that has to be saved.
     *
     * @return mixed
     */
    public function save_data($post_id) {
        if ($this->can_save() == false) {
            return $post_id;
        }

        $fields = $this->fields();
        $values = $this->cleaned_data();

        foreach ($fields as $field) {
            $value = $values[$field];
            $value = $this->sanitize_field($field, $value);
            update_post_meta($post_id, $field, $value);
        }

        $this->set_data($values, true);
    }

    public function validate_save($post_id) {
        /**
         * @var $wpdb wpdb
         */

        if ($this->can_save() == false) {
            return false;
        }
        // on attempting to publish - check for completion and intervene if necessary
        if ((isset($_POST['publish']) || isset($_POST['save'])) && $_POST['post_status'] == 'publish') {
            //  don't allow publishing while any of these are incomplete
            if ($this->is_valid() == false) {
                global $wpdb;

                $wpdb->update($wpdb->posts, array('post_status' => 'pending'), array('ID' => $post_id));
                // filter the query URL to change the published message
                add_filter('redirect_post_location', create_function('$location', 'return add_query_arg("message", "4", $location);'));
            }
        }
    }

    /**
     * Displays the meta box generation HTML.
     *
     * @param $post
     */
    public function display_box($post) {
        $this->load_data($post);
        wp_nonce_field($this->nonce_name, $this->nonce);
        echo $this;
    }

    public function show_error_message() {
        if ($this->has_error == false) {
            return;
        }

        $messages = array();
        $messages[] = '<div class="error" id="message">';
        $messages[] =   '<p>';
        $messages[] =     '<strong>';
        $messages[] =       'Please correct the errors.';
        $messages[] =     '</strong>';
        $messages[] =   '</p>';
        $messages[] = '</div>';

        echo implode('', $messages);
    }

}