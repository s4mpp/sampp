
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label">Título relatório</label>
							<input autocomplete="off" value="<?= $relatorio['titulo_relatorio'] ?>" type="text" name="nome_relatorio" class="form-control input-sm">
						</div>
					</div>
					<div class="col-sm-2">
						<div class="form-group">
							<label class="control-label">Ordenar registros por</label>
							<select name="ordem" class="select2 form-control input-sm"><?php
								foreach($relatorio['campos'] as $campo) {?>
									<option value="<?= $campo[1] ?>"><?= $campo[0] ?></option><?php
								} ?>
							</select>
						</div>
					</div>
					<div class="col-sm-2">
						<div class="form-group">
							<label class="control-label">Sentido</label>
							<select name="sentido" class="select2 form-control input-sm">
								<option value="ASC">Ascendente</option>
								<option value="DESC">Descendente</option>
							</select>
						</div>
					</div>
					<div class="col-sm-2">
						<div class="form-group">
							<label class="control-label">Orientação</label>
							<select name="orientation" class="select2 form-control input-sm">
								<option value="P">Retrato</option>
								<option value="L">Paisagem</option>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<label>Campos</label>
						<small class="text-info"> Arraste os campos para ordenar</small>
						<div class="table-responsive">
							<table class="table table-striped table-bordered" style="background-color: #fff!important; margin-bottom:0">
								<thead class="bg-primary">
									<tr>
										<th>Título</th>
										<th>Tamanho(%)</th>
									</tr>
								</thead>
								<tbody id="sortable"><?php
									for($i=0; $i<count($relatorio['campos']); $i++) {?>
										<tr ondrop="alert()" draggable="true" style="cursor: ns-resize">
											<input type="hidden" name="<?= $i ?>[]" value="<?= $relatorio['campos'][$i][1] ?>">
											<input type="hidden" name="<?= $i ?>[]" value="<?= $relatorio['campos'][$i][0] ?>">
											<td><?= $relatorio['campos'][$i][0]  ?></td>
											<td><input autocomplete="off" type="number" name="<?= $i ?>[]" value="<?= (isset($relatorio['campos'][$i][2])) ? $relatorio['campos'][$i][2] : null ?>"></td>
										</tr><?php
									} ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>

