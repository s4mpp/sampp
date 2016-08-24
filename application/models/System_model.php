<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class System_model extends CI_Model {

	function get_permissoes_usuario($id) {
		$this->db->select('submodulo');
		$this->db->from('permissoes');
		$this->db->where('usuario', $id);

		return $this->db->get()->result();
	}


	function get_modules() {
		$this->db->select('modulos.id, modulos.value, modulos.label, modulos.icone');
		$this->db->from('modulos');
		$this->db->order_by('modulos.label');
		return $this->db->get()->result();
	}


	function get_submodules($modulo) {
		$this->db->select('submodulos.id, submodulos.controller, submodulos.metodo_principal, submodulos.label');
		$this->db->from('submodulos');
		$this->db->order_by('submodulos.label');
		$this->db->where('submodulos.modulo', $modulo);
		return $this->db->get()->result();
	}

	function get_all_submodules($modulo) {
		$this->db->select('submodulos.id, submodulos.label');
		$this->db->from('submodulos');
		$this->db->order_by('submodulos.label');
		$this->db->where('submodulos.modulo', $modulo);
		return $this->db->get()->result();
	}


	function search_cidades($q) {
		$this->db->select('municipios.id, municipios.nome');
		$this->db->from('municipios');
		$this->db->like('municipios.nome', $q);
		$this->db->order_by('nome');

		return $this->db->get()->result_array();
	}

	function get_nome_status($id) {
		$this->db->select('status.id, status.descricao');
		$this->db->from('status');
		$this->db->where('status.id', $id);

		return $this->db->get()->result_array()[0]->descricao;
	}

}