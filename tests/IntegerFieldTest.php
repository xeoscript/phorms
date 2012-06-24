<?php
require_once "FieldTest.php";

class IntegerFieldTest extends FieldTest {
	
	protected $field = 'Phorm_Field_Integer';
	
	function validation_data() {
		$defaults = array('size' => 25, 'max_digits' => 10);
		
		return array(
			// allowed values
			array('1', 1, $defaults),
			array('0', 0, $defaults),
			array('23', 23, $defaults),
			array('-45', -45, $defaults),
			
			// blank maps to null
			array('', null, $defaults),
			
			// max digits
			array('1234567890', 'Must be a whole number with 5 or less digits.', array(25, 5)),
			array('12345', 12345, array(25, 5)),
			
			// bad input
			array('1.6',	'Must be a whole number.', $defaults),
			array('-',		'Must be a whole number.', $defaults),
			array('ten',	'Must be a whole number.', $defaults),
			array('1.6.5',	'Must be a whole number.', $defaults),
		);
	}
	
}