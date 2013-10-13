<?php

require_once("Phorm.class.php");


abstract class BootstrapPhorm extends Phorm_Phorm
{

    function __toString()
    {
        echo 'called....';
        return $this->as_bootstrap();
    }

    /**
     * Returns the form fields.
     *
     * @author Muhammed K K
     * @return string the HTML form bootstrap styled
     */
    public function as_bootstrap()
    {
        /**
         * @var $field Phorm_Field
         */


        $elements = array();
        $fields = $this->fields();

        foreach ($fields as $name => $field) {

            $class = $field->get_attribute('class');
            $class .= ' form-control';
            $field->set_attribute('class', $class);

            $label = $field->label();
            if (!empty($label)) {
                $elements[] = '<div class="form-group">';
                $elements[] = $label;
                $elements[] = $field;
                $elements[] = '</div>';
            } else {
                $elements[] = strval($field);
            }
        }
        return implode("\n", $elements);
    }

}