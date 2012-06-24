<?php if(!defined('PHORMS_ROOT')) { die('Phorms not loaded properly'); }
/**
 * @package Phorms
 * @subpackage Fields
 */
/**
 * Phorm_Field_DateTime
 *
 * A text field that accepts a custom date/time format.
 *
 * @author Jeff Ober <jeffober@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @package Phorms
 * @subpackage Fields
 */
class Phorm_Field_DateTime extends Phorm_Field_Text
{

	/**
	 * Date format
	 * @var string
	 */
	private $format;
	
	/**
	 * @param string $label the field's text label
	 * @param array $validators a list of callbacks to validate the field data
	 * @param array $attributes a list of key/value pairs representing HTML attributes
	 */
	public function __construct($label, $format='d/m/Y', array $validators=array(), array $attributes=array())
	{
		parent::__construct($label, 10, 100, $validators, $attributes);
		$this->format = $format;
	}

	/**
	 * Validates that the value is parsable as a date/time value.
	 *
	 * @param string $value
	 * @return null
	 * @throws Phorm_ValidationError
	 */
	public function validate($value)
	{
		parent::validate($value);

		$value = strtr($value, '-', '/');
		
		// do parse as validation
		$d = DateTime::createFromFormat('!' . $this->format, $value);
		
		// should just be able to do this
		if(!$d) throw new Phorm_ValidationError('field_invalid_datetime_format');
		
		// but unfortunately createFromFormat doesn't actually fail if partially parseable
		$e = $d->getLastErrors();
		if( $e['warning_count'] != 0 ) {
			throw new Phorm_ValidationError('field_invalid_datetime_format');
		}
		
		// need to check it is convertable
		$ts = $d->getTimeStamp();
		if($ts == false) throw new Phorm_ValidationError('field_invalid_datetime_timestamp');
	}

	/**
	 * Imports the value and returns a unix timestamp (the number of seconds
	 * since the epoch.)
	 *
	 * @param string $value
	 * @return int the date/time as a unix timestamp
	 */
	public function import_value($value)
	{
		if( $value === '' ) return null;
		$value = strtr(parent::import_value($value), '-', '/');
		$d = DateTime::createFromFormat('!' . $this->format, $value);
		return $d->getTimestamp();
	}
}