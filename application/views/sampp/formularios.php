<div class="content-wrapper">
	<section class="content-header clearfix">
		<h1 class="pull-left"><?= page_title($pageTitle) ?></h1>
	</section>
	<section class="content">	
		<div class="box box-primary">
			<div class="box-body">
				<div class="row">
					<div class="col-sm-4">
						<div class="form-group">
							<label class='control-label'>Selecione a tabela</label>
							<select id="tabela" class="form-control select2">
								<option></option><?php
								foreach($tabelas as $tabela) { ?>
									<option value="<?= $tabela ?>"><?= $tabela ?></option><?php
								} ?>
							</select>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<label class='control-label'>Selecione o submódulo</label>
							<select id="submodulo" class="form-control select2">
								<option></option><?php
								foreach($submodulos as $submodulos) { ?>
									<option value="<?= $submodulos->value.'/'.$submodulos->controller ?>"><?= $submodulos->label ?></option><?php
								} ?>
							</select>
						</div>
					</div>
					<div class="col-sm-2">
						&nbsp;
						<div class="form-group">
							<button data-url="<?= base_url('samppform/formulario') ?>" class='btn btn-primary btn-sm' id="carregaForm">OK</button>
						</div>
					</div>
					
				</div>
				<div class="row">
					<div class="col-xs-12">
						<div id="form"></div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
