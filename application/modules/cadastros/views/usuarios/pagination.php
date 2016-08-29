<div class="table-responsive">
	<table class="table table-bordered table-hover table-striped">
		<thead class="bg-primary">
			<tr>
				<th>Nome</th>
				<th>Obs.</th>
				<th>Cadastrado em</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody><?php
			foreach($registros->registros AS $registro) {?>
				<tr data-click="<?= base_url('cadastros/usuarios/cadastro/'.encode($registro->id)) ?>">
					<td><?= $registro->nome ?></td>
					<td><?= $registro->obs ?></td>
					<td><?= date('d/m/Y H:i', strtotime($registro->data_hora_add)) ?></td>
					<td><?= $registro->status ?></td>
				</tr><?php
			}?>
		</tbody>
	</table>
</div>
<?php
$this->load->view('includes/pagination') ?>