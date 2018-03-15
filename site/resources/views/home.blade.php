@extends('layout.app')

@section('title', 'Home')

@section('content')

<section id="BannerHome">

	<span class="blend-banner"></span>
	<div class="slide">
		<?php
		$sliders = $data['slider'];
		foreach ($sliders as $key => $value):
			?>
		<div class="slide-imgbox">
			<img src="<?= $value['img']; ?>">
			<div class="container">
				<h1 class="Display-1"><?= $value['titulo']; ?></h1>
				<?php
				if (!empty($value['url'])):
					?>
				<a href="<?= $value['url']; ?>" class="btn-primary large">Ver mais</a>
				<?php
				endif;
				?>
			</div>
		</div>
		<?php
		endforeach;
		?>
	</div>
</section>
<section id="Colecao">
	<div class="container">
		<div class="colecao-container">
			<div class="colecao-img">
				<img src="img/banners/banner.jpg">
			</div>
			<div class="colecao-texto">
				<h2 class="H2-Custom">Coleção 2017.2</h2>
				<p class="Paragraph">Um texto sobre a coleção vigente  um conteúdo interessante sobre a criação da coleção. But it is safe to say that at some point on our lives, each and every one of us has that moment when we are suddenly stunned when we come face to face with the enormity of the universe that we see in the night sky. For many of us who are city dwellers, we don’t really notice that sky up there on a routine basis. The lights of the city do.</p>
			</div>
		</div>
	</div>
</section>
<section id="MaisVendidas">
	<div class="container">
		<h2 class="Display-2">Mais Recentes</h2>
		<main>
			@include('includes.recentes')
			
		</main>
		<div class="botao-everybody">
		<a href="/produtos" title="Todos os produtos" class="btn-secondary btn-seeEverybody">Ver todas</a>
		</div>
	</div>
</section>
<section id="Contato">
	<main>
		<form class="newsLetter newsletter-form">
			<h2 class="Display-2">Receba as últimas novidades</h2>
			<label for="email" class="label">Seu email</label>
			<input type="email" name="email" id="email" placeholder="Digite seu email" class='input'>
			<div class="response"></div>
			<button type="submit" class="btn-secondary">Quero receber</button>
		</form>
		<div class="mapa">
			<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15877.07602741236!2d-35.2317949!3d-5.8176328!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xc85669f842a340de!2sVankoke!5e0!3m2!1spt-BR!2sbr!4v1507938722299" width="100%" height="100%" frameborder="0" style="border:0" allowfullscreen></iframe>
		</div>
	</main>
</section>
@stop

