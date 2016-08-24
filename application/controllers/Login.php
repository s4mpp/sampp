<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	//Tela principal
	public function index($action = null) {

		$this->title = 'Login no sistema';
		$data['pageTitle'] = 'Autenticação';

		if($action == 'sair') {
			//Remove a sessão
			session_destroy();
		}

		$this->load->view('includes/head', $data);
		$this->load->view('login');
		$this->load->view('includes/footer');

	}

	//Faz a validação dos dados recebidos no login
	public function validate() {

		is_ajax();
		$this->load->library('form_validation');

		//Faz a validação dos dados
		$this->form_validation->set_rules('login', 'Login', 'required');
		$this->form_validation->set_rules('senha', 'Senha', 'required');

		//Verifica se passou na validação
		if($this->form_validation->run()) {

			//Pega os dados recebidos via POST
			$login = $this->input->post('login');
			$senha = md5($this->input->post('senha'));

			//Faz a autenticação e coloca na variavel result
			$result = $this->auth($login, $senha);
		} else {
			//Senao, retorna os erros encontrados
			$result['message'] = $this->form_validation->error_array();
		}

		echo json_encode($result);

	}

	//Autentica os dados recebidos
	private function auth($login, $senha) {

		//Conecta ao model para verificar os dados
		$this->load->model('Login_model');
		$checkLogin = $this->Login_model->validar($login, $senha);

		//Processa o resultado
		if(!$checkLogin) {
			return array(
				'message' => array(['error', 'Login ou senha inválidos. Verifique.'])
			);
		} else if($checkLogin[0]->status == 0) {
			return array(
				'message' => array(['error', 'Login desativado. Não é possível entrar.'])
			);
		} else {
			$this->system->get_modules($checkLogin[0]->id);
			$this->session->set_userdata('id', $checkLogin[0]->id);
			$this->session->set_userdata('nome', $checkLogin[0]->nome);
			return array(
				'message' => array(['success', 'Fazendo login, aguarde...']),
				'redirect' => base_url('dashboard')
			);
		}
	}
}


