<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gerar extends MY_Controller {

	//Chama o model principal
	public function __construct() { parent::__construct();

		
	}


	public function index() {

		//Tira o limite de memoria do php, pois ira processar muitos dados
		ini_set('memory_limit', '-1');

		//Pega os filtros informados
		$filtros = $this->input->post();

		//Verifica se os dados de configuração do relatorio foram informados e valida-os
		if(empty($filtros["nome_relatorio"]))	{$filtros["nome_relatorio"] = 	'RELATÓRIO';} 
		if(empty($filtros["ordem"]))			{$filtros["ordem"] 			= 	'id';} 
		if(empty($filtros["sentido"]))			{$filtros["sentido"] 		=	'ASC';} 
		if(empty($filtros["orientation"]))		{$filtros["orientation"] 	=	'P';} 
		

		//Pega o titulo
		$title = $filtros['nome_relatorio'];
		$orientation = $filtros['orientation'];

		//var_dump($filtros); exit();
		//Chama o model com os registros
		$this->load->model($this->input->post('model'), 'Relatorio');

		//Chama a library e seta o titulo
		$this->load->library('pdf', array('title' => $title, 'orientation' => $orientation));

		//Add a primeira pagina
		$this->pdf->addPage();

		//Gera o cabeçalho da tabela
		$this->rel = $this->pdf->gerar_header_tb_relatorio();

		//Pegas as variaveis com o conteudo
		$this->fields = $this->rel['fields'];
		$this->campos = $this->rel['campos'];
		

		//Pega os registros
		$this->registros = $this->Relatorio->report(array_filter($filtros));

		//Mostra os parametros
		$this->pdf->param_relatorios($filtros, $this->registros['params']);

		//Gera o conteudo
		$this->pdf->gerar_conteudo_relatorio($this->rel, $this->registros['registros']);
		
		
		//Gera o PDF
		$this->pdf->Output('relatorio.pdf', 0);
	}

	

}