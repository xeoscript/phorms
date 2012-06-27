<?php
/**
 * @package Phorms
 * @subpackage Fields
 */
/**
 * Phorm_Field_IPv4Address
 *
 * An IP address field that can valdiate against subnets.
 *
 * @author Tris Forster
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @package Phorms
 * @subpackage Fields
 */
class Phorm_Field_IPv4Address extends Phorm_Field_Text {
	
	/**
	 * The subnet to validate against
	 * @var string
	 */
	private $subnet;
	
	/**
	 * Whether to return a long rather than the string
	 * @var boolean
	 */
	private $as_long;
	
	/**
	 * List of private ip ranges
	 * @var array
	 */
	static $private_subnets = array('10.0.0.0/8', '172.16.0.0/12', '192.168.0.0/16');

	/**
	 * @param string $label the field's text label
	 * @param string $subnet a subnet to valdiate against e.g. 10.0.0.0/24
	 * @param boolean $as_long use a long as the native type instead of string
	 * @param array $validators a list of callbacks to validate the field data
	 * @param array $attributes a list of key/value pairs representing HTML attributes
	 */
	public function __construct($label, $subnet=null, $as_long=false, $validators=array(), $attributes=array()) {
		$this->subnet = $subnet;
		$this->as_long = $as_long;
		
		if ( Phorm_Phorm::$html5 ) {
			$attributes['placeholder'] = 'xxx.xxx.xxx.xxx';
		}
		
		parent::__construct($label, 20, 15, $validators, $attributes);
	}

	/**
	 * Validates that the value is parsable as an IP address.  If a subnet was provided
	 * then it is validated against that subnet as well.
	 *
	 * @param string $value
	 * @return null
	 * @throws Phorm_ValidationError
	 */
	public function validate($value) {
		// do a basic string test
		if( !preg_match( '/^\d+\.\d+\.\d+\.\d+$/', $value ) )
		{
			throw new Phorm_ValidationError('field_invalid_ipv4address');
		}
		
		// convert to long
		$ip_value = ip2long($value); 
		if($ip_value===false)
		{
			throw new Phorm_ValidationError('field_invalid_ipv4address');
		}
		
		// check against allowed subnets
		if( $this->subnet ) {
			if( !self::ip_check_cidr($value, $this->subnet) ) {
				throw new Phorm_ValidationError(serialize(array('field_invalid_ipv4address_range', $this->subnet)));
			}
		}
	}

	/**
	 * Parses string as IP address, or long if $as_long was set to true
	 * 
	 * @param string $value
	 * @return string|long
	 */
	public function import_value($value) {
		if( $this->as_long ) {
			return ip2long(parent::import_value($value));
		}
		return parent::import_value($value);
	}

	/**
	 * Converts from long to string if $as_long was set to true
	 * 
	 * @param string|long $value
	 * @return string
	 */
	public function export_value($value) {
		if( $this->as_long ) {
			return long2ip($value);
		}
		return $value;
	}
	
	/**
	 * Checks whether an IP address is within the given CIDR
	 * 
	 * @param unknown_type $ip e.g. '10.23.4.65'
	 * @param unknown_type $cidr e.g. '10.23.0.0/16'
	 * @throws Exception if the CIDR is un-parseable
	 * @return boolean
	 */
	public static function ip_check_cidr($ip, $cidr) {
		list($net, $mask) = explode('/', $cidr);
		
		$ip_net = ip2long($net);
		if(!$ip_net) throw new Exception("Unabled to parse CIDR IP: {$cidr}");
		
		if($mask < 0 || $mask > 32) throw new Exception("Unabled to parse CIDR mask: {$cidr}");
		$ip_mask = ~((1 << (32 - $mask)) - 1);
		
		$ip_ip = ip2long($ip);
		
		return ( ($ip_ip & $ip_mask) == $ip_net );
	}
	
	/**
	 * Checks an IP address against a list of subnets
	 * Returns the first matching CIDR or false if not found
	 * 
	 * @param string $ip IP address to check
	 * @param array $subnets list of CIDRs
	 */
	public static function ip_check_subnets($ip, $subnets) {
		foreach( $subnets as $cidr) {
			if( self::ip_check_cidr($ip, $cidr) ) {
				return $cidr;
			}
		}
		return false;
	}
	
	/**
	 * Validator - ensures IP is public
	 * 
	 * @param string $value
	 * @throws Phorm_ValidationError
	 */
	public function validate_not_private_subnet($value) {
		$match = self::ip_check_subnets($value, self::$private_subnets);
		if( $match ) {
			throw new Phorm_ValidationError("May not be in range {$match}.");
		}
	}
	
	/**
	 * Validator - ensures IP is in one of the private ranges
	 * 
	 * @param string $value
	 * @throws Phorm_ValidationError
	 */
	public function validate_private_subnet($value) {
		$match = self::ip_check_subnets($value, self::$private_subnets);
		if( !$match ) {
			throw new Phorm_ValidationError("Private subnet required.");
		}
	}
}