<h1 class="text-warning">Permissão <strong>negada</strong>!</h1>
<h3>A página que você está tentando acessar está indisponível porque você não tem permissão para acessá-la. Por favor, contate o administrador do sistema.</h3>
<?php if(isset($_SERVER['HTTP_REFERER'])) { ?>
	<a href="<?= $_SERVER['HTTP_REFERER'] ?>" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-chevron-left"></i> Voltar</a><?php
} ?>
<a href="<?= base_url('login/sair') ?>" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-log-out"></i> Sair</a>