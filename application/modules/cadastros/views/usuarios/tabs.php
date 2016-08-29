
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<ul class="nav nav-tabs" id="tabs">

	<li role="presentation" class="active"><a href="#home" data-toggle="tab">Cadastro</a></li>
	<li role="presentation"><a href="#permissoes" data-toggle="tab">Permissoes</a></li>
	
</ul>
<div class="tab-content">

	<!-- Cadastro -->
	<div role="tabpanel" class="tab-pane active" id="home">
		<?=	form_open(null, array('data-action' => base_url('cadastros/usuarios/validate/'.$this->uri->segment(4))));
			$this->load->view('usuarios/form', $usuarios); ?>
			<button data-loading-text="Aguarde..." type="submit" class="btn btn-sm btn-success"><i class="glyphicon glyphicon-ok"></i> Gravar</button><?=
		form_close(); ?>
	</div>
	
	<!-- Permissoes -->
	<div role="tabpanel" class="tab-pane" id="permissoes">
		<?= form_open(null, array('data-action' => base_url('cadastros/usuarios/permissoes/'.$this->uri->segment(4))));
			$this->load->view('usuarios/permissoes', $permissoes); ?>
			<button data-loading-text="Aguarde..." type="submit" class="btn btn-sm btn-success"><i class="glyphicon glyphicon-ok"></i> Gravar</button>
		<?=	form_close() ?>
	</div>
	
</div>