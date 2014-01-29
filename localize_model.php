<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Localize_model extends CI_Model
{
	
	var $registered = array();
	var $extra = array();
	
	function __construct()
	{
		parent::__construct();
		

	}
	
	public function pre_localize($arr){
		pre($arr);
	}
	
	function get_data( $handle, $key ) {
		if ( !isset( $this->registered[$handle] ) )
			return false;

		if ( !isset( $this->registered[$handle]->extra[$key] ) )
			return false;

		return $this->registered[$handle]->extra[$key];
	}	

	function add_data( $name, $data ) {
		if ( !is_scalar($name) )
			return false;
		$this->extra[$name] = $data;
		return true;
	}	
	
	
	function localize( $handle, $object_name, $l10n ) {
		if ( $handle === 'jquery' )
			$handle = 'jquery-core';
		
		if ( is_array($l10n) && isset($l10n['l10n_print_after']) ) { // back compat, preserve the code in 'l10n_print_after' if present
			$after = $l10n['l10n_print_after'];
			unset($l10n['l10n_print_after']);
		}
	
		foreach ( (array) $l10n as $key => $value ) {
			if ( !is_scalar($value) )
				continue;

			$l10n[$key] = html_entity_decode( (string) $value, ENT_QUOTES, 'UTF-8');
		}
		
		$script = "var $object_name = " . json_encode($l10n) . ';';

		if ( !empty($after) )
			$script .= "\n$after;";

		$data = $this->get_data( $handle, 'data' );
		
		if ( !empty( $data ) )
			$script = "$data\n$script";
		
		

		return $script;
	}	
	
	
}
?>