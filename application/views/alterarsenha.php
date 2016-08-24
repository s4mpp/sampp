<div class="content-wrapper">
	<section class="content-header clearfix">
		<h1 class="pull-left"><?= $pageTitle ?></h1>
	</section>
	<section class="content">	
		<div class="box box-primary">
			<div class="alert-container">
				<?=	form_open(null, array('data-action' => base_url('alterarsenha/validate'))); ?></div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label">Senha atual</label>
									<input required autocomplete="off" type="password"  name="senhaatual" class="form-control">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label">Nova senha</label>
									<input required autocomplete="off" type="password"  name="novasenha" class="form-control text-uppercase">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label">Confirme a nova senha</label>
									<input required autocomplete="off" type="password" name="novasenhaconfirm" class="form-control text-uppercase">
								</div>
							</div>
						</div>	
					</div>
				<div class="box-footer">
					<button data-loading-text="Aguarde..." type="submit" class="btn btn-sm btn-success"><i class="glyphicon glyphicon-ok"></i> Alterar</button>
				</div><?=
			form_close(); ?>
		</div>
	</section>
</div>