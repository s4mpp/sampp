<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<ul class="nav nav-tabs" id="tabs">
	<li role="presentation" class="active"><a href="#home" data-toggle="tab">Módulo</a></li>
	<li role="presentation"><a href="#submodulos" data-toggle="tab">Submódulos</a></li>
</ul>
<div class="tab-content">

	<!-- Módulo -->
	<div role="tabpanel" class="tab-pane active" id="home">
		<?=	form_open(null, array('data-action' => base_url('sampp/validate/'.$this->uri->segment(3))));
			$this->load->view('sampp/form', $funcionario); ?>
			<button data-loading-text="Aguarde..." type="submit" class="btn btn-sm btn-success"><i class="glyphicon glyphicon-ok"></i> Gravar</button><?=
		form_close(); ?>
	</div>
	<!-- Submódulos -->
	<div role="tabpanel" class="tab-pane" id="submodulos">
		<?php $this->load->view('sampp/submodulos', $submodulos); ?>
	</div>
</div>