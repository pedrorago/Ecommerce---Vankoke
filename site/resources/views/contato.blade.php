@extends('layout.app')

@section('title', 'Contato')

@section('content')

<section id="BannerInstitucional">
	<span class="Institucional-blend"></span>
	<!-- Crop Livre -->
	<img src="img/institucional/Mask.png">
	<!-- Crop Livre -->
	<h1 class="Display-1">Contato</h1>
</section>
<section id="ContatoPage">
	<div class="container">
		<main>
			<div class="contatoForm-container">
				<h4>Adoramos ouvir nossos clientes e parceiros. Qualquer dúvida, sugestão ou crítica serão bem vindas.</h4>
				<form method="post" onsubmit="return false;" class="form-contato">
					<div class="contato-box1">
						<label class="label" for="nome">Nome</label>
						<input type="text" name="nome" id="nome" class="input" placeholder="Seu nome">
						<label class="label" for="email">Email</label>
						<input type="email" name="email" id="email" class="input" placeholder="email@email.com">
						<label class="label" for="assunto">Assunto</label>
						<input type="text" id="assunto" class="input" name="assunto" placeholder="Entregas, dúvidas, sugestões">
						<button type="submit" class="btn-secondary">Enviar</button>
					</div>
					<div class="contato-box2">
						<label class="label" for="mensagem">Mensagem</label>
						<textarea class="input" id="mensagem" name="mensagem" placeholder="Digite seu texto aqui"></textarea>
					</div>
				</form>
			</div>
			<div class="contatoMapa">
				<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3969.2690068828456!2d-35.233983585232785!3d-5.817632795784478!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x7b255474c048e0f%3A0xc85669f842a340de!2sVankoke!5e0!3m2!1spt-BR!2sbr!4v1508068749002" width="100%" height="100%" frameborder="0" style="border:0" allowfullscreen></iframe>
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