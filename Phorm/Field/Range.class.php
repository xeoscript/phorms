<?php
class Phorm_Field_Range extends Phorm_Field_Integer {
	
	/**
	 * Minimum allowed value
	 * @var int
	 */
	private $min;
	
	/**
	 * Maximum allowed value
	 * @var unknown_type
	 */
	private $max;
	
	/**
	 * @param string $label the field's text label
	 * @param int $size field size
	 * @param int $min the minimum value allowed
	 * @param int $max the maximum value allowed
	 * @param array $validators a list of callbacks to validate the field data
	 * @param array $attributes a list of key/value pairs representing HTML attributes
	 */
	function __construct($label, $min, $max, $slider=true, $validators=array(), $attributes=array()) {
		$this->min = $min;
		$this->max = $max;
		if( Phorm_Phorm::$html5 && $slider && !isset($attributes['type']) ) {
			$attributes['type'] = 'range';
			$attributes['min'] = $min;
			$attributes['max'] = $max;
		}
		parent::__construct($label, 10, 25, $validators, $attributes);
	}
	
	/**
	 * Check the value is between min and max
	 * 
	 * @param string $value
	 * @throws Phorm_ValidationError
	 */
	function validate($value) {
		parent::validate($value);
		
		if( $value < $this->min || $value > $this->max) {
			throw new Phorm_ValidationError(serialize(array('field_invalid_range', $this->min, $this->max)));
		}
	}
}