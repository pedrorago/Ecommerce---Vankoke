@extends('layout.app')

@section('content')

@include('templates.headerProdutos')

<section id="ProdutoSingle">
	<div class="container">
		<main>
			<div class="produto-view">
				<img src="<?= $produto['imagem']; ?>" id="photos-view1">
				<?php

				$adicionais = $produto['imagens_adicionais'];

				foreach ($adicionais as $key => $value):
					?>

				<img src="<?= $value['imagem']; ?>" id="photos-view<?= $key + 2; ?>">

				<?php

				endforeach;

				?>
			</div>
			<div class="produto-photos">
				<span class="produtos-photos-box produtos-photos-active" id="photos-1"><img src="<?= $produto['imagem']; ?>"></span>
				<?php

				$adicionais = $produto['imagens_adicionais'];

				foreach ($adicionais as $key => $value):
					?>
				
				<span class="produtos-photos-box" id="photos-<?= $key + 2; ?>">
					<img src="<?= $value['imagem']; ?>">
				</span>	

				<?php

				endforeach;

				?>
			</div>
			<div class="produto-details">
				<h1 class="Heading-2"><?= $produto['nome']; ?></h1>
				<p class="H2-Custom produto-parcela">6x de <?= $produto['preco_dividido']; ?></p>
				<p class="H2-Custom produto-preco"><?= $produto['preco']; ?></p>

				<span class="selects-produto">
					<label for="tamanhoProduto" class="label">Tamanho</label>
					<label for="quantidadeProduto" class="label">Quantidade</label>
					<label for="corProduto" class="label corProduto">Cor</label>
					<select name="Tamanho" class="input" id="tamanhoProduto">
						<option value="p">P</option>
					</select>
					<select name="Quantidade" class="input" id="quantidadeProduto">
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
					</select>
					<!-- <select name="corProduto" id="corProduto" class="input" id="corProdut">
						<option class="Amarelo"></option>
					</select> -->
					<div class="input corSelect"><p>▼</p>
						<span class="corSelecionada" style="background-color:#000000"></span>

						<ul class="corOptions">
							<li class="000000">
								<span class="corOpcao" style="background-color:#000000"></span>
							</li>
							<li class="0000FF">
								<span class="corOpcao" style="background-color:#0000FF"></span>
							</li>
							<li class="4B0082">
								<span class="corOpcao" style="background-color:#4B0082"></span>
							</li>
						</ul>
					</div>


				</span>
				<button class="btn-secondary btn-add-carrinho" onclick="adicionar_produto(<?= $produto['id']; ?>)">Adicionar ao carrinho</button>
				<h4 class="Display-3">Detalhes do produto</h4>
				<div class="descricao Heading-4">
					<?= $produto['descricao']; ?>
				</div>
				<div class="tags-container">
					<?php
					$tags = $produto['tags'];
					foreach ($tags as $key => $value):
						?>
					<span class="tags-box"><?= $value; ?></span>
					<?php
					endforeach;
					?>
				</div>
			</div>
		</main>


		<div class="relacionados">
			<h2 class="Display-2">Roupas relacionadas</h2>
			@include('includes.produtosRelacionados')
		</div>
	</div>
</section>

@stop

<style>
	header {
		margin-top: -6.3em !important;
	}
	.overflow{
		overflow: hidden;
	}	
	#Produtos-header {
		margin-top: -3.5em !important;
	}
	@media(max-width: 425px) {
		.Produtos-navbar {
			height: 8.5em !important;
		}
		.Produtos-navbar li {
			height: 32% !important;
			width: 18% !important;
		}
	}
	
</style>