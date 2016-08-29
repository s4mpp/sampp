<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

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
					<div role="tabpanel" class="tab-pane active" id="home" data-view="<?= base_url('cadastros/usuarios/pagination?'.http_build_query($_GET)) ?>" data-target="#lista">
						<div id="lista">
							<div class="loading"></div>
						</div>
					</div>
					<div role="tabpanel" class="tab-pane" id="filtro">
						<?= form_open(current_url(), array('method' => 'GET')); ?>
							<?php $this->load->view('usuarios/filtro'); ?>
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
modal_header('cadastro', 'Cadastrar usuarios', 'modal-lg');
	echo form_open(null, array('data-action' => base_url('cadastros/usuarios/validate'))); ?>
		<div class='modal-body'>
			<?php $data['registro'] = null;
			$this->load->view('usuarios/form', $data); ?>
		</div><?=
		modal_footer_form() .
	form_close() .
modal_footer() ?>