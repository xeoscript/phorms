<?php
echo "INCLUDED\n";
class Phorm_Field_IPv4Address extends Phorm_Field_Text {
	
	private $subnet;
	
	private $as_long;
	
	static $private_subnets = array('10.0.0.0/8', '172.16.0.0/12', '192.168.0.0/16');

	public function __construct($label, $subnet=null, $as_long=false, $validators=array(), $attributes=array()) {
		$this->subnet = $subnet;
		$this->as_long = $as_long;
		
		if ( Phorm_Phorm::$html5 ) {
			$attributes['placeholder'] = 'xxx.xxx.xxx.xxx';
		}
		
		parent::__construct($label, 20, 15, $validators, $attributes);
	}

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
			$match = self::ip_check_subnets($value, array($this->subnet));
			if( !$match ) {
				throw new Phorm_ValidationError(serialize(array('field_invalid_ipv4address_range', $this->subnet)));
			}
		}
	}

	public function import_value($value) {
		if( $this->as_long ) {
			return ip2long(parent::import_value($value));
		}
		return parent::import_value($value);
	}

	public function export_value($value) {
		if( $this->as_long ) {
			return long2ip($value);
		}
		return $value;
	}
	
	public static function ip_check_cidr($ip, $cidr) {
		list($net, $mask) = explode('/', $cidr);
		
		$ip_net = ip2long($net);
		if(!$ip_net) throw new Exception("Unabled to parse CIDR IP: {$cidr}");
		
		if($mask < 0 || $mask > 32) throw new Exception("Unabled to parse CIDR mask: {$cidr}");
		$ip_mask = ~((1 << (32 - $mask)) - 1);
		
		$ip_ip = ip2long($ip);
		
		return ( ($ip_ip & $ip_mask) == $ip_net );
	}
	
	public static function ip_check_subnets($ip, $subnets) {
		foreach( $subnets as $cidr) {
			if( self::ip_check_cidr($ip, $cidr) ) {
				return $cidr;
			}
		}
		return false;
	}
	
	public function validate_not_private_subnet($value) {
		$match = self::ip_check_subnets($value, self::$private_subnets);
		if( $match ) {
			throw new Phorm_ValidationError("May not be in range {$match}.");
		}
	}
	
	public function validate_private_subnet($value) {
		$match = self::ip_check_subnets($value, self::$private_subnets);
		if( !$match ) {
			throw new Phorm_ValidationError("Private subnet required.");
		}
	}
}