<?= $this->load->view('includes/head.php') ?>
<body class="hold-transition skin-blue sidebar-mini">
	<div class="wrapper">
		<header class="main-header">
			<a href="<?= base_url() ?>" class="logo">
				<span class="logo-mini"><img src="<?= base_url('assets/img/logo-white-small.png') ?>"></span>
				<span class="logo-lg"><img src="<?= base_url('assets/img/logo-white.png') ?>"></span>
			</a>
			<nav class="navbar navbar-static-top" role="navigation">
				<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button" id="fixnav" data-href="<?php // base_url('navfix') ?>">
					<span class="sr-only"></span>
				</a>
				<div class="navbar-custom-menu">

					<ul class="nav navbar-nav">
						<li class='btn_user'>
							<a href="<?= base_url('alterarsenha') ?>" data-toggle='tooltip' data-placement='bottom' title='Alterar senha'>
								<i class="glyphicon glyphicon-user"></i>
								<?= $this->session->userdata('nome'); ?>
							</a>
						</li>
						<li class='btn_sair'>
							<a href="<?= base_url('login/index/sair') ?>">
								<i class="glyphicon glyphicon-log-out"></i> Sair
							</a>
							
						</li>
					
					</ul>
				</div>
			</nav>
		</header>