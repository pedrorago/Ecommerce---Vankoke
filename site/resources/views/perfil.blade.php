@extends('layout.app')

@section('title', 'Perfil')

@section('content')

@include('templates.headerMinhaConta')

<section id="Perfil">
	<div class="container">
		<main class="perfil-pedidos">
			<div class="pedidos-boxleft">
				<h1 class="Display-1">Meus pedidos</h1>

				<?php
				foreach ($pedidos as $key => $value):
				?>

				<div class="perfil-pedidosLinhas">
					<div class="box-pedidos">
						<span class="pedidos-desc">
							<p>Data de pedido: <span class="bold"><?= $value['data']; ?></span></p>
							<p>Número do pedido: <span class="bold"><?= $value['codigo']; ?></span></p>
							<p>Entrega para: <span class="bold"><?= $value['endereco']; ?>, <?= $value['numero']; ?> <?= $value['cidade']; ?>/<?= $value['uf']; ?></span></p>
						</span>
						<span class="pedidos-status">
							<button class="btn-status <?= $value['status']['class']; ?>"><?= $value['status']['nome']; ?></button>
						</span>
					</div>
					<?php
					if ($value['rastreio'] == true):
					?>
					<a href="http://www.linkcorreios.com.br/?id=<?= $value['codigo_rastreio']; ?>" target="parent" class="btn-rastrear" target="_blank">Rastrear pedido</a>
					<?php
				    endif;
				    ?>
				</div>

				<?php
				endforeach;
				?>
			</div>
			<div class="pedidos-boxright">
				<h1 class="Display-1">Ver outros produtos</h1>
				<div class="outros-produtos">
					<?php

					$produtos = $data['produtos_aleatorios'];

					foreach ($produtos as $key => $value):
					?>
					
					<div class="produtos-box">
						<span class="produtos-img">
							<img src="<?= $value['imagem']; ?>">
							<span class="produto-blend"></span>
							<i class="icon-eye"></i>
						</span>
					</div>
					
					<?php
					endforeach;
					?>
				</div>
			</div>
		</main>
		<main class="perfil-dados">
			<h1 class="Display-1">Meus dados</h1>
			<form class="perfil-dados-form" method="post" onsubmit="return false;">
				<div class="perfil-dados-pessoais">
					<h2 class="Display-3">Dados pessoais</h2>
					<label for="email" class="label label50">Email</label>
					<label for="nome" class="label label50">Nome completo</label>
					<input type="email"  id="email" name="email" class="input input50" value="<?= $dados['perfil']['email']; ?>">
					<input type="text" id="nome" name="nome" class="input input50" value="<?= $dados['perfil']['nome_completo']; ?>">
					<label for="cpf" class="label label50">Cpf</label>
					<label for="telefone" class="label label50">Telefone</label>
					<input type="text" class="input input50" id="cpf" name="cpf" value="<?= $dados['perfil']['cpf']; ?>" data-mask="000.000.000-00">
					<input type="tel" class="input input50 input-telefone" id="telefone" name="telefone" value="<?= $dados['perfil']['telefone']; ?>">
					<label class="label label100" for="data">Data de nascimento *</label>
					<select name="dia" class="input" id="dia">
						<option value="">Dia</option>
						<?php 
						$dia = 1;
						while($dia <= 31):
						?>
						<option value="<?=$dia?>" <?= ($dados['perfil']['dia_nascimento'] == $dia) ? "selected" : "";?>><?=$dia?></option><?php
						$dia++;
						endwhile;
						?>
					</select>
					<select name="mes" class="input" id="mes">
						<option value="">Mês</option>
						<?php 
						$mes = 01;
						while($mes <= 12):
							?><option value="<?=$mes?>" <?= ($dados['perfil']['mes_nascimento'] == $mes) ? "selected" : "";?>><?=$mes?></option><?php
						$mes++;
						endwhile;
						?>
					</select>
					<select name="ano" class="input" id="ano">
						<option value="">Ano</option>
						<?php 
						$ano = 2014;
						while($ano >= 1800):
							?><option value="<?=$ano?>" <?= ($dados['perfil']['ano_nascimento'] == $ano) ? "selected" : "";?>><?=$ano?></option><?php
						$ano--;
						endwhile;
						?>
					</select>
				</div>
				<div class="perfil-dados-endereco">
					<h2 class="Display-3">Endereço</h2>
					<label for="cep" class="label label50">Cep</label>
					<label for="Endereco" class="label label50">Endereço</label>
					<input type="text" class="input input50" id="cep" name="cep" value="<?= $dados['entrega']['cep']; ?>" data-mask="00000-000" onkeyup="busca_cep(this.value)">
					<input type="text" class="input input50 endereco" id="endereco" name="endereco" value="<?= $dados['entrega']['endereco']; ?>">
					<label for="numero" class="label width20">Número</label>
					<label for="complemento" class="label width80">Complemento</label>
					<input type="text" class="input width20" id="numero" placeholder="000" name="numero" value="<?= $dados['entrega']['numero']; ?>">
					<input type="text" class="input width80" id="complemento" name="complemento" value="<?= $dados['entrega']['complemento']; ?>">
					<label for="bairro" class="label label50">Bairro</label>
					<label for="lugar" class="label label50">Cidade/Estado</label>
					<input type="text" class="input input50 bairro" placeholder="Seu bairro" name="bairro" id="bairro" value="<?= $dados['entrega']['bairro']; ?>">
					<input type="text" class="input input50 lugar" placeholder="Digite sua cidade/estado" name="lugar" id="lugar" value="<?= $dados['entrega']['lugar']; ?>">
				</div>
				<div class="perfil-dados-senha">
					<h2 class="Display-3">Alterar a senha de acesso</h2>
					<label for="senhaAntiga" class="label label50">Antiga senha</label>
					<label for="senhaNova" class="label label50">Nova senha</label>
					<input type="password" class="input input50" placeholder="Digite sua antiga senha" name="senha_antiga" value="">
					<input type="password" class="input input50" placeholder="Digite sua nova senha" name="senha_nova" value="">
				</div>
				<div class="perfil-dados-botao">
					<button class="btn-secondary" type="submit">Salvar as alterações</button>
				</div>
			</form>
		</main>
	</div>
</section>


@stop
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