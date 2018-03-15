@extends('layout.app')

@section('title', 'Checkout')

@section('content')

@include('templates.headerCheckout')

<section id="Checkout">
	<div class="container">
		<main>
			<div class="box-esquerdaC">
				<form method="post" onsubmit="return false;" class="form-checkout-dados">
					<label for="email" class="label">Email *</label>
					<label for="nome" class="label">Nome completo * </label>
					<input type="email" class="input" name="email" id="email" placeholder="email@email.com" value="<?=(Auth::check() == true) ? Auth::User()->email : ""; ?>">
					<input type="text" class="input" name="nome" id="nome" placeholder="Digite seu nome" value="<?=(Auth::check() == true) ? Auth::User()->nome_completo : ""; ?>">
					<label for="cpf" class="label">Cpf</label>
					<label for='telefone' class="label">Telefone</label>
					<input type="text" class="input" name="cpf" id="cpf" placeholder="000.000.000-00" data-mask="000.000.000-00" value="<?=(Auth::check() == true) ? Auth::User()->cpf : ""; ?>">
					<input type="tel" class="input input-telefone" name="telefone" id="telefone" placeholder="(00) 99999-9999" value="<?=(Auth::check() == true) ? Auth::User()->telefone : ""; ?>">
					<label class="label label100" for="data">Data de nascimento *</label>
					<select name="dia" class="input" id="dia">
						<option value="">Dia</option>
						<?php 
						if (Auth::check() == false):
							$dia = 1;
						while($dia <= 31):
							?>
						<option value="dia"><?=$dia?></option><?php
						$dia++;
						endwhile;
						else:
							?>
						<?php 
						$dia = 1;
						while($dia <= 31):
							?>
						<option value="<?=$dia?>" <?= (Auth::User()->dia_nascimento == $dia) ? "selected" : "";?>><?=$dia?></option><?php
						$dia++;
						endwhile;
						endif;
						?>
					</select>
					<select name="mes" class="input" id="mes">
						<option value="">Mês</option>
						<?php
						if (Auth::check() == false): 
							$mes = 01;
						while($mes <= 12):
							?><option value="<?= $mes ?>"><?=$mes?></option><?php
						$mes++;
						endwhile;
						else:
							?>
						<?php 
						$mes = 01;
						while($mes <= 12):
							?>
						<option value="<?=$mes?>" <?= (Auth::User()->mes_nascimento == $mes) ? "selected" : "";?>><?=$mes?></option>
						]   <?php
						$mes++;
						endwhile;
						endif;
						?>
					</select>
					<select name="ano" class="input selectAno" id="ano">
						<option value="">Ano</option>
						<?php 
						if (Auth::check() == false): 
							$ano = 2014;
						while($ano >= 1800):
							?><option value="<?=$ano?>"><?=$ano?></option><?php
						$ano--;
						endwhile;
						else:
							?>
						<?php 
						$ano = 2014;
						while($ano >= 1800):
							?>
						<option value="<?=$ano?>" <?= (Auth::User()->ano_nascimento == $ano) ? "selected" : "";?>><?=$ano?></option>
						<?php
						$ano--;
						endwhile;
						endif;
						?>
					</select><br>
					
					<?php
					if (Auth::check() == false):
						?>
					
					<label for="senhaNew" class="label">Criar uma senha de acesso</label>
					<label for="senharRepeat" class="label">Repetir senha</label>
					<input type="password" class="input" name="senhaNew" id="senhaNew">
					<input type="password" class="input" name="senhaRepeat" id="senhaRepeat">

					<?php
					endif;
					?>

					<?php
					if (Auth::check() == true):
						?>

					<button class="btn-three" data-type="cl">Ir para entrega ></button>

					<?php
					else:
						?>

					<button class="btn-three" data-type="sl">Ir para entrega ></button>

					<?php
					endif;
					?>

				</form>
			</div>
			<div class="box-esquerdaE">
				<form method="post" onsubmit="return false;" class="form-checkout-entrega">
					<label for="cep" class="label boxE-width1">CEP *</label>
					<label for="endereco" class="label boxE-width1 endereco-label">Endereço *</label>
					<input type="text" name="cep" id="cep" placeholder="00000-000" class="input boxE-width1" data-mask="00000-000" value="<?= $endereco['cep']; ?>">
					<input type="text" name="endereco" id="endereco" placeholder="Rua dos Alfineiros" class="input boxE-width1" value="<?= $endereco['logradouro']; ?>">
					<label for="numero" class="label boxE-width2">Número *</label>
					<label for="complemento" class="label complemento-label boxE-width3">Complemento</label>
					<input type="text" name="numero" id="numero" class="input boxE-width2">
					<input type="text" name="complemento" id="complemento" class="input boxE-width3">
					<label for="bairro" class="label boxE-width1">Bairro</label>
					<label for="lugar" class="label boxE-width1 lugarForm">Cidade/Estado</label>
					<input type="text" name="bairro" class="input boxE-width1" placeholder="Digite seu bairro" value="<?= $endereco['bairro']; ?>">
					<input type="text" name="lugar" class="input boxE-width1" value="<?= $endereco['cidade'].'/'.$endereco['uf']; ?>">

					<p class="label plano-h2">Escolha o tipo de entrega</p>

					<input type="radio" class="inputRadio" name='tipo' value="normal" id="normal" <?= ($carrinho['frete']['tipo_frete_selecionado'] == "normal") ? "checked" : ""; ?>>
					<div class="planos">
						<label for="normal" class="label planoTitulo">Normal - <?= $carrinho['frete']['valor_pac_formatado']; ?></label>
						<label for="normal" class="label planoDesc">Prazo de até <?= $carrinho['frete']['prazo_pac']; ?> dia(s) para entrega do pedido.</label>
					</div>

					<input type="radio" class="inputRadio" name='tipo' value="expresso" id="expressa" <?= ($carrinho['frete']['tipo_frete_selecionado'] == "expresso") ? "checked" : ""; ?>>
					<div class="planos">
						<label for="expressa" class="label planoTitulo">Expressa - <?= $carrinho['frete']['valor_sedex_formatado']; ?></label>
						<label for="expressa" class="label planoDesc">Prazo de até <?= $carrinho['frete']['prazo_sedex']; ?> dia(s) para entrega do pedido.</label>
					</div>

					<button class="btnPagamento" type="submit">Ir para PAGAMENTO ></button>
					<a href="javascript:void(0)" class="btn-secondary btnPagamentoVoltar meusDadosPessoais">< Voltar para dados pessoais</a>
				</form>
			</div>
			<div class="box-esquerdaP">
				<form method="post" onsubmit="return false;" class="form-checkout-pagamento">
					<p class="label plano-h2">Escolha a forma de pagamento</p>
					<input type="radio" class="inputRadio inputCartao" name='planos' id="carto" value='cartao'>
					<div class="planos">
						<label for="normal" class="label planoTitulo">CARTÃO DE CRÉDITO</label>
						<label for="normal" class="label planoDesc">Você pode parcelar o pagamento em até 6x.</label>
					</div>

					<input type="radio" class="inputRadio inputBoleto" name='planos' id="boleto" value='boleto'>
					<div class="planos">
						<label for="expressa" class="label planoTitulo">BOLETO</label>
						<label for="expressa" class="label planoDesc">Pagamento a vista</label>
					</div>


					<p class="Display-3 desc-boleto ">O boleto bancário será exibido após a confirmação da compra e poderá ser impresso para pagamento em qualquer agência bancária, ou ter o número anotado para pagamento pelo telefone ou internet.</p>

					<span class="cartaoForm">
						<label for="nomeCartao" class="label boxE-width1">Nome impresso no cartão</label>
						<label for="cpfTitular" class="label boxE-width1 cpfTitularc">CPF do titular</label>
						<input type="text" class="input boxE-width1" name="nomeCartao" id="nomeCartao">
						<input type="text" class="input boxE-width1" name="cpfTitular" id="cpfTitular" data-mask="000.000.000-00">		

						<label for="mes" class="label validoLabel">Válido até</label>
						<label for="numeroCartao" class="label numeroCartao">Nº do cartão</label>
						<label for="codCartao" class="label codCartao codLabel">Cód. de seg.</label>

						<select name="mes" class="input mesCartao" id="mes">
							<option value="">MM</option>
							<?php 
							$mes = 01;
							while($mes <= 12):
								?>
							<option value="<?= $mes; ?>" ><?=$mes?></option><?php
							$mes++;
							endwhile;
							?>
						</select>
						<select name="ano" class="input anoCartao" id="ano">
							<option value="">Ano</option>
							<?php 
							$ano = 2017;
							while($ano <= 2030):
								?><option value="<?= $ano; ?>"><?=$ano?></option><?php
							$ano++;
							endwhile;
							?>
						</select>
						<input type="text" class="input numeroCartao" name="numeroCartao" id="numeroCartao">	
						<input type="text" class="input codCartao" name="codCartao" id="codCarta">
						<select name="parcela" class="input parcelaCartao" id="parcela">
							<option value="1">4x de 190,90</option><!--  PARCELA ATÉ 6 VEZES -->
						</select>
						<button href="javascript:void(0)" class="btnPagamento btnEfetuar">eFETUAR PAGAMENTO</button>
					</span>

					<a href="javascript:void(0)" class="btnPagamento btnConfirmar">Confirmar compra</a>
					<a href="javascript:void(0)" class="btn-secondary btnPagamentoVoltar VtEntrega">< Voltar para entrega</a>
				</form>
			</div>

			<div class="box-direitaC">
				<h2 class="Heading-3">Resumo da compra</h2>
				<div class="produtosResumo-container">
					<?php
					$produtos = $carrinho['items'];
					foreach ($produtos as $key => $value):
						?>
					<div class="resumo-box">
						<span class="resumo-img">
							<img src="<?= $value['imagem']; ?>">
						</span>
						<span class="resumo-desc">
							<h4><?= $value['nome']; ?></h4>
							<p><?= $value['preco']; ?></p>
						</span>
					</div>
					<?php
					endforeach;
					?>
				</div>
				<div class="produtosDesc-resumo">
					<p class='resumo-sub width50 resumo-font1'>Sub-total</p>
					<p class='resumo-sub-preco resumo-font1'>R$ <?= $carrinho['total_produtos']; ?></p>
					<?php
					if ($carrinho['frete']['check'] != true):
						?>
					<div class="frete-checkout">
						<p class='resumo-frete width50 resumo-font1'>Frete</p>
						<p class='resumo-frete-link resumo-link calculaCEP'>Calcular</p>
					</div>
					<?php
					else:
						?>
					<div class="frete-checkout">
						<p class='resumo-frete width50 resumo-font1'>Frete:  <?= $carrinho['frete']['valor_frete_formatado']; ?></p>
						<p class='resumo-frete-link resumo-link calculaCEP'>Trocar</p>
					</div>
					<?php
					endif;
					?>
					<form class='calculaCEPForm cepCupom'>
						<input type="text" class="input inputCepCupom cepResumo" placeholder="Digite seu CEP" id="cepCheckout" data-mask="00000-000">
						<button class="btn-secondary btnCepCupom btnCep" onclick="calcula_frete_checkout()" type="button">OK</button>
					</form>
					<?php
					if ($carrinho['cupom']['check'] != true):
						?>
					<p class='resumo-cupom width50 resumo-font1'>Cupom promocional</p>
					<p class='resumo-cupom-adicionar resumo-link adicionaCupom cupom-link'>Adicionar</p>
					<form class='cupomForm cepCupom'>
						<input type="text" class="input inputCepCupom cupomResumo" placeholder="Digite seu cupom" id="cupomCheckout" maxlength="4">
						<button class="btn-secondary btnCepCupom btnCupom" onclick="cupom_promocional()" type="button">OK</button>
					</form>
					<?php
					else:
						?>
					<p class='resumo-cupom width50 resumo-font1'>Cupom promocional</p>
					<p class='resumo-cupom-adicionar desconto-link'><?= 'R$ '.number_format($carrinho['cupom']['valor'], 2, ',', '.'); ?></p>
					<?php
					endif;
					?>

					<p class='resumo-total width50 resumo-negrito'>Total</p>
					<p class="resumo-total-preco resumo-negrito">R$ <?= number_format($carrinho['total_double'] - $carrinho['cupom']['valor'], 2, ',', '.'); ?></p>

					<a href="/produtos" class="btn-secondary">Voltar a loja</a>
				</div>
			</div>
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
	@media(max-width: 758px) {
		.Produtos-navbar {
			height: 2.5em !important;
		} 
		.Produtos-navbar ul {
			justify-content: space-between !important;
		}
		.Produtos-navbar li {
			height: 100% !important;
			margin-right: 0 !important;
		}
		.Produtos-navbar li:nth-child(1) {
			width: 6em
		}
		.Produtos-navbar li:nth-child(2) {
			width: 4em
		}
		.Produtos-navbar li:nth-child(3) {
			width: 5em
		}
	}
</style>