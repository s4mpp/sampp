<body class="hold-transition login-page">
	<div class="login-box box box-primary">
		<div class="login-box-body">
			<img class="box-header img-responsive" src="<?= base_url('assets/img/logo.png') ?>">
			<hr>
			<h2>Login</h2><?=
			form_open(null, array('data-action' => base_url('login/validate'))); ?>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
						<input autocomplete="off" name="login" class="form-control" placeholder="Usuário" type="text" autofocus>
					</div>
				</div>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
						<input autocomplete="off" name="senha" class="form-control" placeholder="Senha" type="password">
					</div>
				</div>
				<button class="btn btn-primary" type="submit"><i class="glyphicon glyphicon-ok"></i> Entrar</button> <?php
			form_close() ?>
		</div>
	</div>
