<div class="row">
	<div class="col-sm-5 col-md-4">
		<div class="form-group">
			<label class="control-label">Nome completo</label>
			<input autocomplete="off" type="text" value="<?= set_value('nome', $registro['nome']) ?>"  name="nome" class="form-control input-sm" required>
		</div>
	</div>
	<div class="col-sm-4 col-md-2">
		<div class="form-group">
			<label class="control-label">Data nasc.</label>
			<input autocomplete="off" autocomplete="off" type="text" value="<?= set_value('dn', dateENtoPT($registro['dn'])) ?>" class="datepicker form-control input-sm" name="dn">
		</div>
	</div>
	<div class="col-sm-3 col-md-2">
		<div class="form-group">
			<label class="control-label">Sexo</label>
			<select class="select2 form-control input-sm" name="sexo">
				<option></option>
				<option <?= set_select('sexo', $registro['sexo'], ($registro['sexo'] == 'M')) ?> value="M">Masculino</option>
				<option <?= set_select('sexo', $registro['sexo'], ($registro['sexo'] == 'F')) ?> value="F">Feminino</option>
			</select>
		</div>
	</div>
	<div class="col-sm-4 col-md-4">
		<div class="form-group">
			<label class="control-label">Endereço</label>
			<input autocomplete="off" type="text" value="<?= set_value('endereco', $registro['endereco']) ?>" name="endereco" class="form-control input-sm">
		</div>
	</div>

	<div class="col-sm-3 col-md-1">
		<div class="form-group">
			<label class="control-label">Nº</label>
			<input autocomplete="off" type="text" value="<?= set_value('num_endereco', $registro['num_endereco']) ?>" name="num_endereco" class="form-control input-sm">
		</div>
	</div>
	<div class="col-sm-5 col-md-3">
		<label class="control-label">Cidade</label>
		<div class="form-group">
			<select data-target='<?= base_url('cadastros/usuarios/search_cidades'); ?>' class='select2_ajax form-control input-sm' name='cidade'>
				<option value="<?= $registro['cidade'] ?>" selected><?= $registro['nomeCidade'] ?></option>
			</select>
		</div>
	</div>

	<div class="col-sm-5 col-md-3">
		<label class="control-label">Bairro</label>
		<div class="form-group">
			<input autocomplete="off" type="text" value="<?= set_value('bairro', $registro['bairro']) ?>" name="bairro" class="form-control input-sm">
		</div>
	</div>

	<div class="col-sm-4 col-md-3">
		<div class="form-group">
			<label class="control-label">Login</label>
			<input autocomplete="off" type="text"  value="<?= set_value('login', $registro['login']) ?>" name="login" class="form-control input-sm">
		</div>
	</div>

	<div class="col-sm-3 col-md-2">
		<div class="form-group">
			<label class="control-label">Status</label>
			<select class="select2 form-control input-sm" name="status">
				<option value="1" <?= set_select('status', $registro['status'], ($registro['status'] == '1')) ?> >Ativo</option>
				<option value="0" <?= set_select('status', $registro['status'], ($registro['status'] == '0')) ?> >Inativo</option>
			</select>
		</div>
	</div>
	<div class="col-xs-12">
		<div class="form-group">
			<label class="control-label">Observações</label>
			<textarea class="form-control input-sm" name="obs"><?= set_value('obs', $registro['obs']) ?></textarea>
		</div>
	</div>
</div>