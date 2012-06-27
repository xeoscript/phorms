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
	
	private static $jquery_date_format = array(
		// Day
		'd' => 'dd', 	// day of month, zero padded (06)
		'j' => 'd',		// day of month (6)
		'D' => 'D',		// short name (Mon)
		'I' => 'DD',	// full name (Monday)
		'S' => '',		// ordinal (st, nd, rd, th)
		'z' => 'o',		// day of year
		// Month
		'F' => 'MM',	// full name (January)
		'M' => 'M',		// short name (Jan)
		'm' => 'mm',	// numeric zero padded (01)	
		'n' => 'm',		// numeric (1)
		// Year
		'Y' => 'yy',	// 4 digit (2012)
		'y' => 'y',		// 2 digit (12)
		// epoch
		'U' => '@'		// unix timestamp
	);
	
	/**
	 * @param string $label the field's text label
	 * @param array $validators a list of callbacks to validate the field data
	 * @param array $attributes a list of key/value pairs representing HTML attributes
	 */
	public function __construct($label, $format='d/m/Y', array $validators=array(), array $attributes=array())
	{
		if( !empty(Phorm_Phorm::$html5) ) {
			/*
			 browser support is patchy we dont use the html5 type
			 pass 'type' => 'date|datetime|time' manually if required
			*/
			// record the data for jquery-ui datepicker (or other javascript)
			$jquery_format = strtr($format, self::$jquery_date_format);
			$attributes['data-format'] = $jquery_format;
			// add a hint
			$attributes['placeholder'] = str_replace('y', 'yy', $jquery_format);
		}
		
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
	
	/**
	 * Exports a unix timestamp to a date string in the correct format
	 * 
	 * @param int $value unix timestamp
	 * @return string
	 */
	public function export_value($value) {
		if(!$value) return '';
		return date($this->format, $value);
	}
}