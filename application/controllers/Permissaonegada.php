<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permissaonegada extends MY_Controller {

	//Chama o model principal
	public function __construct() {
		parent::__construct(false);

	}
	
	public function index() {

		if($this->input->is_ajax_request()) {
			$this->load->view('text_permissao_negada');
		} else {

			$data['pageTitle'] = 'Permissão negada';

			$this->load->view('includes/header', $data);
			$this->load->view('includes/sidebar');
			$this->load->view('permissao-negada');
			$this->load->view('includes/footer');
		}
	}
}
?>