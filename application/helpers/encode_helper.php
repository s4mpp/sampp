<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	function replaceEncode($text) {
		return str_replace(array('+','/','='), array('-','_','.'), $text);
	}

	function replaceDecode($key) {
		return str_replace(array('-','_','.'), array('+','/','='), $key);
	}

	function encode($str) {
		$CI =& get_instance();
		return replaceEncode($CI->encrypt->encode($str));
	}

	function decode($str) {
		$CI =& get_instance();
		return $CI->encrypt->decode(replaceDecode($str));
	}