<div class="table-responsive">
	<table class='table table-striped'><?php
		foreach($this->system->get_all_modules() as $modulo) { ?>
			<tr class=''>
				<td><strong><?= $modulo['module']->label ?></strong></td>
				<td><?php
					foreach($modulo['submodules'] as $submodulo) { ?>
						<label class="checkbox-inline">
							<input class='icheck' <?= exist($submodulo->id, $permissions, 'checked'); ?>  name="permissoes[]" type="checkbox" value="<?= $submodulo->id ?>"> <?= $submodulo->label ?>
						</label><?php
					} ?>
				</td>
			</tr>
			<?php
		} ?>
	</table>
</div>
<div class="spacer"></div>
