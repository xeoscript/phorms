<?php
require_once "FieldTest.php";

class DropDownFieldTest extends FieldTest {
	
	protected $field = "Phorm_Field_DropDown";
	
	function validation_data() {
		$defaults = array('choices' => array('1' => 'One', '2' => 'Two', '3' => 'Three'));
		
		return array(
			array('1', '1', $defaults),
			
			array('', null, $defaults),
			array('', 'This field is required.', $defaults + array('validators' => array('required'))),
			array('4', 'Invalid selection.', $defaults),
		);
	}
	
}