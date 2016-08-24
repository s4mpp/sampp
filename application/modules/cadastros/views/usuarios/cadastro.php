<div class="content-wrapper">
	<section class="content-header clearfix">
		<h1 class="pull-left"><?= $pageTitle ?></h1>

		<a href="<?= base_url('cadastros/usuarios/listar') ?>" class="btn btn-sm btn-primary pull-right"><i class="glyphicon glyphicon-triangle-left"></i> Voltar</a>

		<a
		data-toggle="modal" data-target="#gerarsenha"
		data-modal="<?= base_url('cadastros/usuarios/gerarsenha/'.$this->uri->segment(4)) ?>"
		class="btn btn-sm btn-warning pull-right"><i class="glyphicon glyphicon-lock"></i> Gerar senha</a>
	</section>
	<section class="content">	
		<div class="box box-primary">
			<div class="box-body" data-target="#cadastro" id="cadastro" data-view="<?= base_url('cadastros/usuarios/tabs/'.$this->uri->segment(4)) ?>">
				<div class="loading"></div>
			</div>
		</div>	
	</section>
</div>

<?=
modal_header('gerarsenha', 'Gerar nova senha', '') ?>
		<div class='modal-body'>
			<div class="loading"></div>
		</div>
		<div class='modal-footer'>
			<button class='btn btn-sm btn-success' data-dismiss='modal'><i class='glyphicon glyphicon-ok'></i> OK</button>
			<button class='btn btn-sm btn-default' data-dismiss='modal'><i class='glyphicon glyphicon-remove'></i> Fechar</button>
		</div>
		<?=
modal_footer() ?>