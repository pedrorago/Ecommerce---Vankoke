<section id="Produtos-header">
	<div class="Produtos-navbar">
		<div class="container">
			<ul>
				<?php
				$categorias_lookbook = $data['lookbook']['categorias'];
				foreach ($categorias_lookbook as $key => $value):
					if (isset($_GET['colecao'])) {
						
						if ($_GET['colecao'] == $value['nome']) {
							$active = "activeLink";
						} else {
							$active = "";
						}

					} else if($key == 0){
						$active = "activeLink";
					} else {
						$active = "";
					}
				?>
				<li><a href="lookbook?colecao=<?= $value['nome']; ?>" class="navbar-link <?= $active; ?>"><?= $value['nome']; ?></a></li>
				<?php
				endforeach;
				?>
			</ul>
		</div>
	</div>
</section>