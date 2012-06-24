<?php
abstract class FieldTest extends PHPUnit_Framework_TestCase {
	
	protected $field = null;
	
	public $require = array('validators' => array('required'));
	
	/**
	 * @return array ($input, $result, [$constructor_args])
	 */
	abstract function validation_data();
	
	/**
	 * @dataProvider validation_data
	 */
	function testInputs($input, $result, $args=array()) {
		// every field has label as its first arg
		array_unshift($args, 'My Field');
		
		$rc = new ReflectionClass($this->field);
		$field = $rc->newInstanceArgs($args); /** @var Phorm_Field */
		$field->set_value($input);
		
		if($field->is_valid()) {
			$this->assertSame($result, $field->get_value());
		} else {
			$this->assertEquals($result, $field->errors(false));
		}
	}
}