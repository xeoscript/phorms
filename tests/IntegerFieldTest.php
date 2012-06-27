<?php
require_once "FieldTest.php";

class IntegerFieldTest extends FieldTest {
	
	protected $field = 'Phorm_Field_Integer';
	
	protected $defaults = array('size' => 25, 'max_digits' => 10);
	
	function validation_data() {
		return array(
			// allowed values
			array('1', 1),
			array('0', 0),
			array('23', 23),
			array('-45', -45),
			
			// max digits
			array('1234567890', 'Must be a whole number with 5 or less digits.', array(25, 5)),
			array('12345', 12345, array(25, 5)),
			
			// bad input
			array('1.6',	'Must be a whole number.'),
			array('-',		'Must be a whole number.'),
			array('ten',	'Must be a whole number.'),
			array('1.6.5',	'Must be a whole number.'),
		);
	}
	
	function html_data() {
		return array(
			array('23', 
				'<input value="23" maxlength="10" size="25" class="phorm_field_integer" type="text" />'),
			
			array('23',
				'<input value="23" maxlength="8" size="5" class="phorm_field_integer" type="text" />',
				array(5, 8)),
		);
	}
	
}