<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Instalacao extends MY_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {

		$data['pageTitle'] = 'Instalação';

		$this->load->view('includes/header', $data);
		$this->load->view('includes/sidebar');
		$this->load->view('instalacao/cadastro');
		$this->load->view('includes/footer');
	}

	//Carrega as abas do registro
	public function tabs() {

		$this->load->model('Instalacao_model');

		is_ajax();
		$data['instalacao']['registro'] = $this->Instalacao_model->getInstalacao(1);

		$this->load->view('instalacao/tabs', $data);
		$this->load->view('includes/js_load');
	}

	//Valida os dados recebidos do formulário
	public function validate() {

		is_ajax();

		$this->load->library('form_validation');
	
		//Faz a validação dos dados
		$this->form_validation->set_rules('cnpj', 'CNPJ', 'required');
		$this->form_validation->set_rules('razao_social', 'Razão Social', 'required');
		$this->form_validation->set_rules('nome', 'Nome Fantasia', 'required');

		//Verifica se passou na validação
		if($this->form_validation->run()) {
			//Se passou, envia para a função de gravação
			$save = $this->save();

			//Pega a mensagem recebida
			$result['message'][] = $save['message'];

		} else {
			//Senao, retorna os erros encontrados
			$result['message'] = $this->form_validation->error_array();
		}

		$this->output->set_content_type('text/plain')->set_output(json_encode($result));

	}

	//Salva os registros recebidos
	private function save() {

		//Inicia a library MY_Crud
		$this->load->library('my_crud');

		//Pega os registros
		$registros = array(
			'cnpj' => $this->input->post('cnpj'),
			'inscricao_municipal' => $this->input->post('inscricao_municipal'),
			'razao_social' => $this->input->post('razao_social'),
			'inscricao_estadual' => $this->input->post('inscricao_estadual'),
			'nome' => $this->input->post('nome'),
			'cidade' => $this->input->post('cidade'),
			'bairro' => $this->input->post('bairro'),
			'endereco' => $this->input->post('endereco'),
			'num_endereco' => $this->input->post('num_endereco'),
			'cep' => $this->input->post('cep'),
		);

		//Proteção contra XSS
		$registros = $this->security->xss_clean($registros);

		//Cria mensagem de sucsso
		$this->my_crud->setSuccessMsg('Dados gravados com sucesso.');
		
		//Retorna a library de gravação (success / error com mensagem)
		return $this->my_crud->save('instalacao', $registros, 1);
			
	}
}