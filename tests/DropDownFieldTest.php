<?php
require_once "FieldTest.php";

class DropDownFieldTest extends FieldTest {
	
	protected $field = "Phorm_Field_DropDown";
	
	protected $defaults = array('choices' => array('1' => 'One', '2' => 'Two', '3' => 'Three'));
	
	function validation_data() {
		return array(
			array('1', '1'),
			
			array('', 'This field is required.', $this->defaults + array('validators' => array('required'))),
			array('4', 'Invalid selection.'),
		);
	}
	
	function html_data() {
		return array(
			array('2', '<select  class="phorm_field_dropdown" ><option  value="1" >One</option>
<option  value="2" selected="selected" >Two</option>
<option  value="3" >Three</option>
</select>'),
		);
	}
	
}