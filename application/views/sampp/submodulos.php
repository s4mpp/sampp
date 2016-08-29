<button data-toggle="modal" data-target="#submodulo"
data-modal="<?= base_url('sampp/form_submodulo?modulo='.$this->uri->segment(3)) ?>"
data-action="<?= base_url('sampp/validate_submodule') ?>" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> Cadastrar submódulo</button>

<div class="spacer"></div>

<div class="table-responsive">
	<table class="table table-bordered table-hover table-striped">
		<thead class="bg-primary">
			<tr>
				<th>Nome</th>
				<th>Controller</th>
				<th>Método principal</th>
				<th>Tabela</th>
				<th>Abas</th>
			</tr>
		</thead>
		<tbody><?php
			foreach($registros AS $submodulo) {?>
				<tr data-toggle="modal" data-target="#submodulo"
					data-modal="<?= base_url('sampp/form_submodulo/'.encode($submodulo->id).'?modulo='.$this->uri->segment(3)) ?>"
					data-action="<?= base_url('sampp/validate_submodule/'.encode($submodulo->id)) ?>">
					<td><?= $submodulo->label ?></td>
					<td><?= $submodulo->controller ?></td>
					<td><?= $submodulo->metodo_principal ?></td>
					<td><?= $submodulo->tabela ?></td>
					<td><?= $submodulo->abas ?></td>
				</tr><?php
			}?>
		</tbody>
	</table>
</div>

<?=
modal_header('submodulo', 'Cadastro de submódulo');
	echo form_open(''); ?>
		<div class='modal-body'>
			<div class="loading"></div>
		</div>
		<div class='modal-footer'>
			<button data-loading-text="Aguarde..." type="submit" class="btn btn-success"><i class="glyphicon glyphicon-ok"></i> Gravar</button>
			<button class='btn btn-default' data-dismiss='modal'><i class='glyphicon glyphicon-remove'></i> Cancelar</button>
		</div>
		<?=
	form_close() .
modal_footer() ?>