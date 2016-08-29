<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuarios_model extends CI_Model {

private function filter($filters = null) {

		$filters = ($filters) ? $filters : $_GET;

		if(!empty($filters['nome'])) {$this->db->like('usuarios.nome', $filters['nome']);}
		if(!empty($filters['login'])) {$this->db->like('usuarios.login', $filters['login']);}
		if(!empty($filters['status'])) {$this->db->where('usuarios.status', $filters['status']);}
		if(!empty($filters['sexo'])) {$this->db->where('usuarios.sexo', $filters['sexo']);}
		if(!empty($filters['cidade'])) {$this->db->where('municipios.id', $filters['cidade']);}

		if(!empty($filters['dn_inicial']) && !empty($filters['dn_final'])) {$this->db->where("usuarios.dn BETWEEN '".dateToMysql($filters['dn_inicial'])."' AND '".dateToMysql($filters['dn_final'])."'");}

		if(!empty($filters['dc_inicial']) && !empty($filters['dc_final'])) {$this->db->where("usuarios.data_hora_add BETWEEN '".dateToMysql($filters['dc_inicial'])."' AND '".dateToMysql($filters['dc_final'])."'");}
		
	}

	private function join() {
		$this->db->join('status', 'status.id = usuarios.status');
	}

	//Obtem uma listagem com paginação
	function list() {
		//Busca os dados
		$this->db->select('usuarios.*, status.descricao as status');
		$this->db->from('usuarios');
		$this->db->limit($this->my_pagination->getLimit());
		$this->db->offset($this->my_pagination->getOffset());
		$this->join();
		$this->db->order_by('nome');
		$this->filter();

		//Armazena os registros na variavel registros
		$registros = $this->db->get()->result();

		//Faz a contagem dos registros da tabela e armazena na variavel total
		$this->db->select('count(usuarios.id) as total');
		$this->db->from('usuarios');
		$this->join();
		$this->filter();
		$total = $this->db->get()->result()[0]->total;

		$result = new StdClass();
		$result->registros = $registros;
		$result->total = $total;

		return $result;
	}

	//Obtem dados de um determinado registro
	function getOne($id) {
		$this->db->select('usuarios.*, municipios.nome as nomeCidade');
		$this->db->from('usuarios');
		$this->db->where('usuarios.id', $id);
		$this->db->join('municipios', 'usuarios.cidade = municipios.id', 'left');
		$this->db->limit(1);

		return $this->db->get()->result_array()[0];
	}


	//Gera dados para o relatorio
	public function report($filters) {

		$this->db->select("usuarios.*, date_format(dn, '%d/%m/%Y') as dn, municipios.nome as nome_cidade, status.descricao as status");
		$this->db->from('usuarios');
		$this->db->join('municipios', 'municipios.id = usuarios.cidade', 'left');
		$this->join();
		$this->filter($filters);
		$this->db->order_by('usuarios.'.$filters['ordem'], $filters['sentido']);

		$registros = $this->db->get()->result_array();


		if(isset($filters['dn_inicial']) && isset($filters['dn_final'])) {
			$params[] = ['Data nasc', $filters['dn_inicial'].' a '.$filters['dn_final']];
		}

		if(isset($filters['dc_inicial']) && isset($filters['dc_final'])) {
			$params[] = ['Data cadastro', $filters['dc_inicial'].' a '.$filters['dc_final']];
		}

		if(isset($filters['da_inicial']) && isset($filters['da_final'])) {
			$params[] = ['Data cadastro', $filters['da_inicial'].' a '.$filters['da_final']];
		}

		if(isset($filters['dd_inicial']) && isset($filters['dd_final'])) {
			$params[] = ['Data cadastro', $filters['dd_inicial'].' a '.$filters['dd_final']];
		}

		if(isset($filters['sexo'])) {
			$params[] = ['Sexo', $filters['sexo']];
		}

		if(isset($filters['cidade'])) {
			$params[] = ['Cidade', $this->System_model->getNomeCidade($filters['cidade'])];
		}

		if(isset($filters['status'])) {
			$params[] = ['Status', $this->System_model->get_nome_status($filters['status'])];
		}

		$params['total'] = ['TOTAL', number_format(count($registros), 0, '', '.') .' registro(s)'];


		return array(
			'registros' => $registros,
			'params' => $params
		);
		
	}
}