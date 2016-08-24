<?php

//Mostra o titulo da pagina se o filtro nao estiver ativado. se tiver, mostra o batao de limpar
function page_title($title) {
	if(!empty($_GET)) {
		return "Resultados da pesquisa<a class='btn btn-primary btn-xs' href='".base_url(uri_string())."'>Limpar filtro</a>";
	} else {
		return $title;
	}
}

//Header modal
function modal_header($id, $title, $tam = null) { return "
<div id='$id' class='modal'  tabindex='-1'>
	<div class='modal-dialog ".$tam."'>
		<div class='modal-content'>
			<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
				<h4 class='modal-title'>$title</h4>
			</div>";
 } 

//Footer modal
function modal_footer() { return "
		</div>
	</div>
</div>";
}

//Coloca um item na sidebar ativo
function active($url, $segment) {
	$CI =& get_instance();

	if($url == $CI->uri->segment($segment)) {
		return 'active';
	}
}

//Limpa caracteres estranhos da string 
function cleanInput($input) {
	return str_replace(array('(', ')', ' ', '.', '-', '/'), '', $input);
}

//Permite apenas requisiçções ajax a determinado metodo
function is_ajax() {
	$CI =& get_instance();
	if(!$CI->input->is_ajax_request()) {echo 'Requisiçao inválida'; exit; }
}


//Formata data YYYY-mm-dd para ser mostrada na view dd/mm/YYYY
function dateENtoPT($date) {
	if(strtotime($date) > 0) {
		return date('d/m/Y', strtotime($date));
	} else {
		return false;
	}
}


//Formata data dd/mm/YYYY para ser mostrada na view YYYY-mm-dd
function datePTtoEN($datePT) {
	$date = explode('/', $datePT);
	return join('-', array_reverse($date));
}


//Verifica se o valor existe no array e retorna o que desejar (return)
function exist($valor, $array, $return = null) {
	$array = (!is_array($array)) ? array() : $array;
	if(in_array($valor, $array)) {
		if($return)
			return $return;
		else 
			return true;
	}
}


//Verifica se tem  valores em $_GET[] eos obtem
function getSearch($var) {
	if(array_key_exists($var, $_GET)) {
		return $_GET[$var];
	}
}

