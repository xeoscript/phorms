<?php

abstract class AbstractWordpressPhorm extends Phorm_Phorm
{

    function __toString()
    {
        return $this->as_wordpress_field();
    }

    /**
     * Returns the form fields.
     *
     * @author Muhammed K K
     * @return string the HTML form wordpress widget styled
     */
    public function as_wordpress_field()
    {
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