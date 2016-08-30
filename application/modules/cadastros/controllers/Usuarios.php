<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuarios extends MY_Controller {

	//Chama o model principal
	public function __construct() {
		parent::__construct();
		$this->load->model('Usuarios_model');
	}

	//Faz a listagem dos registros
	public function index(){

		//Coloca o título
		$data['pageTitle'] = 'Usuarios cadastrados';

		$this->load->view('includes/header', $data);
		$this->load->view('includes/sidebar');
		$this->load->view('usuarios/index');
		$this->load->view('includes/footer');
		$this->load->view('includes/js_load');

	}

	//Chama a tabela de registros com paginação
	public function pagination(int $pag = 1) {

		is_ajax();

		//Chama a library de paginação
		$this->load->library('my_pagination');

		//Carrega os registros
		$data['registros'] = $this->Usuarios_model->list($pag);
		$this->my_pagination->setTotalPags($data['registros']->total);

		$this->load->view('usuarios/pagination', $data);
		$this->load->view('includes/js_load');
	}
	

	//Pagina principal do cadastro
	public function cadastro(string $id) {

		$data['pageTitle'] = 'Usuarios';

		$this->load->view('includes/header', $data);
		$this->load->view('includes/sidebar');
		$this->load->view('usuarios/cadastro');
		$this->load->view('includes/footer');
		
	}

	//Carrega as abas do registro
	public function tabs(string $id) {

		is_ajax();

		$id = decode($id);
		$data['usuarios']['registro'] = $this->Usuarios_model->getOne($id);
		$data['permissoes']['permissions'] = $this->system->get_permissoes_usuario($id);
		
		$this->load->view('usuarios/tabs', $data);
		$this->load->view('includes/js_load');
	}

	//Valida os dados recebidos do formulário
	public function validate($id = null) {

		is_ajax();
		$id = decode($id);

		$this->load->library('form_validation');
		
		//Faz a validação dos dados
		$this->form_validation->set_rules('nome', 'Nome Completo', 'required|strtoupper|alpha_spaces');
		$this->form_validation->set_rules('dn', 'Data nasc.', 'trim|required|valid_date');
		$this->form_validation->set_rules('cpf', 'CPF', 'trim|valid_cpf|unique[usuarios.cpf.'.$id.']');
		$this->form_validation->set_rules('rg', 'RG', 'cleanInput|numeric|unique[usuarios.rg.'.$id.']');
		$this->form_validation->set_rules('login', 'Login', 'trim|unique[usuarios.login.'.$id.']');

		//Verifica se passou na validação
		if($this->form_validation->run()) {
			//Se passou, envia para a função de gravação
			$save = $this->save($id);

			//Pega a mensagem recebida
			$result['message'][] = $save['message'];

			//Se retornou a ultima id e nao foi informada a id no parametro,  se trata de uma gravação. Entao mandará o JS redirecionar para a pagina de cadastro
			if($save['last_id'] && !$id) {
				$result['redirect'] = base_url('cadastros/usuarios/cadastro/'.$save['last_id']);
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
			'nome' => $this->input->post('nome'),
			'dn' => datePTtoEN($this->input->post('dn')),
			'sexo' => $this->input->post('sexo'),
			'endereco' => $this->input->post('endereco'),
			'num_endereco' => $this->input->post('num_endereco'),
			'cidade' => $this->input->post('cidade'),
			'bairro' => $this->input->post('bairro'),
			'status' => $this->input->post('status'),
			'login' => $this->input->post('login'),
			'obs' => $this->input->post('obs'),
		);

		//Proteção contra XSS
		$registros = $this->security->xss_clean($registros);

		//Cria mensagem de sucsso
		$this->my_crud->setSuccessMsg('Usuário gravado com sucesso.');
		
		//Retorna a library de gravação (success / error com mensagem)
		return $this->my_crud->save('usuarios', $registros, $id);
			
	}

	//Retorna uma listagem para ser mostrada no Select2
	public function search() {

		is_ajax();
		$q = $_REQUEST['q'];

		//Inicia a library MY_Crud
		$this->load->library('my_crud');

		$items = $this->my_crud->search('usuarios', 'id, nome', ['nome', $q]);

		$result['total_count'] = count($items);
		$result['incomplete_results'] = false;
		$result['items'] = $items;

		$this->output->set_content_type('text/plain')->set_output(json_encode($result));
	
	}


	//Manipula e Grava as permissoes do usuario
	public function permissoes($id) {

		is_ajax();
		$id = decode($id);	
		
		//Recebe as novas permissoes
		$permissoes_input = $this->input->post('permissoes');
		if($id && $permissoes_input) {
			
			//Pega as permissoes atuais do usuario
			$permissoes = array();
			$permissoes = $this->system->get_permissoes_usuario($id);

			//Pega os submodulos existentes
    		$submodulos = $this->db->get_where('submodulos')->result();

			//Faz o loop entre os submodulos existentes
			foreach($submodulos as $submodulo) {
				//Verifica se o modulo esta entre as ações recebidas
				if(in_array($submodulo->id, $permissoes_input)) {
					//Se nao estiver, grava no banco
					if(!in_array($submodulo->id, $permissoes)) {
						$this->db->insert('permissoes', array('submodulo' => $submodulo->id, 'usuario' => $id));
					}
				//Se nao estiver, continua
				} else {
					//Verifica se foi recebido, para excluir
					if(in_array($submodulo->id, $permissoes)) {
						$this->db->delete('permissoes', array('submodulo' => $submodulo->id, 'usuario' => $id));
					}
				}
			}

			$result['message'][] = array('success', 'Permissões alteradas com sucesso. Para ter efeito, é necessário sair e entrar novamente no sistema.');
			echo json_encode($result);
		}

	}


	//Gera uma nova senha aleatoria para o usuario
	public function gerarsenha($id) {
		//Pega a id enviada;
		$id = decode($id);

		//gera uma string aleatoria
		$this->load->helper('string');
		$senha = strtolower(random_string('alnum', 5));
		$senhaMd5 = md5($senha);

		//Atualiza no banco
		if($this->db->update('usuarios', array('senha' => $senhaMd5), array('id' => $id))) {
			//Mostra nova senha
			echo "<p>Senha alterada com sucesso. A nova senha é:</p>
			<h1 class='text-center'><strong class='bg-info text-primary'>".$senha."</strong></h1>";
			
		} else {
			echo "<p>Houve um erro ao alterar a senha. Verifique.</p>";
		}
	}
 
}