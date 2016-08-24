<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Security {

	public function verif_session(){

		$CI =& get_instance();
		if(!$CI->session->userdata('id')) {
			var_dump($CI->session->userdata());
			redirect(base_url('login')); exit;
		}
	}

	
	public function verif_permission($alloweds_url) {
		$CI =& get_instance();

		if(!in_array(uri_string(), $alloweds_url)) {

			$permissoes = $CI->session->userdata('permissoes');

			//Verifica a id do modulo que esta sendo acessado
			$CI->db->select('submodulos.id');
			$CI->db->from('submodulos');
			$CI->db->join('modulos', 'modulos.id = submodulos.modulo', 'inner');
			$CI->db->where('submodulos.controller', $CI->uri->segment(2));
			$CI->db->where('modulos.value', $CI->uri->segment(1));
			$CI->db->limit(1);
			$submodulo = $CI->db->get()->result()[0]->id;

			//Verificar se nao esta no array de permissoes
			if(!in_array($submodulo, $permissoes)) {
				//redirecionar para permissao negada
				redirect(base_url('permissaonegada')); exit;
			}
		}
	}
}