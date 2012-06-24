<?php
require_once 'FieldTest.php';

class DecimalFieldTest extends FieldTest {
	
	protected $field = 'Phorm_Field_Decimal';
	
	function validation_data() {
		$defaults = array(25, 10);
		
		return array(
			// allowed values
			array('1.2', 1.2, $defaults),
			array('0', 0.0, $defaults),
			array('0.0', 0.0, $defaults),
			array('-0', 0.0, $defaults),
			array('-23', -23.0, $defaults),
			array('-12.546', -12.546, $defaults),
			array('1e3', 1000.0, $defaults),
			
			// blank maps to null
			array('', null, $defaults),
			
			// precision
			array('1.23456789', 1.23, array(25, 2)),
			array('-1.23456789', -1.235, array(25, 3)),
			array('23.73854', 24.0, array(25, 0)),
			
			// bad input
			array('-',		'Invalid decimal value.', $defaults),
			array('ten',	'Invalid decimal value.', $defaults),
			array('1.6.5',	'Invalid decimal value.', $defaults),
		);
	}
}