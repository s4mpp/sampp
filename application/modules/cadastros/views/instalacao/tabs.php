<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<ul class="nav nav-tabs" id="tabs">
	<li role="presentation" class="active"><a href="#home" data-toggle="tab">Cadastro</a></li>
</ul>
<div class="tab-content">

	<div role="tabpanel" class="tab-pane active" id="home">
		<?= form_open(null, array('data-action' => base_url('cadastros/instalacao/validate')));
			$this->load->view('instalacao/form', $instalacao); ?>
			
			<button data-loading-text="Aguarde..." type="submit" class="btn btn-sm btn-success"><i class="glyphicon glyphicon-ok"></i> Gravar</button><?=
		form_close(); ?>
	</div>

</div>