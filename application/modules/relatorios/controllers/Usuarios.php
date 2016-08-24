<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuarios extends MY_Controller {


	public function index() {

		$data['pageTitle'] = 'Relatório de usuários cadastrados';
		//Configuração padrao do relatorio
		$data['relatorio'] = array(
			//titulo padrao
			'titulo_relatorio' => $data['pageTitle'],
			//Campos padrao, com nome e um tamanho padrao inicial
			'campos' => array(
				array('Nome', 'nome', 20),
				array('Data nasc.', 'dn'),
				array('Sexo', 'sexo', 10),
				array('Endereço', 'endereco', 30),
				array('Número', 'num_endereco'),
				array('Cidade', 'nome_cidade', 15),
				array('Bairro', 'bairro'),				
				array('Login', 'login', 10),
				array('Status', 'status'),
			),
		);

		$data['view_filter'] = 'cadastros/usuarios/filtro';

		//Chama as views
		$this->load->view('includes/header', $data);
		$this->load->view('includes/sidebar');
		$this->load->view('gerar');
		$this->load->view('includes/footer');
		$this->load->view('includes/js_load');
	}


}
