@extends('layout.app')

@section('title', 'T-Shirts')

@section('content')

@include('templates.headerProdutos')

<div class="Produtos-banner">
    <button class="filtro-box" id="filtroBox">
        <span class="seta-box">
            <i class="material-icons seta-filtro">chevron_left</i>
        </span>
        <span class="iconsMeio">
            <i class="material-icons">filter_list</i>
            <p class="filtros-texto">Filtros</p>
        </span>

        <form class="filtro-formulario">
            <span class="labels">

                <label for="Tamanho" class="Heading-5">Tamanho</label>

                <label for="Tamanho" class="Heading-5">Cor</label>

                <label for="Tamanho" class="Heading-5">Preço</label>

            </span>
            <span class="Selects">
                <select id="Tamanho" value="---" onchange="filtros_tshirts()">
                    <option value="all">---</option>
                    <option value="pp">PP</option>
                    <option value="p">P</option>
                    <option value="m">M</option>
                    <option value="g">G</option>
                    <option value="gg">GG</option>
                </select>
                <select id="Cor" value="---" onchange="filtros_tshirts()">
                    <option >---</option>
                </select>
                <select id="Preco" value="---" onchange="filtros_tshirts()">
                    <option value="all">---</option>
                    <option value="1">Menos de R$ 100,00 </option>
                    <option value="2">De R$ 100,00 até R$ 199,99</option>
                    <option value="3">De R$ 200,00 até R$ 399,99</option>
                    <option value="4">Acima de R$ 400,00</option>
                </select>
            </span>
        </form>
    </button>
    <span class="Produts-banner-blend"></span>
    <img src="img/page_produtos/banner.jpg">
</div>
<section id="Produtos-catalogo">
    <div class="container">
        <main onload="scroll_tshirts()" class="block">

            @include('includes.produtos')

        </main>
        
        <div class="btn-load" style="display:none;">CARREGANDO OUTRAS...</div>

    </div>
</section>

<!--  Apenas temporário, já irei ajeitar -->

<style>
    header {
        margin-top:-5.3em !important;
    }
    .overflow{
        overflow: hidden;
    }
</style>



@stop