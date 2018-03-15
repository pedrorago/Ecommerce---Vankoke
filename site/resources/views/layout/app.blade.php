<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>

    <?php
    if ($segmento = Request::segment(1) == "produto"):
    ?>

    <title><?= $produto['nome']; ?></title>

    <?php
    else:
    ?>

    <title>Vankoke - @yield('title')</title>

    <?php
    endif;
    ?>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <meta author="Mangue Tecnologia">

    <link rel="icon" href="img/icons/icon.png">

    <base href="<?= $url_base; ?>">

    <meta property="og:type" content="website">

    <?php
    if ($segmento = Request::segment(1) == "produto"):
    ?>

    <meta property="og:image" content="<?= $url_base.$produto['imagem']; ?>">

    <?php
    else:
    ?>
    
    <meta property="og:image" content="<?= $url_base; ?>img/icons/logo-black.svg">

    <?php
    endif;
    ?>
    <meta name="keywords" value="<?= $data['info']['keywords']; ?>">
    <meta name="description" value="<?= $data['info']['descricao']; ?>">

    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Encode+Sans+Semi+Condensed:100,200,300,400,500,600,700,800,900|Playfair+Display:400,700,900">
    <link rel="stylesheet" type="text/css" href="/css/plugins/slick.css"/>
    <link rel="stylesheet" type="text/css" href="/css/plugins/slick-theme.css"/>

    <link rel="stylesheet" href="/css/reset.css">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/responsive.css">

</head>

<body>
    <span class="carrinho-box" onclick="carrinho()">
        <img src="img/icons/basket.svg">
        <span class="carrinhoHave" style="display: none;"></span>
    </span>
    <form class="formCarrinho">
        <span class="seta"></span>
        <div class="produtos-carrinho-container">
            
        </div>
        <div class="carrinho-cep">
            <label for="cepCarrinho" class="label">Estime o valor da sua entrega</label>
            <input type="text" name="cepCarrinho" class="input" id="cepCarrinho" placeholder="Digite seu CEP" data-mask="00000-000">
            <button class="btn-secondary btnFrete" type="button" onclick="calcula_frete()">Ok</button>
            <a href="javascript:void(0)" class="btn-three" onclick="finalizar_compra()">Finalizar compra</a>
        </div>
    </form>

    <?php
    if (Auth::check() == true) {
        echo '<a href="javascript:void(0)" class="navbar-link minhaContaLinkDisable" onclick="chama_boasVindas()">Minha Conta</a>';
    } else {
        echo '<a href="javascript:void(0)" class="navbar-link minhaContaLink">Minha Conta</a>';
    }
    ?>
    <form class="formLogin" onsubmit="return false;" method="post">
        <span class="seta"></span>
        <h2 class="H2-Custom">Acessar minha conta</h2>
        <label for="email" class="label">E-mail</label>
        <input type="email" class="input" name="email" id="email">
        <label for="senha" class="label">Senha de acesso</label>
        <input type="password" name="senha" id="senha" class="input">
        <p class="label cadastroConfirmar">O cadastro não foi confirmado, <a href="javascript:void(0)" onclick="reenviar_email()" class="reenviar_email" data-email="'+$('.email').val()+'">Reenviar email</a></p>
        <a href="javascript:void(0)" class="Display-4 recuperarSenha">Esqueci minha senha</a>
        <a href="javascript:void(0)" class="Display-4 cadastrarMe">Não sou cadastrado</a>
        <button type="submit" class="btn-three">Entrar</button>
    </form>
    <form class="formRecuperar" onsubmit="return false;" method="post">
        <span class="seta"></span>
        <h2 class="H2-Custom">Recuperar senha</h2>
        <p>Você receberá as instruções de recuperação de senha no e-mail cadastrado.</p>
        <label for="email" class="label">E-mail cadastrado</label>
        <input type="email" class="input" name="email" id="email">
        <p class="label recuperarP">Esse email não existe em nossos registros.</p>
        <p class="label recuperarSuccess">As instruções foram enviadas com sucesso!</p>
        <p class="label recuperarError">Ocorreu um erro. Tente novamente.</p>
        <button type="submit" class="btn-three recuperarButton">Enviar</button>
        <button type="button" class="btn-secondary recuperarVoltar">Voltar</button>
    </form>
    <form class="formCadastro" onsubmit="return false;" method="post">
        <span class="seta"></span>
        <h2 class="H2-Custom">Cadastrar-me na Vankoke</h2>
        <label for="email" class="label">E-mail</label>
        <input type="email" class="input" name="email" id="email">
        <label for="nome" class="label">Nome</label>
        <input type="text" class="input" name="nome_completo" id="nome">
        <label for="senha" class="label">Senha</label>
        <input type="password" name="senha" id="senha" class="input cadastroSenha">
        <button type="submit" class="btn-three cadastroButton">Cadastrar-me</button>
        <button type="button" class="btn-secondary cadastrarVoltar">Voltar</button>
        <p class='label errorEmail'>Email já cadastrado</p>
        <p class='label emailSucess'>Cadastrado com sucesso! </p>
    </form>
        <form class="formLogado" onsubmit="return false;" method="post">
        <span class="seta"></span>
        <h2 class="H2-Custom">Olá, <?= Auth::user()->nome_completo; ?></h2>
        <a href="/perfil" class="btn-three logadoButton">Meus pedidos</a>
        <a href="/perfil" class="btn-three logadoButton">Editar meus dados</a>
        <a href="/logout" class="btn-secondary formLogadoVoltar">Sair</a>
    </form>
    <div class="content">

        @include('templates.header')

        @yield('content')

        @include('templates.footer')
    </div>

    <script  src="/js/jquery-3.2.1.min.js" ></script>
    <script  src="/js/plugins/jquery.validate.js" ></script>
    <script  src="/js/plugins/slick.min.js" ></script>
    <script  src="/js/plugins/jquery.mask.js" ></script>


    <script  src="/js/slide.js" async></script>
    <script  src="/js/app.js" async></script>

       <!--  <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <button type="button" class="fechar" data-dismiss="modal"><span>&times;</span></button>
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"></h4>
                    </div>
                    <div class="modal-body"></div>
                </div>
            </div>
        </div> -->
        
    </body>
    </html>