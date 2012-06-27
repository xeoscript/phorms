<?php
require_once "FieldTest.php";

class RangeFieldTest extends FieldTest {

	protected $field = 'Phorm_Field_Range';

	protected $defaults = array('min' => 0, 'max' => 100, 'slider' => false);

	public function validation_data() {
		return array(
			array('0', 0),
			array('4', 4),
			array('100', 100),
			
			// outside range
			array('-1', 'Must be between 0 and 100.'),
			array('101', 'Must be between 0 and 100.'),
			
			// custom ranges
			array('4', 'Must be between 5 and 10.', array(5, 10, false)),
			array('6', 6, array(5, 10, false)),
			array('12', 'Must be between 5 and 10.', array(5, 10, false)),
			
			// bad data - inherits from Integer
			array('one', 'Must be a whole number.'),
		);
	}

	public function html_data() {
		return array(
			array('', '<input value="" maxlength="25" size="10" class="phorm_field_range" type="text" />'),
			
			// can be a rendered as a HTML5 slider
			array('', '<input value="" type="range" min="0" max="100" maxlength="25" size="10" class="phorm_field_range" />', array(0, 100, true)),
		);
	}

}