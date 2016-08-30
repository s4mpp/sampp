<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sampp extends MY_Controller {

	public function __construct() {
		parent::__construct(false);

		//Permite acesso somente ao administrador
		if($this->session->userdata('id') != 1) {
			redirect(base_url('dashboard'));
		}

		$this->load->model('Sampp_model');
	}

	//Faz a listagem dos registros
	public function listar(int $pag = 1){

		//Chama a library de paginação
		$this->load->library('my_pagination');

		//Coloca o título
		$data['pageTitle'] = 'Módulos cadastrados';

		$data['registros'] = $this->Sampp_model->list($pag);
		$this->my_pagination->setTotalPags($data['registros']->total);

		$this->load->view('includes/header', $data);
		$this->load->view('includes/sidebar');
		$this->load->view('sampp/lista');
		$this->load->view('includes/footer');
		$this->load->view('includes/js_load');

	}


	//Pagina principal do cadastro
	public function cadastro(string $id) {

		$data['pageTitle'] = 'Sampp';

		$this->load->view('includes/header', $data);
		$this->load->view('includes/sidebar');
		$this->load->view('sampp/cadastro');
		$this->load->view('includes/footer');
		
	}

	//Carrega as abas do registro
	public function tabs(string $id) {

		is_ajax();

		$id = decode($id);
		$data['funcionario']['registro'] = $this->Sampp_model->getModule($id);
		$data['submodulos']['registros'] = $this->Sampp_model->getSubmodulesByModule($id);

		$this->load->view('sampp/tabs', $data);
		$this->load->view('includes/js_load');
	}


	//Carrega o formulário
	public function form_submodulo($id = null) {

		is_ajax();

		if($id) {
			$id = decode($id);
			$data['registro'] = $this->Sampp_model->getSubmodule($id);
		} else {
			$data['registro'] = null;
		}

		$this->load->view('sampp/form_submodulo', $data);
		$this->load->view('includes/js_load');
	}

	//Valida os dados recebidos do formulário
	public function validate($id = null) {

		is_ajax();
		$id = decode($id);

		$this->load->library('form_validation');
		
		//Faz a validação dos dados
		$this->form_validation->set_rules('label', 'Nome', 'required|alpha_spaces|unique[modulos.label.'.$id.']');
		$this->form_validation->set_rules('value', 'Diretório', 'required|alpha|unique[modulos.value.'.$id.']');
		$this->form_validation->set_rules('icone', 'Ícone', 'required|alpha_spaces');

		//Verifica se passou na validação
		if($this->form_validation->run()) {
			//Se passou, envia para a função de gravação
			$save = $this->save($id);

			//Pega a mensagem recebida
			$result['message'][] = $save['message'];

			//Se retornou a ultima id e nao foi informada a id no parametro,  se trata de uma gravação. Entao mandará o JS redirecionar para a pagina de cadastro
			if($save['last_id'] && !$id) {
				$result['redirect'] = base_url('sampp/cadastro/'.$save['last_id']);
			}

		} else {
			//Senao, retorna os erros encontrados
			$result['message'] = $this->form_validation->error_array();
		}

		$this->output->set_content_type('text/plain')->set_output(json_encode($result));

	}

	//Salva os registros recebidos
	private function save($id = null) {

		//Inicia a library MY_Crud
		$this->load->library('my_crud');

		//Pega os registros
		$registros = array(
			'label' => ucfirst($this->input->post('label')),
			'value' => strtolower($this->input->post('value')),
			'icone' => strtolower($this->input->post('icone')),
		);

		//Proteção contra XSS
		$registros = $this->security->xss_clean($registros);

		//Cria mensagem de sucsso
		$this->my_crud->setSuccessMsg('Módulo gravado com sucesso.');
		
		//Retorna a library de gravação (success / error com mensagem)
		return $this->my_crud->save('modulos', $registros, $id);
			
	}

	//Retorna uma listagem para ser mostrada no Select2
	public function search() {

		is_ajax();
		$q = $_REQUEST['q'];

		$items =$this->my_crud->search('usuarios', 'id, nome', array('nome' => $q));

		$result['total_count'] = count($items);
		$result['incomplete_results'] = false;
		$result['items'] = $items;

		$this->output->set_content_type('text/plain')->set_output(json_encode($result));
	
	}



	//Valida os dados recebidos do formulário
	public function validate_submodule($id = null) {

		is_ajax();
		$id = decode($id);

		$this->load->library('form_validation');
		
		//Faz a validação dos dados
		$this->form_validation->set_rules('label', 'Nome', 'required|unique[submodulos.label.'.$id.']');
		$this->form_validation->set_rules('controller', 'Controller', 'required|unique[submodulos.controller.'.$id.']');
		$this->form_validation->set_rules('metodo_principal', 'Método principal', 'alpha');
		$this->form_validation->set_rules('tabela', 'Tabela', 'unique[submodulos.tabela.'.$id.']');

		//Verifica se passou na validação
		if($this->form_validation->run()) {
			//Se passou, envia para a função de gravação
			$save = $this->save_submodule($id);

			//Pega a mensagem recebida
			$result['message'][] = $save['message'];

			$result['reload'] = '#cadastro';
			$result['tab_active'] = '#submodulos';

		} else {
			//Senao, retorna os erros encontrados
			$result['message'] = $this->form_validation->error_array();
		}

		$this->output->set_content_type('text/plain')->set_output(json_encode($result));

	}

	//Salva os registros recebidos
	private function save_submodule($id) {

		//Inicia a library MY_Crud
		$this->load->library('my_crud');

		//Pega os registros
		$registros = array(
			'label' => ucfirst($this->input->post('label')),
			'controller' => strtolower($this->input->post('controller')),
			'metodo_principal' => strtolower($this->input->post('metodo_principal')),
			'modulo' => decode($this->input->post('modulo')),
			'tabela' => $this->input->post('tabela'),
			'abas' => $this->input->post('abas'),
		);

		//Proteção contra XSS
		$registros = $this->security->xss_clean($registros);

		//Cria mensagem de sucsso
		$this->my_crud->setSuccessMsg('Submódulo gravado com sucesso.');
		
		//Retorna a library de gravação (success / error com mensagem)
		return $this->my_crud->save('submodulos', $registros, $id);
			
	}

	private function create_submodulo_relatorio($submodulo) {

		//Inicia a library MY_Crud
		$this->load->library('my_crud');

		//Pega os registros
		$registros = array(
			'label' => $submodulo->label,
			'controller' => $submodulo->controller,
			'modulo' => 2,
		);

		//Proteção contra XSS
		$registros = $this->security->xss_clean($registros);

		//Retorna a library de gravação (success / error com mensagem)
		$this->my_crud->save('submodulos', $registros);
		
		//Cria mensagem de sucsso
		return 'Submódulo de relatórios gravado com sucesso.';
	}

	//Compila a criação de modulos e submodulos
	public function compilar($id) {

		//Obtem os dados necessarios : modulo e submodulos
		$id = decode($id);
		$modulo = $this->Sampp_model->getModule($id);
		$submodulos = $this->Sampp_model->getSubmodulesByModule($id);


		//Cria os diretorios do modulo
		echo $this->create_module($modulo)."<br/>";


		echo '----------------------------------------------------<br/>';

		//Faz loop entre os submodulos
		foreach($submodulos as $submodulo) {
			//Cria os arquivos
			echo $this->create_submodule($submodulo, $modulo['value'])."<br/>";
			//Adiciona permissao
			echo $this->create_permission($submodulo->id)."<br/>";
			//Cria tabela no banco de dados
			echo $this->create_table($submodulo)."<br/>";
			//Cria submodulo em relatorios
			echo $this->create_submodulo_relatorio($submodulo)."<br/>";
			//Adiciona permissao
			echo $this->create_permission($this->db->insert_id())."<br/>";

			echo '----------------------------------------------------<br/>';
		}


		echo '<a href='.$_SERVER['HTTP_REFERER'].'>Voltar</a> | ';
		echo '<a target=_blank href='.base_url($modulo['value'].'/'.$submodulos[0]->controller).'>Ir para o módulo</a>';


		//Coloca os modulos na sessao novamente(permissoes)
		$this->system->get_modules(1);

	}

	private function create_table($submodulo) {
		
		if($submodulo->tabela) {
			//Campos
			$fields['id'] = array('type' => 'int', 'auto_increment' => true);
			$fields['nome'] = array('type' => 'varchar', 'constraint' => '55', 'null' => true);
			$fields['obs'] = array('type' => 'text', 'null' => true);
			$fields['status'] = array('type' => 'int', 'null' => false);
			$fields['usuario'] = array('type' => 'int');
			$fields['data_hora_add'] = array('type' => 'datetime');

			//Cria a tabela
			$this->load->dbforge();
			$this->dbforge->add_field($fields);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->create_table($submodulo->tabela, TRUE);
			
			return 'Criada tabela '.$submodulo->tabela;
		} else {
			return 'Não foi criada a tabela para o submodulo '.ucfirst($submodulo->label);
		}

		
	}

	//Cria permissao pro usuario administrador acessar os submodulos
	private function create_permission($submodulo) {
		
		//Verifica se o usuario ja tem permissao
		$verif_permission = $this->db->select('id')
			->from('permissoes')->where(array('submodulo' => $submodulo, 'usuario' => 1))
			->get()->result();
		
		//Se nao tiver, faz o cadastro
		if(count($verif_permission) == 0) {

			$permissao = array(
				'usuario' => 1,
				'submodulo' =>  $submodulo
			);
			$this->db->insert('permissoes', $permissao);

			return 'Criado permissão ao usuario administrador no submodulo.';

		//Se sim, retorna mensagem
		} else {
			return 'Já existe permissão ao usuario administrador no submodulo criado.';
		}
	}


	//Cria um modulo
	private function create_module($modulo) {

		$path = "application/modules/".$modulo['value'];

		if(is_dir($path)) {
			return 'Módulo '.$modulo['label'].' já existe';
		} else {
			mkdir($path, 0777);
			mkdir($path.'/views', 0777);
			mkdir($path.'/controllers', 0777);
			mkdir($path.'/models', 0777);

			return 'Módulo '.$modulo['label'].' criado com sucesso';
		}
	
	}


	//Cria um submodulo
	private function create_submodule($submodulo, $modulo_dir) {

		$path = "application/modules/".$modulo_dir."/views/".$submodulo->controller;

		if(is_dir($path)) {
			return 'Submódulo '.$submodulo->label.' já existe';
		} else {
			
			//Cria a pasta dos arquivos da view
			$viewFolder = "application/modules/".$modulo_dir."/views/".$submodulo->controller;
			mkdir($viewFolder, 0777);

			//Faz o caminho dos arquivos
			$formFile = "application/modules/".$modulo_dir."/views/form_".$submodulo->controller.".php";
			$modelFile = "application/modules/".$modulo_dir."/models/".ucfirst($submodulo->controller)."_model.php";
			$controllerFile = "application/modules/".$modulo_dir."/controllers/".ucfirst($submodulo->controller).".php";
			$controllerRelatorioFile = "application/modules/relatorios/controllers/".ucfirst($submodulo->controller).".php";

			//Cria o controller
			$controller = fopen($controllerFile, 'w');
			fwrite($controller, $this->layout_controller($submodulo, $modulo_dir));
			fclose($controller);

			//Cria o model
			$model = fopen($modelFile, 'w');
			fwrite($model, $this->layout_model($submodulo));
			fclose($model);

			//Cria o controller relatorios
			$controllerRelatorio = fopen($controllerRelatorioFile, 'w');
			fwrite($controllerRelatorio, $this->layout_controller_relatorio($submodulo, $modulo_dir));
			fclose($controllerRelatorio);

			//Cria as views
			//cadastro
			$view = fopen($viewFolder.'/cadastro.php', 'w');
			fwrite($view, $this->layout_cadastro($submodulo, $modulo_dir));
			fclose($view);

			//filtro
			$view = fopen($viewFolder.'/filtro.php', 'w');
			fwrite($view, $this->layout_filtro($submodulo, $modulo_dir));
			fclose($view);

			//Paginação
			$view = fopen($viewFolder.'/pagination.php', 'w');
			fwrite($view, $this->layout_pagination($submodulo, $modulo_dir));
			fclose($view);

			//form
			$view = fopen($viewFolder.'/form.php', 'w');
			fwrite($view, $this->layout_form());
			fclose($view);

			//index
			$view = fopen($viewFolder.'/index.php', 'w');
			fwrite($view, $this->layout_index($submodulo, $modulo_dir));
			fclose($view);

			//tabs
			$view = fopen($viewFolder.'/tabs.php', 'w');
			fwrite($view, $this->layout_tabs($submodulo, $modulo_dir));
			fclose($view);


			return 'Módulo '.$submodulo->label.' criado com sucesso';
		}
	}




	private function layout_controller_relatorio($submodulo, $modulo_dir) {

$html =
'<?php if ( ! defined(\'BASEPATH\')) exit(\'No direct script access allowed\');

class '.ucfirst($submodulo->controller).' extends MY_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {

		$data[\'pageTitle\'] = \'Relatório de '.ucfirst($submodulo->controller).' cadastrados\';
		//Configuração padrao do relatorio
		$data[\'relatorio\'] = array(
			//titulo padrao
			\'titulo_relatorio\' => $data[\'pageTitle\'],
			//Campos padrao, com nome e um tamanho padrao inicial
			\'campos\' => array(
				array(\'Nome\', \'nome\', 40),
				array(\'Status\', \'status\', 40),
				array(\'Data cadastro\', \'data_hora_add\', 20),
			),
		);

		$data[\'view_filter\'] = \''.$modulo_dir.'/'.$submodulo->controller.'/filtro\';

		//Chama as views
		$this->load->view(\'includes/header\', $data);
		$this->load->view(\'includes/sidebar\');
		$this->load->view(\'gerar\');
		$this->load->view(\'includes/footer\');
		$this->load->view(\'includes/js_load\');
	}
}';

		return $html;
	}


	private function layout_pagination($submodulo, $modulo_dir) {

$html = 
'<div class="table-responsive">
	<table class="table table-bordered table-hover table-striped">
		<thead class="bg-primary">
			<tr>
				<th>Nome</th>
				<th>Obs.</th>
				<th>Cadastrado em</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody><?php
			foreach($registros->registros AS $registro) {?>
				<tr data-click="<?= base_url(\''.$modulo_dir.'/'.$submodulo->controller.'/cadastro/\'.encode($registro->id)) ?>">
					<td><?= $registro->nome ?></td>
					<td><?= $registro->obs ?></td>
					<td><?= date(\'d/m/Y H:i\', strtotime($registro->data_hora_add)) ?></td>
					<td><?= $registro->status ?></td>
				</tr><?php
			}?>
		</tbody>
	</table>
</div>
<?php
$this->load->view(\'includes/pagination\') ?>';

		return $html;
	}

	//Retorna o conteudo do cadastro
	private function layout_cadastro($submodulo, $modulo_dir) {

$html =
'<div class="content-wrapper">
	<section class="content-header clearfix">
		<h1 class="pull-left"><?= $pageTitle ?></h1>
		
		<a href="<?= base_url(\''.$modulo_dir.'/'.$submodulo->controller.'\') ?>" class="btn btn-sm btn-primary pull-right"><i class="glyphicon glyphicon-triangle-left"></i> Voltar</a>

	</section>
	<section class="content">	
		<div class="box box-primary">
			<div class="box-body" data-target="#cadastro" id="cadastro" data-view="<?= base_url(\''.$modulo_dir.'/'.$submodulo->controller.'/tabs/\'.$this->uri->segment(4)) ?>">
				<div class="loading"></div>
			</div>
		</div>	
	</section>
</div>';

		return $html;
	}


	//Retorna o conteudo do filtro
	private function layout_filtro($submodulo, $modulo_dir) {

$html = 
'<div class="row">
	<div class="col-md-3">
		<div class="form-group">
			<label class="control-label">Nome</label>
			<input autocomplete="off" type="text" autocomplete="off" class="form-control input-sm " name="nome" value="<?= getSearch(\'nome\') ?>">
		</div>
	</div>
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
<input type="hidden" name="model" value="'.$modulo_dir.'/'.ucfirst($submodulo->controller).'_model">';

return $html;
	}

	//Retorna o conteudo do form
	private function layout_form() {
$html =
'<div class="row">
	<div class="col-sm-5 col-md-4">
		<div class="form-group">
			<label class="control-label">Nome</label>
			<input autocomplete="off" type="text" value="<?= set_value(\'nome\', $registro[\'nome\']) ?>"  name="nome" class="form-control input-sm" required>
		</div>
	</div>
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

return $html;

	}

	//Retorna o conteudo da lista
	private function layout_index($submodulo, $modulo_dir) {

$html =
'<?php if ( ! defined(\'BASEPATH\')) exit(\'No direct script access allowed\'); ?>

<div class="content-wrapper">
	<section class="content-header clearfix">
		<h1 class="pull-left"><?= page_title($pageTitle) ?></h1>
		<a data-toggle="modal" data-target="#cadastro" class="btn btn-sm btn-success pull-right"><i class="glyphicon glyphicon-plus"></i> Adicionar novo</a>
	</section>
	<section class="content">	
		<div class="box box-primary">
			<div class="box-body">
				<ul class="nav nav-tabs" id="tabs">
					<li role="presentation" class="active"><a href="#home" data-toggle="tab">Cadastros</a></li>
					<li role="presentation"><a href="#filtro" data-toggle="tab">Pesquisar</a></li>
				</ul>
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane active" id="home" data-view="<?= base_url(\''.$modulo_dir.'/'.$submodulo->controller.'/pagination?\'.http_build_query($_GET)) ?>" data-target="#lista">
						<div id="lista">
							<div class="loading"></div>
						</div>
					</div>
					<div role="tabpanel" class="tab-pane" id="filtro">
						<?= form_open(current_url(), array(\'method\' => \'GET\')); ?>
							<?php $this->load->view(\''.$submodulo->controller.'/filtro\'); ?>
							<button type="submit" class="btn btn-sm btn-sm btn-primary"><i class="glyphicon glyphicon-search"></i> Pesquisar</button>
							<a href="<?= current_url() ?>" class="btn btn-sm btn-sm btn-default"><i class="glyphicon glyphicon-erase"></i> Limpar filtro</a>
						<?= form_close(); ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>


<?=
modal_header(\'cadastro\', \'Cadastrar '.$submodulo->controller.'\', \'modal-lg\');
	echo form_open(null, array(\'data-action\' => base_url(\''.$modulo_dir.'/'.$submodulo->controller.'/validate\'))); ?>
		<div class=\'modal-body\'>
			<?php $data[\'registro\'] = null;
			$this->load->view(\''.$submodulo->controller.'/form\', $data); ?>
		</div><?=
		modal_footer_form() .
	form_close() .
modal_footer() ?>';

return $html;
	}

	//Retorna o conteudo das tabs
	private function layout_tabs($submodulo, $modulo_dir) {

$html ='
<?php if ( ! defined(\'BASEPATH\')) exit(\'No direct script access allowed\'); ?>

<ul class="nav nav-tabs" id="tabs">

	<li role="presentation" class="active"><a href="#home" data-toggle="tab">Cadastro</a></li>
	';

$abas = explode(',', $submodulo->abas);
foreach($abas as $aba) {
	if(!empty($aba)) {
		$html .= 
		'<li role="presentation"><a href="#'.str_replace(' ', '', trim($aba)).'" data-toggle="tab">'.ucfirst(trim($aba)).'</a></li>
		';
	}
}

	$html .= '
</ul>
<div class="tab-content">

	<!-- Cadastro -->
	<div role="tabpanel" class="tab-pane active" id="home">
		<?=	form_open(null, array(\'data-action\' => base_url(\''.$modulo_dir.'/'.$submodulo->controller.'/validate/\'.$this->uri->segment(4))));
			$this->load->view(\''.$submodulo->controller.'/form\', $'.$submodulo->controller.'); ?>
			<button data-loading-text="Aguarde..." type="submit" class="btn btn-sm btn-success"><i class="glyphicon glyphicon-ok"></i> Gravar</button><?=
		form_close(); ?>
	</div>
	';

foreach($abas as $aba) {
	if(!empty($aba)) {
		$html .= 
		'
		<!-- '.ucfirst(trim($aba)).' -->
		<div role="tabpanel" class="tab-pane" id="'.str_replace(' ', '', trim($aba)).'">
			//$this->load->view(\''.$submodulo->controller.'/'.str_replace(' ', '', trim($aba)).'\', $'.str_replace(' ', '', trim($aba)).'); ?>
		</div>
		';
	}
}

		$html .= '
</div>';

return $html;
	}



	//Retorna o conteudo do model
	private function layout_model($submodulo) {

$html = '
<?php if ( ! defined(\'BASEPATH\')) exit(\'No direct script access allowed\');

class '.ucfirst($submodulo->controller).'_model extends CI_Model {

	private function filter($filters = null) {

		$filters = ($filters) ? $filters : $_GET;

		if(!empty($filters[\'nome\'])) {$this->db->like(\''.$submodulo->tabela.'.nome\', $filters[\'nome\']);}
		if(!empty($filters[\'status\'])) {$this->db->where(\''.$submodulo->tabela.'.status\', $filters[\'status\']);}
		

		if(!empty($filters[\'dc_inicial\']) && !empty($filters[\'dc_final\'])) {$this->db->where("'.$submodulo->tabela.'.data_hora_add BETWEEN \'".dateToMysql($filters[\'dc_inicial\'])."\' AND \'".dateToMysql($filters[\'dc_final\'])."\'");}
		
	}

	private function join() {
		$this->db->join(\'status\', \'status.id = '.$submodulo->tabela.'.status\');
	}

	//Obtem uma listagem com paginação
	function list() {
		//Busca os dados
		$this->db->select(\''.$submodulo->tabela.'.*, status.descricao as status\');
		$this->db->from(\''.$submodulo->tabela.'\');
		$this->db->limit($this->my_pagination->getLimit());
		$this->db->offset($this->my_pagination->getOffset());
		$this->db->order_by(\'nome\');
		$this->join();
		$this->filter();

		//Armazena os registros na variável registros
		$registros = $this->db->get()->result();

		//Faz a contagem dos registros da tabela e armazena na variavel total
		$this->db->select(\'count('.$submodulo->tabela.'.id) as total\');
		$this->db->from(\''.$submodulo->tabela.'\');
		$this->join();
		$this->filter();
		$total = $this->db->get()->result()[0]->total;

		$result = new StdClass();
		$result->registros = $registros;
		$result->total = $total;

		return $result;
	}

	//Obtem dados de um determinado registro
	function getOne($id) {
		$this->db->select(\''.$submodulo->tabela.'.*\');
		$this->db->from(\''.$submodulo->tabela.'\');
		$this->join();
		$this->db->where(\''.$submodulo->tabela.'.id\', $id);
		$this->db->limit(1);

		return $this->db->get()->result_array()[0];
	}


	//Gera dados para o relatorio
	public function report($filters) {

		$this->db->select("'.$submodulo->tabela.'.*, date_format(data_hora_add, \'%d/%m/%Y\') as data_hora_add, status.descricao as status");
		$this->db->from(\''.$submodulo->tabela.'\');
		$this->join();
		$this->filter($filters);
		$this->db->order_by(\''.$submodulo->tabela.'.\'.$filters[\'ordem\'], $filters[\'sentido\']);

		$registros = $this->db->get()->result_array();


		if(isset($filters[\'dc_inicial\']) && isset($filters[\'dc_final\'])) {
			$params[] = [\'Data cadastro\', $filters[\'dc_inicial\'].\' a \'.$filters[\'dc_final\']];
		}

		if(isset($filters[\'status\'])) {
			$params[] = [\'Status\', $this->System_model->get_nome_status($filters[\'status\'])];
		}

		$params[\'total\'] = [\'TOTAL\', number_format(count($registros), 0, \'\', \'.\') .\' registro(s)\'];


		return array(
			\'registros\' => $registros,
			\'params\' => $params
		);
		
	}
}';

return $html;
	}




	//Retorna o conteudo do controller
	private function layout_controller($submodulo, $modulo_dir) {

$html =
'<?php if ( ! defined(\'BASEPATH\')) exit(\'No direct script access allowed\');

class '.ucfirst($submodulo->controller).' extends MY_Controller {

	//Chama o model principal
	public function __construct() {
		parent::__construct();
		$this->load->model(\''.ucfirst($submodulo->controller).'_model\');
	}

	//Faz a listagem dos registros
	public function index(){

		//Coloca o título
		$data[\'pageTitle\'] = \''.ucfirst($submodulo->controller).' cadastrados\';

		$this->load->view(\'includes/header\', $data);
		$this->load->view(\'includes/sidebar\');
		$this->load->view(\''.$submodulo->controller.'/index\');
		$this->load->view(\'includes/footer\');
		$this->load->view(\'includes/js_load\');

	}

	//Chama a tabela de registros com paginação
	public function pagination(int $pag = 1) {

		is_ajax();

		//Chama a library de paginação
		$this->load->library(\'my_pagination\');

		//Carrega os registros
		$data[\'registros\'] = $this->'.ucfirst($submodulo->controller).'_model->list($pag);
		$this->my_pagination->setTotalPags($data[\'registros\']->total);

		$this->load->view(\''.$submodulo->controller.'/pagination\', $data);
		$this->load->view(\'includes/js_load\');
	}
	

	//Pagina principal do cadastro
	public function cadastro(string $id) {

		$data[\'pageTitle\'] = \''.ucfirst($submodulo->controller).'\';

		$this->load->view(\'includes/header\', $data);
		$this->load->view(\'includes/sidebar\');
		$this->load->view(\''.$submodulo->controller.'/cadastro\');
		$this->load->view(\'includes/footer\');
		
	}

	//Carrega as abas do registro
	public function tabs(string $id) {

		is_ajax();

		$id = decode($id);
		$data[\''.$submodulo->controller.'\'][\'registro\'] = $this->'.ucfirst($submodulo->controller).'_model->getOne($id);
		
		';
$abas = explode(',', $submodulo->abas);
foreach($abas as $aba) {
	$html .= 
		'//$data[\''.str_replace(' ', '', trim($aba)).'\'][\'registro\'] = $this->'.str_replace(' ', '', trim($aba)).'_model->getOne($id);
		';
}

		$html .= '
		$this->load->view(\''.$submodulo->controller.'/tabs\', $data);
		$this->load->view(\'includes/js_load\');
	}

	//Valida os dados recebidos do formulário
	public function validate($id = null) {

		is_ajax();
		$id = decode($id);

		$this->load->library(\'form_validation\');
		
		//Faz a validação dos dados
		$this->form_validation->set_rules(\'nome\', \'Nome\', \'required|alpha_spaces\');
		$this->form_validation->set_rules(\'status\', \'Status\', \'required|is_numeric\');

		//Verifica se passou na validação
		if($this->form_validation->run()) {
			//Se passou, envia para a função de gravação
			$save = $this->save($id);

			//Pega a mensagem recebida
			$result[\'message\'][] = $save[\'message\'];

			//Se retornou a ultima id e nao foi informada a id no parâmetro,  se trata de uma gravação. Entao mandará o JS redirecionar para a pagina de cadastro
			if($save[\'last_id\'] && !$id) {
				$result[\'redirect\'] = base_url(\''.$modulo_dir.'/'.$submodulo->controller.'/cadastro/\'.$save[\'last_id\']);
			}

		} else {
			//Senao, retorna os erros encontrados
			$result[\'message\'] = $this->form_validation->error_array();
		}

		$this->output->set_content_type(\'text/plain\')->set_output(json_encode($result));

	}

	//Salva os registros recebidos
	private function save($id = null) {

		//Inicia a library MY_Crud
		$this->load->library(\'my_crud\');

		//Pega os registros
		$registros = array(
			\'nome\' => $this->input->post(\'nome\'),
			\'status\' => $this->input->post(\'status\'),
			\'obs\' => $this->input->post(\'obs\'),
		);

		//Proteção contra XSS
		$registros = $this->security->xss_clean($registros);

		//Cria mensagem de sucsso
		$this->my_crud->setSuccessMsg(\''.ucfirst($submodulo->controller).' gravado com sucesso.\');
		
		//Retorna a library de gravação (success / error com mensagem)
		return $this->my_crud->save(\''.$submodulo->tabela.'\', $registros, $id);
			
	}

	//Retorna uma listagem para ser mostrada no Select2
	public function search() {

		is_ajax();
		$q = $_REQUEST[\'q\'];

		$items = $this->my_crud->search(\''.$submodulo->tabela.'\', \'id, nome\', array(\'nome\' => $q));

		$result[\'total_count\'] = count($items);
		$result[\'incomplete_results\'] = false;
		$result[\'items\'] = $items;

		$this->output->set_content_type(\'text/plain\')->set_output(json_encode($result));
	
	}
}';
	return $html;
	
	}
}
