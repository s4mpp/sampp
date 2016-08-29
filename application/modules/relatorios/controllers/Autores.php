<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Autores extends MY_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {

		$data['pageTitle'] = 'Relatório de Autores cadastrados';
		//Configuração padrao do relatorio
		$data['relatorio'] = array(
			//titulo padrao
			'titulo_relatorio' => $data['pageTitle'],
			//Campos padrao, com nome e um tamanho padrao inicial
			'campos' => array(
				array('Nome', 'nome', 40),
				array('Status', 'status', 40),
				array('Data cadastro', 'data_hora_add', 20),
			),
		);

		$data['view_filter'] = 'biblioteca/autores/filtro';

		//Chama as views
		$this->load->view('includes/header', $data);
		$this->load->view('includes/sidebar');
		$this->load->view('gerar');
		$this->load->view('includes/footer');
		$this->load->view('includes/js_load');
	}
}