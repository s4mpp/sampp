<div class="row">
	<div class="col-sm-4">
		<input type="hidden" name="modulo" value="<?= $_GET['modulo'] ?>">
		<div class="form-group">
			<label class="control-label">Nome</label>
			<input autocomplete="off" type="text" value="<?= set_value('label', $registro['label']) ?>"  name="label" class="form-control input-sm" required>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="form-group">
			<label class="control-label">Controller</label>
			<input autocomplete="off" type="text" value="<?= set_value('controller', $registro['controller']) ?>"  name="controller" class="form-control input-sm" required>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="form-group">
			<label class="control-label">Método principal</label>
			<input autocomplete="off" type="text" value="<?= set_value('metodo_principal', $registro['metodo_principal']) ?>"  name="metodo_principal" class="form-control input-sm">
		</div>
	</div>
	<div class="col-sm-8">
		<div class="form-group">
			<label class="control-label">Abas</label>
			<input autocomplete="off" type="text" value="<?= set_value('abas', $registro['abas']) ?>"  name="abas" class="form-control input-sm">
		</div>
	</div>
	<div class="col-sm-4">
		<div class="form-group">
			<label class="control-label">Tabela</label>
			<input autocomplete="off" type="text" value="<?= set_value('tabela', $registro['tabela']) ?>"  name="tabela" class="form-control input-sm">
		</div>
	</div>
</div>