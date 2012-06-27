<?php
require_once "FieldTest.php";

class MultipleChoiceFieldTest extends FieldTest {

	protected $field = "Phorm_Field_MultipleChoice";

	protected $defaults = array('choices' => array('1' => 'One', '2' => 'Two', '3' => 'Three', '4' => 'Four', '5' => 'Five'), 'widget' => 'Phorm_Widget_SelectMultiple');

	protected $blank_input = array();
	
	function validation_data() {
		return array(
			array(array('1'), array('1')),
				
			array('3', 'Invalid selection.'),
			array(array('1', '6'), 'Invalid selection.'),
		);
	}

	function html_data() {
		return array(
			array(array('2'), <<< EOF
<select multiple="multiple" class="phorm_field_multiplechoice" ><option value="1" >One</option>
<option value="2" selected="selected" >Two</option>
<option value="3" >Three</option>
<option value="4" >Four</option>
<option value="5" >Five</option>
</select>
EOF
				),
			
			array(array('2', '4'), <<< EOF
<select multiple="multiple" class="phorm_field_multiplechoice" ><option value="1" >One</option>
<option value="2" selected="selected" >Two</option>
<option value="3" >Three</option>
<option value="4" selected="selected" >Four</option>
<option value="5" >Five</option>
</select>
EOF
				),
		);
	}

}