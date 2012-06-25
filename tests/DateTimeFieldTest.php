<?php
require_once "FieldTest.php";

class DateTimeFieldTest extends FieldTest {
	
	protected $field = "Phorm_Field_DateTime";
	
	protected $defaults = array('format' => 'd/m/Y');
	
	private $tz = +10;
	
	function validation_data() {
		$offset = $this->tz * 60 * 60;
		
		return array(
			array('24/6/2012', 1340496000 - $offset),
			array('1/1/1970', 0 - $offset),
			
			// dashed input
			array('24-06-2012', 1340496000 - $offset),
			
			// alternate formats
			array('6/24/2012', 1340496000 - $offset, array('m/d/Y')),
			
			// blank input
			array('', null),
			
			// rubish input
			array('Monday week', 'Date/time format not recognized.'),
			// backward date (American format)
			array('06/24/2012', 'Date/time format not recognized.'),
			// too many days
			array('30/02/2012', 'Date/time format not recognized.'),
			// date out of timestamp range
			array('12/5/546', 'Date/time outside unix timestamp range'),
		);
	}
	
	function html_data() {
		return array(
			array('24/6/2012', '<input value="24/6/2012" maxlength="100" size="10" class="phorm_field_datetime" type="text" />'),
		);
	}
	
}