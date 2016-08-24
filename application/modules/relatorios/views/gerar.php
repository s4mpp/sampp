<div class="content-wrapper">
	<section class="content-header clearfix">
		<h1 class="pull-left"><?= $pageTitle ?></h1>
		<a href="<?= base_url('relatorios/'.$this->uri->segment(2)) ?>" class="btn btn-primary btn-sm pull-right"><i class="glyphicon glyphicon-triangle-left"></i> Voltar</a>
	</section>
	<section class="content">
		<div class="box box-primary">
			<div class="box-body"><?=
				form_open(base_url('relatorios/'.$this->uri->segment(2).'/gerar')); ?>
					<ul class="nav nav-tabs" id="tabs">
						<li role="presentation" class="active"><a href="#formulario" data-toggle="tab">Parâmetros</a></li>
						<li role="presentation"><a href="#layout" data-toggle="tab">Layout</a></li>
					</ul>
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane active" id="formulario">
							<?php $this->load->view($view_filter); ?>
						</div>
						<div role="tabpanel" class="tab-pane" id="layout">
							<?php $this->load->view('layout_relatorio'); ?>
						</div>
					</div>
				</div>
				<div class="box-footer">
					<button type="submit" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-ok"></i> Gerar</button>
				</div>
				<?=
			form_close(); ?>
		</div>
	</section>
</div>