<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class System {

	public function __construct() {
		$this->CI =& get_instance();
	
	}

	//$usuario = O usuario o qual esta pegando as permissoes
	//$session = true se for pra colocar na sessao logada as permissoes. Usada somente no login, para adicionar na sessao as permissoes do usuario que esta logando no sistema
	function get_permissoes_usuario($usuario, $session = false) {

		//Coleta as permissoes
		$permissoes = $this->CI->System_model->get_permissoes_usuario($usuario);

		//Faz o loop pra colocar no array $perm
		$perm = array();
		foreach($permissoes as $perm_modulo) {
			$perm[] = $perm_modulo->submodulo;
		}

		//Coloca na sessao, se for verdadeiro o parametro $session
		if($session) {
			$this->CI->session->set_userdata('permissoes', $perm);
		}

		//Retorna as permissoes
		return $perm;
	}


	//Obtem os modulos de um usuario
	function get_modules($usuario) {

		$permissoes = $this->get_permissoes_usuario($usuario, TRUE);
		$nav = array();
		$i = 0;
		foreach($this->CI->System_model->get_modules() as $module) {

			$submodules_all = $this->CI->System_model->get_submodules($module->id);
			
			$submodulos_perm = array();
			foreach($submodules_all as $submodulo) {
				if(in_array($submodulo->id, $permissoes)) {
					$submodulos_perm[] = $submodulo;
				}
			}

			if(count($submodulos_perm) > 0) {
				$nav[$i]['module'] = $module;
				$nav[$i]['submodules'] = $submodulos_perm;
			}

			$i++;
		}

		$this->CI->session->set_userdata('modules_nav', $nav);
	}


	function get_all_modules() {
		$i = 0;
		foreach($this->CI->System_model->get_modules() as $module) {
			$nav[$i]['module'] = $module;
			$nav[$i]['submodules'] = $this->CI->System_model->get_all_submodules($module->id);
			$i++;
		}
		return $nav;
	}


	
}