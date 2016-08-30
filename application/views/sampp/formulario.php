<?= form_open(null, array('data-action' => base_url('samppform/gerar'))); ?>
<input name="submodulo" type="hidden" value="<?= $_GET['submodulo']?>">
<div class="table-responsive">
	<table class="table table-striped table-bordered">
		<thead class='bg-primary'>
			<th>Campo</th>
			<th>Label</th>
			<th>Tipo</th>
			<th>Máscara</th>
			<th>Required</th>
			<th>Select ajax</th>
			<th>Tamanho</th>
		</thead>
		<tbody><?php
			foreach($campos as $campo) { 

				if($campo->Field == 'id' || $campo->Field == 'obs' || $campo->Field == 'data_hora_add' || $campo->Field == 'usuario' || $campo->Field == 'status') {
					continue;
				}

				?>
				<tr>
					<input name="field[]" type="hidden" value="<?= $campo->Field ?>">

					<td style="width:100px"><strong><?= $campo->Field ?></strong> <br/><small><?= $campo->Type ?></small></td>
					<td style="width:140px">
						<input class='form-control input-sm' name="label[]" value="<?= ucfirst(str_replace('_', ' ', $campo->Field)) ?>">
					</td>
					<td>
						<select name="tipo[]" class="input-sm form-control select2">
							<option value="text">Texto</option>
							<option value="select">Select</option>
						</select>
					</td>
					<td>
						<select name="mascara[]" class="input-sm form-control select2">
							<option></option>
							<option value="datepicker">Data</option>
							<option value="time">Horario</option>
							<option value="cep">CEP</option>
							<option value="cpf">CPF</option>
							<option value="cnpj">CNPJ</option>
							<option value="telefone">Telefone</option>
							<option value="maskMoney">Moeda</option>
						</select>
					</td>
					<td  style="width:40px">
						<label class="checkbox-inline">
							<input class='icheck' name="<?= $campo->Field ?>" type="checkbox" value="1">
						</label>
					</td>
					<td>
						<select class="form-control select2" name="get[]">
							<option></option><?php
							foreach($submodulos as $submodulo) { ?>
								<option value="<?= $submodulo->value.'/'.$submodulo->controller.'/search' ?>"><?= $submodulo->value.'/'.$submodulo->label ?></option><?php
							} ?>
						</select>
					</td>
					<td>
						<select name="tamanho[]" class="input-sm form-control select2">
							<option value="col-sm-1">col-sm-1</option>
							<option value="col-sm-2">col-sm-2</option>
							<option value="col-sm-3">col-sm-3</option>
							<option selected value="col-sm-4">col-sm-4</option>
							<option value="col-sm-5">col-sm-5</option>
							<option value="col-sm-6">col-sm-6</option>
							<option value="col-sm-7">col-sm-7</option>
							<option value="col-sm-8">col-sm-8</option>
							<option value="col-sm-9">col-sm-9</option>
							<option value="col-sm-10">col-sm-10</option>
							<option value="col-sm-11">col-sm-11</option>
							<option value="col-sm-12">col-sm-12</option>
						</select>
					</td>
				</tr><?php
			} ?>
		</tbody>
	</table>
</div>

<button data-loading-text="Aguarde..." type="submit" class="btn btn-sm btn-success"><i class="glyphicon glyphicon-ok"></i> Gravar</button>

<?= form_close() ?>