<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Samppform extends MY_Controller {

	public function __construct() {
		parent::__construct(false);

		//Permite acesso somente ao administrador
		if($this->session->userdata('id') != 1) {
			redirect(base_url('dashboard'));
		}

		$this->load->model('Sampp_model');
	}

	public function index(){

		//Coloca o título
		$data['pageTitle'] = 'Gerador de formulários';

		$data['tabelas'] =  $this->Sampp_model->getTables();
		$data['submodulos'] = $this->Sampp_model->getSubmodules();

		$this->load->view('includes/header', $data);
		$this->load->view('includes/sidebar');
		$this->load->view('sampp/formularios');
		$this->load->view('includes/footer');
		$this->load->view('includes/js_load');
		$this->load->view('sampp/js_formulario');

	}


	public function formulario() {
		is_ajax();

		$tabela = $_GET['tabela'];
		$submodulo = $_GET['submodulo'];

		$data['campos'] = $this->Sampp_model->getColumns($tabela);
		$data['submodulos'] = $this->Sampp_model->getSubmodules();

		$this->load->view('sampp/formulario', $data);
		$this->load->view('includes/js_load');
	}


	public function gerar() {
		is_ajax();

		$campos = array(
			'field' => $this->input->post('field'),
			'label' => $this->input->post('label'),
			'tipo' => $this->input->post('tipo'),
			'mascara' => $this->input->post('mascara'),
			'get' => $this->input->post('get'),
			'tamanho' => $this->input->post('tamanho')
		);

		$modulo = explode('/', $this->input->post('submodulo'));
		$diretorio = $modulo[0];
		$submodulo = $modulo[1];
		$view_path = "application/modules/".$diretorio."/views/".$submodulo;
		$controller_path = "application/modules/".$diretorio.'/controllers/'.ucfirst($submodulo).'.php';
		$model_path = "application/modules/".$diretorio.'/models/'.ucfirst($submodulo).'_model.php';
		$controller_relatorio_path = "application/modules/relatorios/controllers/".ucfirst($submodulo).".php";

		$this->geraFormulario($campos, $view_path);
		$this->geraController($campos, $controller_path);
		$this->geraFormularioFiltro($campos, $view_path, $diretorio, $submodulo);
		$this->geraControllerRelatorio($campos, $controller_relatorio_path);
		$this->geraModel($campos, $model_path, $submodulo);

		$result['message'][] = array('success', 'Arquivo gravado com sucesso.');
	
		$this->output->set_content_type('text/plain')->set_output(json_encode($result));

	}

	private function geraModel($campos, $model, $submodulo) {

		$conteudo = file_get_contents($model);

		$filter_old = '
		if(!empty($filters[\'nome\'])) {$this->db->like(\''.$submodulo.'.nome\', $filters[\'nome\']);}
		if(!empty($filters[\'status\'])) {$this->db->where(\''.$submodulo.'.status\', $filters[\'status\']);}';

		$report_old = '
		if(isset($filters[\'dc_inicial\']) && isset($filters[\'dc_final\'])) {
			$params[] = [\'Data cadastro\', $filters[\'dc_inicial\'].\' a \'.$filters[\'dc_final\']];
		}

		if(isset($filters[\'status\'])) {
			$params[] = [\'Status\', $this->System_model->get_nome_status($filters[\'status\'])];
		}';

		$filter = null;
		$report = null;

		for($i=0; $i<count($campos['field']); $i++) {

		if($campos['mascara'][$i] == 'datepicker') {
			
		$filter .= '

		if(!empty($filters[\''.$campos['field'][$i].'_inicial\']) && !empty($filters[\''.$campos['field'][$i].'_final\'])) {$this->db->where(\'clientes.'.$campos['field'][$i].' BETWEEN \'.dateToMysql($filters[\''.$campos['field'][$i].'_inicial\']).\' AND \'.dateToMysql($filters[\''.$campos['field'][$i].'_final\']).\' 23:59:59\');}
		';
		
		} else if($campos['tipo'][$i] == 'text') {
		
		$filter .= '
		if(!empty($filters[\''.$campos['field'][$i].'\'])) {$this->db->like(\''.$submodulo.'.'.$campos['field'][$i].'\', $filters[\''.$campos['field'][$i].'\']);}';
		
		} else {

		$filter .= '
		if(!empty($filters[\''.$campos['field'][$i].'\'])) {$this->db->where(\''.$submodulo.'.'.$campos['field'][$i].'\', $filters[\''.$campos['field'][$i].'\']);}';
		}

			
		if($campos['mascara'][$i] != 'datepicker') {
		$report .= '

		if(isset($filters[\''.$campos['field'][$i].'\'])) {
			$params[] = [\''.$campos['label'][$i].'\', $filters[\''.$campos['field'][$i].'\']];
		}
		';
		} else {

		$report .= '

		if(isset($filters[\''.$campos['field'][$i].'_inicial\']) && isset($filters[\''.$campos['field'][$i].'_final\'])) {
			$params[] = [\''.$campos['label'][$i].'\', $filters[\''.$campos['field'][$i].'_inicial\'].\' a \'.$filters[\''.$campos['field'][$i].'_final\']];
		}
		';
		
		}

		}

		$filter .= '
		if(!empty($filters[\'status\'])) {$this->db->where(\''.$submodulo.'.status\', $filters[\'status\']);}';

	$report .= '
		if(isset($filters[\'status\'])) {
			$params[] = [\'Status\', $this->System_model->get_nome_status($filters[\'status\'])];
		}

		if(isset($filters[\'dc_inicial\']) && isset($filters[\'dc_final\'])) {
			$params[] = [\'Data cadastro\', $filters[\'dc_inicial\'].\' a \'.$filters[\'dc_final\']];
		}'

		;

		$conteudo = str_replace($filter_old, $filter, $conteudo);
		$conteudo = str_replace($report_old, $report, $conteudo);

		//controller
		$file = fopen($model, 'w');
		fwrite($file, $conteudo);
		fclose($file);

	}



	private function geraControllerRelatorio($campos, $controller_relatorio_path) {

		$conteudo = file_get_contents($controller_relatorio_path);

		$string_old = '
				array(\'Nome\', \'nome\', 40),
				array(\'Status\', \'status\', 40),
				array(\'Data cadastro\', \'data_hora_add\', 20),';

		$string = null;

		for($i=0; $i<count($campos['field']); $i++) {

			switch ($i) {
				case 0:
					$tam = 30;
					break;
				case 1:
					$tam = 25;
					break;
				case 2:
					$tam = 20;
					break;
				default:
					$tam = 'null';
					break;
			}

		$string .= '
				array(\''.$campos['label'][$i].'\', \''.$campos['field'][$i].'\', '.$tam.'),';
			
		}

		$string .= '
				array(\'Status\', \'status\', 10),
				array(\'Data cadastro\', \'data_hora_add\', 15),';


		$conteudo = str_replace($string_old, $string, $conteudo);

		//controller
		$file = fopen($controller_relatorio_path, 'w');
		fwrite($file, $conteudo);
		fclose($file);

	}

	private function geraFormularioFiltro($campos, $folder, $diretorio, $submodulo) {

$html = '<div class="row">';

for($i=0; $i<count($campos['field']); $i++) {

	$required = ($this->input->post($campos['field'][$i])) ? ' required' : '';

$html .= '
	<div class="'.$campos['tamanho'][$i].'">
		<div class="form-group">
			<label class="control-label">'.$campos['label'][$i].'</label>
			';

		if($campos['tipo'][$i] == 'text' && $campos['mascara'][$i] != 'datepicker') {

			$html .= '<input autocomplete="off" type="text" value="<?= getSearch(\''.$campos['field'][$i].'\') ?>"  name="'.$campos['field'][$i].'" class="'.$campos['mascara'][$i].' form-control input-sm">';

		} else if($campos['tipo'][$i] == 'text' && $campos['mascara'][$i] == 'datepicker') {

			$html .= '
			<div class="input-group">
				<input value="<?= getSearch(\''.$campos['field'][$i].'_inicial\') ?>" type="text" name="'.$campos['field'][$i].'_inicial" class="datepicker form-control input-sm" placeholder="dd/mm/aaaa" />
				<span class="input-group-addon" style="border-left: 0; border-right: 0;">até</span>
				<input value="<?= getSearch(\''.$campos['field'][$i].'_final\') ?>" type="text" name="'.$campos['field'][$i].'_final" class="datepicker form-control input-sm" placeholder="dd/mm/aaaa" />
			</div>';

		} else {
			
			$html .= '<select data-target="<?= base_url(\''.$campos['get'][$i].'\') ?>" name="'.$campos['field'][$i].'" class="select2_ajax form-control input-sm">
			</select>';

		}
	$html .=  '
		</div>
	</div>
	';
}

$html .= '
	<div class="col-md-3">
		<div class="form-group">
			<label class="control-label">Data cadastro</label>
			<div class="input-group">
				<input value="<?= getSearch(\'dc_inicial\') ?>" type="text" name="dc_inicial" class="datepicker form-control input-sm" placeholder="dd/mm/aaaa" />
				<span class="input-group-addon" style="border-left: 0; border-right: 0;">até</span>
				<input value="<?= getSearch(\'dc_final\') ?>" type="text" name="dc_final" class="datepicker form-control input-sm" placeholder="dd/mm/aaaa" />
			</div>
		</div>
	</div>
	<div class="col-md-2">
		<div class="form-group">
			<label class="control-label">Situação</label>
			<select class="select2 form-control input-sm" name="status">
				<option></option>
				<option <?= set_select(\'status\', getSearch(\'status\'), (getSearch(\'status\') === \'1\')) ?> value="1">Ativo</option>
				<option <?= set_select(\'status\', getSearch(\'status\'), (getSearch(\'status\') === \'0\')) ?> value="0">Inativo</option>
			</select>
		</div>
	</div>
</div>
<input type="hidden" name="model" value="'.$diretorio.'/'.ucfirst($submodulo).'_model">';


		//filtro
		$file = fopen($folder.'/filtro.php', 'w');
		fwrite($file, $html);
		fclose($file);


		}

	


	private function geraController($campos, $folder) {

		$conteudo = file_get_contents($folder);

		$save_old = '
			\'nome\' => $this->input->post(\'nome\'),
			\'status\' => $this->input->post(\'status\'),
			\'obs\' => $this->input->post(\'obs\'),';

	$validate_old = '
		$this->form_validation->set_rules(\'nome\', \'Nome\', \'required|alpha_spaces\');
		$this->form_validation->set_rules(\'status\', \'Status\', \'required|is_numeric\');';

		$save = null;
		$validate = null;

		for($i=0; $i<count($campos['field']); $i++) {

			$required = ($this->input->post($campos['field'][$i])) ? 'required' : '';

			if($campos['mascara'][$i] && $campos['mascara'][$i] != 'datepicker') {
			$save .= '
			\''.$campos['field'][$i].'\' => cleanInput($this->input->post(\''.$campos['field'][$i].'\')),';
			} else if($campos['mascara'][$i] == 'datepicker') {
			$save .= '
			\''.$campos['field'][$i].'\' => datePTtoEN($this->input->post(\''.$campos['field'][$i].'\')),';
			} else {
			$save .= '
			\''.$campos['field'][$i].'\' => $this->input->post(\''.$campos['field'][$i].'\'),';
			}

			if($required) {

		$validate .= '
		$this->form_validation->set_rules(\''.$campos['field'][$i].'\', \''.$campos['label'][$i].'\', \''.$required.'\');';
			}

		}

		$save .= '
			\'status\' => $this->input->post(\'status\'),
			\'obs\' => $this->input->post(\'obs\'),';

	$validate .= '
		$this->form_validation->set_rules(\'status\', \'Status\', \'required|is_numeric\');';

		$conteudo = str_replace($save_old, $save, $conteudo);
		$conteudo = str_replace($validate_old, $validate, $conteudo);

		//controller
		$file = fopen($folder, 'w');
		fwrite($file, $conteudo);
		fclose($file);

	}



	private function geraFormulario($campos, $folder) {
		$html = '<div class="row">';

for($i=0; $i<count($campos['field']); $i++) {

	$required = ($this->input->post($campos['field'][$i])) ? ' required' : '';

$html .= '
	<div class="'.$campos['tamanho'][$i].'">
		<div class="form-group">
			<label class="control-label">'.$campos['label'][$i].'</label>
			';

		if($campos['tipo'][$i] == 'text') {
			$html .= '<input autocomplete="off" type="text" value="<?= set_value(\''.$campos['field'][$i].'\', $registro[\''.$campos['field'][$i].'\']) ?>"  name="'.$campos['field'][$i].'" class="'.$campos['mascara'][$i].' form-control input-sm"'.$required.'>';
		} else {
			$html .= '<select data-target="<?= base_url(\''.$campos['get'][$i].'\') ?>" name="'.$campos['field'][$i].'" class="select2_ajax form-control input-sm"'.$required.'>
				<option value="<?= $registro[\''.$campos['field'][$i].'\'] ?>"><?= $registro[\'nome_'.$campos['field'][$i].'\'] ?></option>
			</select>';
		}
	$html .=  '
		</div>
	</div>
	';
}

$html .= '
	<div class="col-sm-3 col-md-2">
		<div class="form-group">
			<label class="control-label">Situação</label>
			<select class="select2 form-control input-sm" name="status">
				<option value="1" <?= set_select(\'status\', $registro[\'status\'], ($registro[\'status\'] == \'1\')) ?> >Ativo</option>
				<option value="0" <?= set_select(\'status\', $registro[\'status\'], ($registro[\'status\'] == \'0\')) ?> >Inativo</option>
			</select>
		</div>
	</div>

	<div class="col-xs-12">
		<div class="form-group">
			<label class="control-label">Obs.</label>
			<textarea class="form-control input-sm" name="obs"><?= set_value(\'obs\', $registro[\'obs\']) ?></textarea>
		</div>
	</div>
</div>';


		//form
		$file = fopen($folder.'/form.php', 'w');
		fwrite($file, $html);
		fclose($file);


		}
		
}