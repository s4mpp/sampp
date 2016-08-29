<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	public function __construct() {
		parent::__construct(false);
	}

	public function index() {

		$data['pageTitle'] = 'Dashboard';

		$this->load->view('includes/header', $data);
		$this->load->view('includes/sidebar');
		$this->load->view('dashboard');
		$this->load->view('includes/footer');
		$this->load->view('js_dashboard');
	}
}