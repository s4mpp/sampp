<div class="row">
	<div class="col-sm-6 col-md-4">
		<div class="form-group">
			<label class="control-label">Nome Fantasia</label>
			<input required autocomplete="off" type="text" value="<?= set_value('nome', $registro['nome']) ?>"  name="nome" class="form-control input-sm">
		</div>
	</div>
	<div class="col-sm-6 col-md-4">
		<div class="form-group">
			<label class="control-label">Razão Social</label>
			<input required autocomplete="off" type="text" value="<?= set_value('razao_social', $registro['razao_social']) ?>"  name="razao_social" class="form-control input-sm">
		</div>
	</div>
	<div class="col-sm-4 col-md-2">
		<div class="form-group">
			<label class="control-label">CNPJ</label>
			<input required autocomplete="off" type="text" value="<?= set_value('cnpj', $registro['cnpj']) ?>"  name="cnpj" class="form-control input-sm cnpj">
		</div>
	</div>
	<div class="col-sm-4 col-md-2">
		<div class="form-group">
			<label class="control-label">Inscrição Municipal</label>
			<input autocomplete="off" type="text" value="<?= set_value('inscricao_municipal', $registro['inscricao_municipal']) ?>"  name="inscricao_municipal" class="form-control input-sm ">
		</div>
	</div>
	<div class="col-sm-4 col-md-2">
		<div class="form-group">
			<label class="control-label">Inscrição Estadual</label>
			<input autocomplete="off" type="text" value="<?= set_value('inscricao_estadual', $registro['inscricao_estadual']) ?>"  name="inscricao_estadual" class="form-control input-sm ">
		</div>
	</div>
	<div class="col-sm-6 col-md-2">
		<label class="control-label">Cidade</label>
		<div class="form-group">
			<select data-target='<?= base_url('cadastros/instalacao/search_cidades'); ?>' class='select2_ajax form-control input-sm' name='cidade'>
				<option value="<?= $registro['cidade'] ?>" selected><?= $registro['nomeCidade'] ?></option>
			</select>
		</div>
	</div>
	<div class="col-sm-6 col-md-2">
		<label class="control-label">Bairro</label>
		<div class="form-group">
			<input autocomplete="off" type="text" value="<?= set_value('bairro', $registro['bairro']) ?>"  name="bairro" class="form-control input-sm ">
		</div>
	</div>
	<div class="col-sm-6 col-md-3">
		<div class="form-group">
			<label class="control-label">Endereço</label>
			<input autocomplete="off" type="text" value="<?= set_value('endereco', $registro['endereco']) ?>" name="endereco" class="form-control input-sm">
		</div>
	</div>
	<div class="col-sm-3 col-md-1">
		<div class="form-group">
			<label class="control-label">Numero</label>
			<input autocomplete="off" type="text" value="<?= set_value('num_endereco', $registro['num_endereco']) ?>" name="num_endereco" class="form-control input-sm ">
		</div>
	</div>
	<div class="col-sm-3 col-md-2">
		<div class="form-group">
			<label class="control-label">CEP</label>
			<input autocomplete="off" type="text" value="<?= set_value('cep', $registro['cep']) ?>" name="cep" class="form-control input-sm cep">
		</div>
	</div>
</div>
			