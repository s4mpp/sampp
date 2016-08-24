<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Alterarsenha extends MY_Controller {

	//Chama o model principal
	public function __construct() { 
		parent::__construct();	
	}

	//Cadastra ou altera um registro
	public function index() {
		
		$data['pageTitle'] = 'Alterar senha';

		$this->load->view('includes/header', $data);
		$this->load->view('includes/sidebar');
		$this->load->view('alterarsenha');
		$this->load->view('includes/footer');
	}


	//Faz a validação dos dados
	public function validate() {

		$this->load->library('form_validation');

		is_ajax();
		$registros = array(
			'senhaatual' => $this->input->post('senhaatual'),
			'novasenha' => $this->input->post('novasenha'),
			'novasenhaconfirm' => $this->input->post('novasenhaconfirm'),
		);

		
		//Faz a validação dos dados
		$this->form_validation->set_rules('senhaatual', 'Senha Atual', 'trim|required|callback__verif_senha');
		$this->form_validation->set_rules('novasenha', 'Nova Senha', 'trim|required');
		$this->form_validation->set_rules('novasenhaconfirm', 'Confirmação da Nova Senha', 'trim|required|matches[novasenha]');


		//Se passar na validação, faz a gravação
		if($this->form_validation->run()) {

			$this->db->where('id', $this->session->userdata('id'));
			$this->db->update('usuarios', array('senha' => md5($this->input->post('novasenha'))));

			$result['message'][] = array('success', 'Senha alterada com sucesso.');

		} else {
			//Senao, retorna os erros encontrados
			$result['message'] = $this->form_validation->error_array();
		}

		$this->output->set_content_type('text/plain')->set_output(json_encode($result));
	}

	//Verifica se a senha atual está correta
	public function _verif_senha($str) {
		$CI =& get_instance();
		$CI->db->select('id');
		$CI->db->where('id', $CI->session->userdata('id'));
		$CI->db->where('senha', md5($str));
		$CI->db->limit(1);

		if(count($CI->db->get('usuarios')->result()) == 0) {
			return FALSE;
			$this->form_validation->set_message('_verif_senha', 'Senha atual errada.');
		} else {
			return TRUE;
		}
	}

}