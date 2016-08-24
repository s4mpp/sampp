<div class="row">
	<div class="col-md-3">
		<div class="form-group">
			<label class="control-label">Nome</label>
			<input autocomplete="off" type="text" autocomplete="off" class="text-uppercase form-control input-sm input-sm " name="nome" value="<?= getSearch('nome') ?>">
		</div>
	</div>
	<div class="col-md-2">
		<div class="form-group">
			<label class="control-label">Sexo</label>
			<select class="select2 form-control input-sm" name="sexo">
				<option></option>
				<option <?= set_select('sexo', getSearch('sexo'), (getSearch('sexo') == 'M')) ?> value="M">Masculino</option>
				<option <?= set_select('sexo', getSearch('sexo'), (getSearch('sexo') == 'F')) ?> value="F">Feminino</option>
			</select>
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group">
			<label class="control-label">Cidade</label>
			<select data-target='<?= base_url('cadastros/usuarios/get_cidades'); ?>' class='select2_ajax form-control input-sm' name='cidade'>
			</select>
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group">
			<label class="control-label">Data nasc.</label>
			<div class="input-group">
				<input autocomplete="off" type="text" name="dn_inicial" value="<?= getSearch('dn_inicial') ?>" class="datepicker form-control input-sm input-sm" placeholder="dd/mm/aaaa" />
				<span class="input-group-addon" style="border-left: 0; border-right: 0;">até</span>
				<input autocomplete="off" type="text" name="dn_final" value="<?= getSearch('dn_final') ?>" class="datepicker form-control input-sm input-sm" placeholder="dd/mm/aaaa" />
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group">
			<label class="control-label">Data cadastro</label>
			<div class="input-group">
				<input value="<?= getSearch('dc_inicial') ?>" type="text" name="dc_inicial" class="datepicker form-control input-sm" placeholder="dd/mm/aaaa" />
				<span class="input-group-addon" style="border-left: 0; border-right: 0;">até</span>
				<input value="<?= getSearch('dc_final') ?>" type="text" name="dc_final" class="datepicker form-control input-sm" placeholder="dd/mm/aaaa" />
			</div>
		</div>
	</div>
	<div class="col-md-2">
		<div class="form-group">
			<label class="control-label">Situação</label>
			<select class="select2 form-control input-sm" name="status">
				<option></option>
				<option <?= set_select('status', getSearch('status'), (getSearch('status') == 1)) ?> value="1">Ativo</option>
				<option <?= set_select('status', getSearch('status'), (getSearch('status') == 2)) ?> value="2">Inativo</option>
			</select>
		</div>
	</div>
</div>
<input type="hidden" name="model" value="cadastros/Usuarios_model">