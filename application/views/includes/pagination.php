<!-- Botoes da paginação -->
<div class="row pag">
	<div class="col-sm-6">
		<div class="btn-group">
			<a data-url="<?= $this->my_pagination->getPath(1) ?>" class="btn btn-default">&laquo; Primeira</a>
		
			<?php if(($this->my_pagination->getPagAtual() - 4) > 0 && $this->my_pagination->getPagAtual() == $this->my_pagination->getTotalPags() && $this->my_pagination->getTotalPags() >= 5) { ?>
				<a class="btn btn-default" data-url="<?= $this->my_pagination->getPath($this->my_pagination->getPagAtual() - 4); ?>"><?= $this->my_pagination->getPagAtual() - 4?></a>
			<?php } ?>
			<?php if(($this->my_pagination->getPagAtual() - 3) > 0 && $this->my_pagination->getPagAtual() >= ($this->my_pagination->getTotalPags() - 1) && $this->my_pagination->getTotalPags() >= 4) { ?>
				<a class="btn btn-default" data-url="<?= $this->my_pagination->getPath($this->my_pagination->getPagAtual() - 3); ?>"><?= $this->my_pagination->getPagAtual() - 3?></a>
			<?php } ?>
			<?php if(($this->my_pagination->getPagAtual() - 2) > 0 && $this->my_pagination->getPagAtual() >= 3 && $this->my_pagination->getTotalPags() >= 3) { ?>
				<a class="btn btn-default" data-url="<?= $this->my_pagination->getPath($this->my_pagination->getPagAtual() - 2); ?>"><?= $this->my_pagination->getPagAtual() - 2?></a>
			<?php } ?>
			<?php if(($this->my_pagination->getPagAtual() - 1) > 0 && $this->my_pagination->getPagAtual() >= 2 && $this->my_pagination->getTotalPags() >= 2) { ?>
				<a class="btn btn-default" data-url="<?= $this->my_pagination->getPath($this->my_pagination->getPagAtual() - 1); ?>"><?= $this->my_pagination->getPagAtual() - 1?></a>
			<?php } ?>

			<a data-url="<?= $this->my_pagination->getPath($this->my_pagination->getPagAtual()) ?>" class="btn btn-primary"><?= $this->my_pagination->getPagAtual() ?></a>
		
			<?php if($this->my_pagination->getPagAtual() < $this->my_pagination->getTotalPags()) { ?>
				<a class="btn btn-default" data-url="<?= $this->my_pagination->getPath($this->my_pagination->getPagAtual() + 1); ?>"><?= $this->my_pagination->getPagAtual() + 1?></a>
			<?php } ?>
			<?php if($this->my_pagination->getPagAtual() < ($this->my_pagination->getTotalPags() - 1)) { ?>
				<a class="btn btn-default" data-url="<?= $this->my_pagination->getPath($this->my_pagination->getPagAtual() + 2); ?>"><?= $this->my_pagination->getPagAtual() + 2?></a>
			<?php } ?>
			<?php if($this->my_pagination->getPagAtual() < ($this->my_pagination->getTotalPags() - 2) && $this->my_pagination->getPagAtual() <= 2) { ?>
				<a class="btn btn-default" data-url="<?= $this->my_pagination->getPath($this->my_pagination->getPagAtual() + 3); ?>"><?= $this->my_pagination->getPagAtual() + 3?></a>
			<?php } ?>
			<?php if($this->my_pagination->getPagAtual() < ($this->my_pagination->getTotalPags() - 3) && $this->my_pagination->getPagAtual() < 2) { ?>
				<a class="btn btn-default" data-url="<?= $this->my_pagination->getPath($this->my_pagination->getPagAtual() + 4); ?>"><?= $this->my_pagination->getPagAtual() + 4?></a>
			<?php } ?>

			<a data-url="<?= $this->my_pagination->getPath($this->my_pagination->getTotalPags()) ?>" class="btn btn-default">Última &raquo;</a>
		</div>
	</div>
	<div class="col-sm-6 text-right">
		<?php $s = ($registros->total == 1)? '' : 's'; ?>
		<strong><?= number_format($registros->total, 0, '', '.') ?></strong> registro<?= $s ?> encontrado<?= $s ?>
	</div>
</div>
