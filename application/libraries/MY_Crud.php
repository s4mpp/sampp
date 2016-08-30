<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class MY_Crud {

	//Mensagens de sucesso e erro, caso não sejam informadas
	private $success_message = 'Operação realizada com sucesso.';
	private $error_message = 'Falha na operação. Verifique';

	private $last_id;

	function __construct() {
		//Pega a instância do CI
		$this->CI =& get_instance();
	}

	//Configura mensagem de sucesso		
	public function setSuccessMsg($msg) {
		$this->success_message = $msg;
	}
	
	//Configura mensagem de erro	
	public function setErrorMsg($msg) {
		$this->error_message = $msg;
	}
	

	// Faz a alteração no banco
	// $tbl (string)		= a tabela a ser gravada. Ex.: 'usuarios'
	// $register (array) 	= array com os registros a serem gravados. Ex: array('nome' => 'Samuel', 'sobrenome' => 'Pacheco');
	// $pk (int)			= id do registro a ser alterado. Ex.: 1; Se não for informado, criará um novo registro
	public function save($tbl, $register, $pk = null) {

		//Verifica se foi informada a primary key (id)
		if($pk) {
			//Salva o registro
			$this->CI->db->where('id', $pk);
			$save = $this->CI->db->update($tbl, $register);
			$this->last_id = $pk;
		} else {

			//Grava o usuario e a data_hora_add
			$register['usuario'] = $this->CI->session->userdata('id');
			$register['data_hora_add'] = date('Y-m-d H:i');


			//Ou insere um novo
			$save = $this->CI->db->insert($tbl, $register);
			$this->last_id = $this->CI->db->insert_id($tbl);
		}

		//Verifica se a gravação foi bem-sucedida
		if($save) {
			//Mostra mensagem de sucesso
			return array('message' => array('success', $this->success_message), 'last_id' => encode($this->last_id));
		} else {
			//Ou mensagem de erro
			return array('errors'=> array($this->error_message));
		}
        
	}

	
	// Faz a exclusão de um registro no banco
	// $tbl (string) 	= a tabela
	// $pk (int)		= id do registro a ser excluido.
	public function delete($tbl, $pk) {
		if($this->CI->db->delete($tbl, array($pk[0] => $pk[1]))) {
			//Cria mensagem de sucesso na sessao
			$this->setMessage($this->success_message);
		} else {
			//Cria mensagem de erro na sessão
			$this->setMessage($this->error_message);
		}
	}

	// Faz uma consulta de um campo no banco
	// $tbl (string) 	= a tabela
	// $fields (string)	= campos a serem retornados
	// $like (array)		= condição do registro a ser retornado. se nao for informada, retornara todos os registros
	public function search($tbl, $fields, $like = null) {
		$this->CI->db->select($fields);
		$this->CI->db->from($tbl);
		$this->CI->db->like($like[0], $like[1]);
		
		return $this->CI->db->get()->result();
		
	}
}