<?php 
 
class MY_Controller extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->title = 'Sampp';

		//Colos as permissoes na sessão
		$modules_nav = $this->session->userdata('modules_nav');
		$this->menu_itens = ($modules_nav) ? $modules_nav : array();

		//Permite urls sem fazer validação de permissão
		$alloweds_url = array(
 			'permissaonegada',
 			'dashboard',
 			'alterarsenha',
 			'alterarsenha/validate',
 			'relatorios/gerar',
 		);

 		$this->load->library('my_security');

 		$this->my_security->verif_session();
 		$this->my_security->verif_permission($alloweds_url);
 	}


 	public function search_cidades() {

 		is_ajax();
 		$q = $_REQUEST['q'];

		$items = $this->System_model->search_cidades($q);

		$result['total_count'] = count($items);
		$result['incomplete_results'] = false;
		$result['items'] = $items;

		$this->output->set_content_type('text/plain')->set_output(json_encode($result));
 	}
}