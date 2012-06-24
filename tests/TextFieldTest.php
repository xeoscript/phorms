<?php
require_once "FieldTest.php";

class TextFieldTest extends FieldTest {
	
	protected $field = 'Phorm_Field_Text';
	
	function validation_data() {
		$defaults = array('size' => 25, 'max_length' => 100);
		
		return array(
			array('Hello', 'Hello', $defaults),
			array('Andy &amp; Bob', 'Andy & Bob', $defaults),
			
			// blank input - treat as string not null
			array('', '', $defaults),
			array('', 'This field is required.', $defaults + $this->require),
			
			// max length
			array('12345', '12345', array(25, 5)),
			array('123456', 'Must be 5 or fewer characters in length.', array(25, 5)),
		);
	}
	
}