@extends('layout.app')

@section('title', 'Institucional')

@section('content')

<section id="BannerInstitucional">
	<span class="Institucional-blend"></span>
	<!-- Crop Livre -->
	<img src="img/institucional/Mask.png">
	<!-- Crop Livre -->
	<h1 class="Display-1">A Vankoke</h1>
</section>

<section id="Institucional">
	<div class="container">
		<main>
			<div class="Institucional-texto">
				<h4>Com a visão arrojada e contemporânea, a VANKOKE reformulou a marca investindo ainda mais em tecnologia, no seu quadro pessoal mais qualificado, reconhecido e muito valorizado pelas clientes e consumidores do nosso produto.</h4>
				<p>A VANKOKE, composta por fábrica, loja própria e loja virtual atua desde 2004 com foco no mercado de moda feminina diferenciada. Está reforçando sua expansão com vistas a atender o território nacional através de lojas multimarcas e a partir de 2014 com loja virtual voltada para atender ao varejo.

					O processo constante de expansão da VANKOKE está alinhado com a busca pela eficiência no relacionamento com os clientes, no bem estar de seus colaboradores e nos investimentos em equipamentos e tecnologia. Essa é uma regra que determina o crescimento e o sucesso da VANKOKE. É uma consequência do trabalho, aliado à criatividade e ao espirito empreendedor, que fortalece e consolida a marca.
					Incorporada a uma rede de parceiros e de fornecedores, a VANKOKE utiliza matéria prima, design e estampas exclusivas, possibilitando o desenvolvimento de um trabalho consistente, dentro do conceito de ``Fast Fashion´´.</p>
				</div>
				<div class="Institucional-fotos">
					<div class="institucional-slide">
						<div class="galeria">
							<div class="slider-for">
								<?php

								$galeria = $data['galeria_institucional'];

								foreach ($galeria as $key => $value):
								?>
								<div class="slider-for__item">
									<div class="galeria-center">
										<img src="<?= $value['imagem'];?>">
									</div>
								</div>
								<?php
								endforeach;
								?>
							</div>
							<div class="slider-nav-thumbnails">
								<?php

								$galeria = $data['galeria_institucional'];

								foreach ($galeria as $key => $value):
								?>
								<div class="slider-min item-nav">
									<img src="<?= $value['imagem'];?>" style="height: 87px;">
								</div>
								<?php
								endforeach;
								?>
							</div>
						</div>
					</div>
				</div>
			</main>
		</div>
	</section>
	@stop
	<style>
		header {
			margin-top: 0 !important;
		}
	</style>