<?php
require_once "FieldTest.php";

class CheckBoxFieldTest extends FieldTest {
	
	protected $field = "Phorm_Field_CheckBox";
	
	function validation_data() {
		// no construct params required
		
		return array(
			array('on', true),
			array('', false),
			array('1', false),
		);
	}
	
}