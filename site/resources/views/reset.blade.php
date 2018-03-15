@extends('layout.app')

@section('title', 'Resetar senha')

@section('content')

<section id="Perfil">
	<div class="container">
		<main class="perfil-dados" style="display: block;">
			<h1 class="Display-1">Meus dados</h1>
			<form class="perfil-dados-reset" method="post" onsubmit="return false;">
				<div class="perfil-dados-senha">
					<h2 class="Display-3">Alterar a senha de acesso</h2>
					<label for="senhaAntiga" class="label label50">Nova senha</label>
					<label for="senhaNova" class="label label50">Confirmar senha</label>
					<input type="hidden" name="email" value="{{ app('request')->input('email') }}">
                    <input type="hidden" name="token" value="{{ app('request')->input('tk') }}">
					<input type="password" class="input input50" placeholder="Digite sua antiga senha" id="senha_nova" name="senha_nova" value="">
					<input type="password" class="input input50" placeholder="Digite sua nova senha" name="confirmar_senha" value="">
				</div>
				<div class="perfil-dados-botao">
					<button class="btn-secondary" type="submit">Salvar as alterações</button>
				</div>
			</form>
		</main>
	</div>
</section>

<style>
	header {
		margin-top: -5.3em !important;
	}
	.overflow{
		overflow: hidden;
	}
	.Produtos-navbar ul {
		justify-content: flex-start !important;
	}	

</style>

@stop

