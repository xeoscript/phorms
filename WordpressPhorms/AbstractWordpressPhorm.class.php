<?php

abstract class AbstractWordpressPhorm {

    protected $prefix = NULL;
    private $method;
    private $multi_part = false;
    public $bound = false;
    private $fields = array();
    private $errors = array();
    private $clean;
    private $valid;
    public $lang;
    public static $html5 = false;

    public function __construct() {

        // Some Validation
        if ($this->prefix == NULL) {
            throw new ErrorException("Prefix is required.");
        }

        $this->multi_part = false;


        // Set up fields
        $this->define_fields();
        $this->fields = $this->find_fields();

        // Find submitted data, if any
        $this->method = 'post';
        $user_data = $_POST;

        // Determine if this form is bound (depends on defined fields)
        $this->bound = $this->check_if_bound($user_data);

        // change how we populate depending on whether we are bound or not
        if ($this->bound) {
            $this->set_data($user_data, false);
        }

    }

    abstract protected function define_fields();

    private function check_if_bound(array $data) {
        foreach ($this->fields as $name => $field) {
            if (array_key_exists($name, $data) || ($this->multi_part && array_key_exists($name, $_FILES))) {
                return TRUE;
            }
        }
        return FALSE;
    }

    private function find_fields() {
        $found = array();

        foreach (array_keys(get_object_vars($this)) as $name) {
            $field = $this->$name;

            if ($field instanceof Phorm_Field) {
                $name = $this->prefix . '_' . htmlentities($name);
                $id = sprintf('id_%s', $name);

                $field->set_attribute('id', $id);
                $field->set_attribute('name', ($this->$name->multi_field) ? sprintf('%s[]', $name) : $name);

                $found[$name] = $field;
            }
        }
        return $found;
    }

    protected function set_data($data, $export) {
        /**
         * @var $field Phorm_Field
         */

        foreach ($this->fields as $name => $field) {
            if (array_key_exists($name, $data)) {
                $value = $data[$name];
                if ($export) {
                    $value = $field->export_value($value);
                }
                $field->set_value($value);
            }
        }
    }

    public function cleaned_data($reprocess = FALSE) {
        /**
         * @var $field Phorm_Field
         */

        if (!$this->bound && !$this->is_valid()) {
            return NULL;
        }

        if (!is_array($this->clean) || $reprocess) {
            $this->clean = array();
            foreach ($this->fields as $name => $field) {
                $this->clean[$name] = $field->get_value();
            }
        }

        return $this->clean;
    }

    public function fields() {
        return $this->fields;
    }

    public function has_errors() {
        return !empty($this->errors);
    }

    public function get_errors() {
        /**
         * @var $field Phorm_Field
         */

        foreach ($this->fields as $name => &$field) {
            if ($errors = $field->get_errors()) {
                foreach ($errors as $error) {
                    $this->errors[$name] = array($error[0], $error[1]);
                }
            }
        }
        return $this->errors;
    }

    public function display_errors($prefix = '', $suffix = '') {
        $nested_errors = $this->get_errors();
        foreach ($nested_errors as $field_name => $field_error) {
            echo $prefix;
            echo $this->$field_name->label(false) . ': ' . $field_error[1];
            echo $suffix;
        }
    }

    public function is_valid($reprocess = FALSE) {
        /**
         * @var $field Phorm_Field
         */

        if ($reprocess || is_null($this->valid)) {
            if ($this->bound) {
                $this->valid = TRUE;
                foreach ($this->fields as $name => &$field) {
                    if (!$field->is_valid($reprocess)) {
                        $this->valid = FALSE;
                    }
                }

                // Set up the errors array.
                $this->get_errors();
            }
        }

        return $this->valid;
    }

    public function __toString() {
        return $this->as_wordpress_field();
    }

    public function as_wordpress_field() {
        /**
         * @var $field Phorm_Field
         */


        $elements = array();
        $fields = $this->fields();

        foreach ($fields as $name => $field) {

            $class = $field->get_attribute('class');
            $class .= ' widefat';
            $field->set_attribute('class', $class);

            $label = $field->label();
            if (!empty($label)) {
                $elements[] = '<p>';
                $elements[] = $label;
                $elements[] = $field;
                $elements[] = '</p>';
            } else {
                $elements[] = strval($field);
            }
        }
        return implode("\n", $elements);
    }

}