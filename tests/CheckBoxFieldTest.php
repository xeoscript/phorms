<?php
require_once "FieldTest.php";

class CheckBoxFieldTest extends FieldTest {
	
	protected $field = "Phorm_Field_CheckBox";
	
	function validation_data() {
		return array(
			array('on', true),
			array('', false),
			array('1', false),
		);
	}
	
	function html_data() {
		return array(
			array(true, '<input value="on" class="phorm_field_checkbox" type="checkbox" checked="checked" />'),
			array('on', '<input value="on" class="phorm_field_checkbox" type="checkbox" checked="checked" />'),
			
			array(false, '<input value="on" class="phorm_field_checkbox" type="checkbox" />'),
			array('', '<input value="on" class="phorm_field_checkbox" type="checkbox" />'),
			array('off', '<input value="on" class="phorm_field_checkbox" type="checkbox" />'),
		);
	}
	
}