<?php
require_once "FieldTest.php";

class DateTimeFieldTest extends FieldTest {
	
	protected $field = "Phorm_Field_DateTime";
	
	private $tz = +10;
	
	function validation_data() {
		$defaults = array('format' => 'd/m/Y');
		
		$offset = $this->tz * 60 * 60;
		
		return array(
			array('24/6/2012', 1340496000 - $offset, $defaults),
			array('1/1/1970', 0 - $offset, $defaults),
			
			// dashed input
			array('24-06-2012', 1340496000 - $offset, $defaults),
			
			// alternate formats
			array('6/24/2012', 1340496000 - $offset, array('m/d/Y')),
			
			// blank input
			array('', null, $defaults),
			
			// rubish input
			array('Monday week', 'Date/time format not recognized.', $defaults),
			// backward date (American format)
			array('06/24/2012', 'Date/time format not recognized.', $defaults),
			// too many days
			array('30/02/2012', 'Date/time format not recognized.', $defaults),
			// date out of timestamp range
			array('12/5/546', 'Date/time outside unix timestamp range', $defaults),
		);
	}
	
}