<div class="content-wrapper">
	<section class="content-header clearfix">
		<h1 class="pull-left"><?= $pageTitle ?></h1>
		<a href="<?= base_url('sampp/listar') ?>" class="btn btn-sm btn-primary pull-right"><i class="glyphicon glyphicon-triangle-left"></i> Voltar</a>
		<a href="<?= base_url('sampp/compilar/'.$this->uri->segment(3)) ?>" class="btn btn-sm btn-warning pull-right"><i class="glyphicon glyphicon-console"></i> Compilar</a>
	</section>
	<section class="content">	
		<div class="box box-primary">
			<div class="box-body" data-target="#cadastro" id="cadastro" data-view="<?= base_url('sampp/tabs/'.$this->uri->segment(3)) ?>">
				<div class="loading"></div>
			</div>
		</div>	
	</section>
</div>