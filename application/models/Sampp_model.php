<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sampp_model extends CI_Model {

private function filter($filters = null) {

		$filters = ($filters) ? $filters : $_GET;

		if(!empty($filters['nome'])) {$this->db->like('modulos.nome', $filters['nome']);}
	}

	//Obtem uma listagem com paginação
	function list() {
		//Busca os dados
		$this->db->select('modulos.*');
		$this->db->from('modulos');
		$this->db->limit($this->my_pagination->getLimit());
		$this->db->offset($this->my_pagination->getOffset());
		$this->db->order_by('label');
		$this->filter();

		//Armazena os registros na variavel registros
		$registros = $this->db->get()->result();

		//Faz a contagem dos registros da tabela e armazena na variavel total
		$this->db->select('count(modulos.id) as total');
		$this->db->from('modulos');
		$this->filter();
		$total = $this->db->get()->result()[0]->total;

		$result = new StdClass();
		$result->registros = $registros;
		$result->total = $total;

		return $result;
	}

	//Obtem dados de um determinado registro
	function getModule($id) {
		$this->db->select('modulos.*');
		$this->db->from('modulos');
		$this->db->where('modulos.id', $id);
		$this->db->limit(1);

		return $this->db->get()->result_array()[0];
	}

	function getSubmodule($id) {
		$this->db->select('submodulos.*');
		$this->db->from('submodulos');
		$this->db->where('submodulos.id', $id);
		$this->db->limit(1);

		return $this->db->get()->result_array()[0];
	}


	function getSubmodulesByModule($module) {
		$this->db->select('submodulos.*');
		$this->db->from('submodulos');
		$this->db->where('submodulos.modulo', $module);

		return $this->db->get()->result();
	}


}