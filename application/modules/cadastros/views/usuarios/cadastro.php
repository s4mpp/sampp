<div class="content-wrapper">
	<section class="content-header clearfix">
		<h1 class="pull-left"><?= $pageTitle ?></h1>
		
		<a href="<?= base_url('cadastros/usuarios') ?>" class="btn btn-sm btn-primary pull-right"><i class="glyphicon glyphicon-triangle-left"></i> Voltar</a>

	</section>
	<section class="content">	
		<div class="box box-primary">
			<div class="box-body" data-target="#cadastro" id="cadastro" data-view="<?= base_url('cadastros/usuarios/tabs/'.$this->uri->segment(4)) ?>">
				<div class="loading"></div>
			</div>
		</div>	
	</section>
</div>