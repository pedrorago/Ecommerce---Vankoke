@extends('layout.app')

@section('title', 'Lookbook')

@section('content')

@include('templates.headerLookbook')
<section id="Lookbook">
	<div class="container">
		<?php
		if (isset($_GET['colecao'])) {
			$colecao = $_GET['colecao'];
		} else {
			$colecao = 2017;
		}
		?>
		<h1>Coleção <?= $colecao; ?></h1>
		<main onload="carrega_colecao(<?= $colecao; ?>)" class="lookbook"></main>
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