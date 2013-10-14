<?php

abstract class AbstractWordpressPhorm extends Phorm_Phorm {

    function __toString() {
        return $this->as_wordpress_field();
    }

    /**
     * Sets the value of each field.  Uses the value from the superglobal
     * if provided, otherwise exports the default value.
     *
     * @author Tris Forster
     *
     * @param array   $data   data to set
     * @param boolean $export whether to run the value through export_value() first
     *
     * @return null
     */
    protected  function set_data($data, $export) {
        /**
         * @var $fields array
         * @var $field  Phorm_Field
         */

        $fields = $this->fields();
        foreach ($fields as $name => &$field) {
            if (array_key_exists($name, $data)) {
                $value = $data[$name];
                if ($export) {
                    $value = $field->export_value($value);
                }
                $field->set_value($value);
            }
        }
    }

    /**
     * Returns the form fields.
     *
     * @author Muhammed K K
     * @return string the HTML form wordpress widget styled
     */
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