<?php
require_once "FieldTest.php";

class TextFieldTest extends FieldTest {
	
	protected $field = 'Phorm_Field_Text';
	
	protected $defaults = array('size' => 25, 'max_length' => 100);
	
	function validation_data() {
		return array(
			array('Hello', 'Hello'),
			array('Andy &amp; Bob', 'Andy & Bob'),
			
			// blank input - treat as string not null
			array('', ''),
			array('', 'This field is required.', $this->defaults + $this->require),
			
			// max length
			array('12345', '12345', array(25, 5)),
			array('123456', 'Must be 5 or fewer characters in length.', array(25, 5)),
		);
	}
	
	function html_data() {
		return array (
			array('Hello', '<input value="Hello" maxlength="100" size="25" class="phorm_field_text" type="text" />'),
			array('Hello', '<input value="Hello" maxlength="20" size="10" class="phorm_field_text" type="text" />',
				array('size' => 10, 'max_length' => 20)),
				
			// override other attributes
			array('Hello', '<input value="Hello" type="password" maxlength="100" size="25" class="phorm_field_text" />',
				$this->defaults + array('validators' => array(), 'attributes' => array('type' => 'password'))),
		);
	}
	
}