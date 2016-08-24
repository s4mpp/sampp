<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Instalacao_model extends CI_Model {

	function getInstalacao($id) {
		$this->db->select('instalacao.*, municipios.nome as nomeCidade');
		$this->db->from('instalacao');
		$this->db->where('instalacao.id', $id);
		$this->db->join('municipios', 'instalacao.cidade = municipios.id', 'left');
		$this->db->limit(1);

		return $this->db->get()->result_array()[0];
	}

}