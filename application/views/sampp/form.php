<div class="row">
	<div class="col-sm-4">
		<div class="form-group">
			<label class="control-label">Nome</label>
			<input autocomplete="off" type="text" value="<?= set_value('label', $registro['label']) ?>"  name="label" class="form-control input-sm" required>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="form-group">
			<label class="control-label">Diretório</label>
			<input autocomplete="off" type="text" value="<?= set_value('value', $registro['value']) ?>"  name="value" class="form-control input-sm" required>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="form-group">
			<label class="control-label">Ícone</label>
			<input autocomplete="off" type="text" value="<?= set_value('icone', $registro['icone']) ?>"  name="icone" class="form-control input-sm" required>
		</div>
	</div>
</div>