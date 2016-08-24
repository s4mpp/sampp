	<aside class="main-sidebar">
		<section class="sidebar">
			<ul class="sidebar-menu">
				<li class="treeview <?= active('dashboard', 1); ?>">
					<a href="<?= base_url('dashboard') ?>">
						<i class="fa fa-dashboard"></i> <span>Dashboard</span> <i class="fa fa-angle-right pull-right"></i>
					</a>
				</li><?php
				foreach($this->menu_itens as $menu) { ?>
					<li class="treeview <?= active($menu['module']->value, 1); ?>">
						<a href="#">
							<i class="<?= $menu['module']->icone ?>"></i> <span><?= $menu['module']->label ?></span> <i class="fa fa-angle-right pull-right"></i>
						</a>
						<ul class="treeview-menu"><?php
							foreach($menu['submodules'] as $submenu) { ?>
								<li class="<?= active($submenu->controller, 2); ?>"><a href="<?= base_url($menu['module']->value.'/'.$submenu->controller.'/'.$submenu->metodo_principal) ?>"><i class="glyphicon glyphicon-menu-right"></i> <?= $submenu->label ?></a></li><?php
							} ?>
						</ul>
					</li><?php
				} ?>
			</ul>
		</section>
	</aside>