<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class MY_Pagination {

	private $per_page;
	private $total_pags;
	private $pag_atual;
	private $offset;

	function __construct() {
		$this->per_page = 15;
	}

	function getOffset() {
		return ($this->getPagAtual() - 1) * $this->per_page;
	}

	function getLimit() {
		return $this->per_page;
	}

	function setTotalPags($rows) {
		$this->total_pags = (int)ceil($rows / $this->per_page);
	}

	function getTotalPags() {
		return $this->total_pags;
	}

	function getPagAtual() {
		$CI =& get_instance();
		return ($CI->uri->segment(4)) ? $CI->uri->segment(4) : 1;
	}


	function getPath($pag){
		$CI =& get_instance();
		$url = $CI->uri->slash_segment(1) . $CI->uri->slash_segment(2). 'pagination/'.$pag;

		return base_url($url).'?'.http_build_query($_GET);
	}
}