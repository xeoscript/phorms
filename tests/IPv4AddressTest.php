<?php

require_once "FieldTest.php";

class IPv4AddressTest extends FieldTest {
	
	protected $field = 'Phorm_Field_IPv4Address';
	
	protected $defaults = array('subnet' => null, 'as_long' => false);
	
	public function validation_data() {
		return array(
			array('172.16.0.1', '172.16.0.1'),
			
			// return long
			array('172.16.0.1', ip2long('172.16.0.1'), array(null, true)),
			
			// range filters
			array('10.0.0.45', '10.0.0.45', array('10.0.0.0/24', false)),
			array('192.168.0.1', 'IP must be in the range 10.0.0.0/24.', array('10.0.0.0/24', false)),
			
			// require public ip
			array('1.2.3.4', '1.2.3.4', $this->defaults + array('validators' => array('not_private_subnet'))),
			array('10.1.2.3', 'May not be in range 10.0.0.0/8.', $this->defaults + array('validators' => array('not_private_subnet'))),
			array('172.18.43.34', 'May not be in range 172.16.0.0/12.', $this->defaults + array('validators' => array('not_private_subnet'))),
			array('192.168.54.23', 'May not be in range 192.168.0.0/16.', $this->defaults + array('validators' => array('not_private_subnet'))),
			
			// require private ip
			array('1.2.3.4', 'Private subnet required.', $this->defaults + array('validators' => array('private_subnet'))),
			array('10.1.2.3', '10.1.2.3', $this->defaults + array('validators' => array('private_subnet'))),
			array('172.18.43.24', '172.18.43.24', $this->defaults + array('validators' => array('private_subnet'))),
			array('192.168.54.23', '192.168.54.23', $this->defaults + array('validators' => array('private_subnet'))),
			
			// bad data
			array('test', 'Invalid IP address.'),
			array('10.0.256.2', 'Invalid IP address.'),
			array('10.0.0.', 'Invalid IP address.'),
		);
	}
	
	public function html_data() {
		return array(
			array('', '<input value="" maxlength="15" size="20" class="phorm_field_ipv4address" type="text" />'),
		);
	}
	
}