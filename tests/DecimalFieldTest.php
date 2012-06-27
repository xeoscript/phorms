<?php
require_once 'FieldTest.php';

class DecimalFieldTest extends FieldTest {
	
	protected $field = 'Phorm_Field_Decimal';
	
	protected $defaults = array(25, 10);
	
	function validation_data() {
		return array(
			// allowed values
			array('1.2', 1.2),
			array('0', 0.0),
			array('0.0', 0.0),
			array('-0', 0.0),
			array('-23', -23.0),
			array('-12.546', -12.546),
			array('1e3', 1000.0),
			
			// precision
			array('1.23456789', 1.23, array(25, 2)),
			array('-1.23456789', -1.235, array(25, 3)),
			array('23.73854', 24.0, array(25, 0)),
			
			// bad input
			array('-',		'Invalid decimal value.'),
			array('ten',	'Invalid decimal value.'),
			array('1.6.5',	'Invalid decimal value.'),
		);
	}
	
	function html_data() {
		return array(
			array('3.14159', 
				'<input value="3.14159" size="25" class="phorm_field_decimal" type="text" />'),
			
			array('3.14159',
				'<input value="3.14159" size="5" class="phorm_field_decimal" type="text" />',
				array(5, 3)),
		);
	}
}