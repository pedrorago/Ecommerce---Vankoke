jQuery(function($){
    
    var MANGUE = window.LOAD || {};

    var host = window.location.hostname;

    if (host == "vankoke") {
        adm_path = "http://vankoke-adm/"
    } else {
        adm_path = "http://vankoke-adm.siritecnologia.com.br/"
    }
    menuAtivo();
    fechaMenu();
    
    MANGUE.preloader = function(){

        // verificar se vai ter
        
        /*$('.status').fadeOut();
        $('.preloader').delay(350).fadeOut('slow');
        $('body').delay(350).css({'overflow':'visible'});*/

    }

    MANGUE.fancybox = function(){
        $(".fancybox").fancybox();
    }

    MANGUE.slick = function(){

        // funções do slick criar aqui

        $('.slider-for').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: true,
            adaptiveHeight: false,
            asNavFor: '.slider-nav-thumbnails'
        });

        $('.slider-nav-thumbnails').slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            asNavFor: '.slider-for',
            dots: false,
            arrows: false,
            focusOnSelect: true,
            adaptiveHeight: false,
            variableWidth: true
        });

    }

    MANGUE.mascaras = function(){

        var FormataNumero = function (val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        },

        Options = {
            onKeyPress: function(val, e, field, options) {
                field.mask(FormataNumero.apply({}, arguments), options);
            }
        };

        $('.input-telefone').mask(FormataNumero, Options);

    }

    MANGUE.contato = function(){

        $('.form-contato').validate({

            rules: {
                email: { required: true, email: true },
                nome: { required: true },
                assunto: { required: true},
                mensagem: { required: true }
            },
            messages: {
                email: { required: 'Preencha o campo email', email: 'Insira um email válido' },
                nome: { required: 'Preencha o campo nome' },
                assunto: { required: 'Preencha o campo assunto'},
                mensagem: { required: 'Preencha o campo mensagem'}
            },

            submitHandler: function( form ){

                var dados = $('.form-contato');

                $.ajax({
                    type: "POST",
                    url: adm_path+"json/contato",
                    data: dados.serialize(),

                    beforeSend: function( data ) {

                        $(".form-contato button").text('Enviando...');

                        $(".form-contato button").prop('disable', true);

                    },

                    success: function( data ) {

                        // sucesso 200 trabalhar retorno...

                        if (data == 200) {

                            $('.input').val('');
                            
                            /*$('#modal').modal('show');

                            $('.modal-title').text('Enviado com sucesso');*/

                        } else {
                            // trabalhar erro aqui
                        }


                    },
                    error : function(){
                       // feedback de erro aqui generico
                   },
                   complete: function(){
                    $(".form-contato button").text('Enviar');
                    $(".form-contato button").prop('disable', false);
                },
            });

                return false;
            }

        });

    }

    MANGUE.login = function(){

        $(".formLogin").validate({

            errorClass: "error",

            rules: {
                email: {required: true, email: true},
                senha: {required: true}
            },
            messages: {
                email: {required: 'Campo requerido', email: 'Digite um email válido'},
                senha: {required: 'Campo requerido'}
            },

            submitHandler: function(form) {

                var dados = $('.formLogin');

                $.ajax({
                    type: "POST",
                    url: '/login',
                    data: dados.serialize(),

                    beforeSend: function() {

                        $('.formLogin .login-btn').text('entrando...');

                        $('input').prop('disabled', true);

                    },

                    success: function(data) {

                        // retirar se n for usar...

                        $('.modal-body').html('');

                        $('.response').text('');

                        if (data.status == 501) {

                            $('.formLogin').find('#email').css('border', '1px solid red');
                            $('.formLogin').find('#senha').css('border', '1px solid red');
                            
                            $('.formLogin').find('#email').on('click', function() {
                                $('.formLogin').find('#email').css('border', '1px solid rgba(110,110,110,0.4)');
                                $('.formLogin').find('#email').val('');
                            });

                            $('.formLogin').find('#senha').on('click', function() {
                                $('.formLogin').find('#senha').css('border', '1px solid rgba(110,110,110,0.4)');
                                $('.formLogin').find('#senha').val('');
                            });

                            $('.response').text('Email ou senha incorretos. Tente novamente.');

                        } else if (data.status == 502) {
                            // verificar se jogar em modal ou outra coisa
                            $('.response').text('Preencha os campos obrigatórios.');
                        } else if (data.status == 503) {

                            $(".cadastroConfirmar").fadeIn();
                            $('.response').html('O cadastro não foi confirmado, <a href="javascript:void(0)" onclick="reenviar_email()" class="reenviar_email" data-email="'+$('.email').val()+'">Reenviar email</a>');
                        } else if (data.status == 200 ) {

                            $('.H2-Custom').text('Olá, '+data.usuario);

                            $('.fechar').hide();
                            
                            $('input').val('');

                            $(".carrinho-box").toggle(100);
                            $(".formLogin").removeClass("formLoginOn");
                            $(".formRecuperar").removeClass("formRecuperarOn");
                            $(".formCadastro").removeClass("formCadastroOn");
                            $(".formLogin").find("#email").val('');
                            $(".formLogin").find("#senha").val('');
                            $(".formLogado").addClass("formLogadoOn");


                        } else {
                            $('.response').text('Ocorreu um erro interno, tente novamente...');
                        }

                        $('input').prop("disabled", false);

                    },

                    error:function (data){

                        $('.response').text('Ocorreu um erro interno, tente novamente...');
                        $('.formLogin .login-btn').text('entrar');

                        $('input').prop("disabled", false);

                    },

                    complete: function (data){
                        $('.formLogin .login-btn').text('entrar');
                    }

                });
}
});

$(".formLogin input").click( function(){
    $('.response').text("");
    $('.formLogin button').prop("disabled", false);
});

}

MANGUE.cadastro = function(){

    jQuery.validator.addMethod("alphanumeric", function(value, element) {
        return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
    }); 

    jQuery.validator.addMethod("upper", function(value, element) {
        return this.optional(element) || /[A-Z]+/.test(value);
    });

    $(".formCadastro").validate({

        errorClass: "error",

        rules: {
            nome: { required: true, minlength: 2 },
            email: {required: true, email: true},
            senha: {required: true, maxlength: 16, minlength: 6, alphanumeric: true, upper: true}
        },
        messages: {
            nome: { required: 'Campo requerido', minlength: 'Mínimo 2 caracteres'},
            email: {required: 'Campo requerido', email: 'Digite um email válido'},
            senha: {required: 'Campo requerido', maxlength: 'Máximo 16 caracteres', minlength: 'Mínimo 6 caracteres', alphanumeric: 'Só são permitidos letras e números', upper: 'É necessário uma letra maiúscula'}
        },

        submitHandler: function(form) {

            var dados = $('.formCadastro');

            $.ajax({
                type: "POST",
                url: '/cadastro',
                data: dados.serialize(),

                beforeSend: function() {

                    $('.formCadastro .cadastroButton').text('cadastrando...');

                    $('button').prop('disabled', true);

                },

                success: function(data) {

                    if( data == 501 ){

                      $('.errorEmail').fadeIn();

                      $('.formCadastro').find("#email").on('click', function() {
                        $('.errorEmail').fadeOut();
                        $(this).val('');
                    });





                  } else if( data == 502 ){

                    $('.errorEmail').fadeIn();

                    $('.formCadastro').find("#email").on('click', function() {
                        $('.errorEmail').fadeOut();
                        $(this).val('');
                    });
                } else if ( data == 200 ) {

                    $('input').val('');

                    $('.emailSucess').fadeIn();

                    $('.formCadastro').find("#email").on('click', function() {
                        $('.emailSucess').fadeOut();
                        $(this).val('');
                    });
                    setTimeout(function(){
                        $(".formRecuperar").removeClass("formRecuperarOn");
                        $(".formCadastro").removeClass("formCadastroOn");
                        $(".formLogin").find("#email").val('');
                        $(".formLogin").find("#senha").val('');
                        $(".formLogin").addClass("formLoginOn");
                    }, 1200);
                    


                } else {

                                // erro generico

                                //$('.response').text('Ocorreu um erro interno, tente novamente... ');

                            }

                        },

                        error: function (xhr, ajaxOptions, thrownError){
                            // erro generico
                        },
                        complete: function (data){

                            $('input').prop('disabled', false);

                            $('.formCadastro .cadastroButton').text('cadastrar-me');

                        }  
                    });

            return false;

        }

    });

}

MANGUE.recuperar = function(){

    $(".formRecuperar").validate({

        errorClass: "error",

        rules: {
            email: {required: true, email: true}
        },
        messages: {
            email: {required: 'Campo requerido', email: 'Digite um email válido'}
        },

        submitHandler: function(form) {

            var dados = $('.formRecuperar');

            $.ajax({

                type: "POST",
                url: '/recuperar_senha',
                data: dados.serialize(),

                beforeSend: function(data){

                    $('.formRecuperar .recuperarButton').text('enviando instruções...');

                    $('.formRecuperar button').prop('disabled', true);

                },

                success: function(data) {

                    if (data == 503) {
                      $(".recuperarP").fadeIn();

                      $(".formRecuperar").find("#email").on('click',function(){
                        $(".recuperarP").fadeOut();
                        $(this).val('');
                    });
                  } else if (data == 502) {

                  } else if (data == 200 ) {
                    $(".recuperarSuccess").fadeIn();

                    $(".formRecuperar").find("#email").on('click',function(){
                        $(".recuperarSuccess").fadeOut();
                        $(this).val('');
                    });
                } else {
                   $(".recuperarError").fadeIn();

                   $(".formRecuperar").find("#email").on('click',function(){
                    $(".recuperarError").fadeOut();
                    $(this).val('');
                });
               }

           },

           error:function (data){

                            // erro generico

                        },

                        complete: function(data){

                            $('.formRecuperar .recuperarButton').html('enviar');
                            
                            $('.formRecuperar button').prop('disabled', false);

                        }
                        

                    });

        }

    });

    $("#recovery input").change( function(){

        $('.response').html("");

        $('#recovery button').prop("disabled", false);

    });

}

MANGUE.reset = function(){

    $(".perfil-dados-reset").validate({

        errorClass: "error",

        rules: {
            senha_nova: {required: true, maxlength: 16, minlength: 6}, 
            confirmar_senha: { required: true, equalTo : "#senha_nova" },
        },
        messages: {
            senha_nova: {required: 'Campo requerido', maxlength: 'Máximo 16 caracteres', minlength: 'Mínimo 6 caracteres'},
            confirmar_senha: { required: 'Campo requerido', equalTo : "As senhas precisam ser iguais" },
        },

        submitHandler: function(form) {

            var dados = $('.perfil-dados-reset');

            $.ajax({
                type: "POST",
                url: '/resetar_senha',
                data: dados.serialize(),

                beforeSend: function(data){
                    $('.perfil-dados-reset button').text('salvando...');
                    $('.perfil-dados-reset button').prop("disabled", false);
                },

                success: function(data) {

                    if (data == 503) {
                                //$('.response').text('Dados de autenticação inválidos.');
                            } else if (data == 502) {
                                //$('.response').text('Preencha os campos obrigatórios.');
                            } else if( data == 504 ){
                                //$('.response').text('A senha precisa ter entre 6 a 16 caracteres.');
                            } else if( data == 505 ){
                                //$('.response').text('Senha e confirmar senha estão diferentes.');
                            } else if (data == 200 ) {

                                $('input').val('');

                                //$('.response').html('<span style="color:#35DE6E;"> Senha atualizada com sucesso! </span>');
                            } else {
                                //$('.response').text('Ocorreu um erro interno, tente novamente...');
                            }


                        },

                        error:function (data) {
                            // erro generico
                        },
                        complete:function (data){
                            $('.perfil-dados-reset button').text('salvar as alterações');
                            $('.perfil-dados-reset button').prop("disabled", false);
                        }
                    });
        }

    });

}

MANGUE.newsletter = function(){

    $(".newsletter-form").validate({

        errorClass: "error",

        rules: {
            email: {required: true, email: true},
        },
        messages: {
            email: {required: 'campo requerido', email: 'Digite um email válido'},
        },

        submitHandler: function(form) {

            var dados = $('.newsletter-form');

            $.ajax({
                type: "POST",
                url: adm_path+'json/newsletter',
                data: dados.serialize(),
                dataType: 'json',

                beforeSend: function(data){
                    $('.newsletter-form button').text('Inscrevendo...');
                },

                success: function(data) {

                    $('.newsletter-form .response').text('');

                    if (data == 501) {
                        $('.newsletter-form .response').text('Preencha todos os campos.');
                    } else if (data == 502) {
                        $('.newsletter-form .response').text('O email já está cadastrado.');
                    } else if (data == 200 ) {

                        $('.newsletter-form input').val('');

                        setTimeout(function (){
                            $(".newsletter-form button").prop("disabled",true);
                            $(".newsletter-form button").text("cadastrado");
                        }, 1000);

                    } else {
                        $('.newsletter-form .response').text('Aconteceu um erro interno, por favor tente novamente.');
                    }

                },
                complete: function(data) {
                    $('.newsletter-form button').text('quero receber');
                }
            });
        },

        error:function (data){

            $('.newsletter-form button').text('quero receber');

        }
    });

    $('.newsletter-form input').click(function(){

        $('.newsletter-form .response').text('');

        $(".newsletter-form button").prop("disabled", false);
        $(".newsletter-form button").text("quero receber");

    });

}

MANGUE.editar_dados = function(){

    jQuery.validator.addMethod("alphanumeric", function(value, element) {
        return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
    }); 

    jQuery.validator.addMethod("upper", function(value, element) {
        return this.optional(element) || /[A-Z]+/.test(value);
    });

    $(".perfil-dados-form").validate({

        errorClass: "error",

        rules: {
            nome: { required: true, minlength: 2 },
            dia: { required: true},
            mes: { required: true},
            ano: { required: true},
            cpf: { required: true},
            telefone: { required: true},
            cep: { required: true},
            endereco: { required: true},
            numero: { required: true},
            bairro: { required: true},
            email: {required: true, email: true},
            senha_antiga: { maxlength: 16, minlength: 6, alphanumeric: true, upper: true},
            senha_nova: { maxlength: 16, minlength: 6, alphanumeric: true, upper: true}
        },
        messages: {
            nome: { required: 'Campo requerido', minlength: 'Mínimo 2 caracteres'},
            dia: { required: 'Campo requerido'},
            mes: { required: 'Campo requerido'},
            ano: { required: 'Campo requerido'},
            cpf: { required: 'Campo requerido'},
            telefone: { required: 'Campo requerido'},
            cep: { required: 'Campo requerido'},
            endereco: { required: 'Campo requerido'},
            numero: { required: 'Campo requerido'},
            bairro: { required: 'Campo requerido'},
            email: {required: 'Campo requerido', email: 'Digite um email válido'},
            senha_antiga: {maxlength: 'Máximo 16 caracteres', minlength: 'Mínimo 6 caracteres', alphanumeric: 'Só são permitidos letras e números', upper: 'É necessário uma letra maiúscula'},
            senha_nova: {maxlength: 'Máximo 16 caracteres', minlength: 'Mínimo 6 caracteres', alphanumeric: 'Só são permitidos letras e números', upper: 'É necessário uma letra maiúscula'},
        },

        submitHandler: function(form) {

            var dados = $('.perfil-dados-form');

            $.ajax({
                type: "POST",
                url: '/perfil/editar',
                data: dados.serialize(),

                beforeSend: function() {

                    $('.perfil-dados-form button').text('salvando...');

                    $('.perfil-dados-form button').prop('disabled', true);

                },


                success: function(data) {

                    if (data == 501) {

                                // algum dado não está valido.. caso alguem tente rackear

                            } else if(data == 502){

                                // o email já está cadastrado

                            } else if(data == 503){

                                // quando a senha nova ou senha antiga for preenchida.. precisa que os dois campos estejem preenchidos.

                            } else if(data == 504){

                                // a senha antiga está errada

                            } else if(data == 200){

                                // tudo ok

                            } else {

                                 // n foi possivel salvar

                             }

                         },

                         error:function (xhr, ajaxOptions, thrownError){

                            // erro generico

                        },

                        complete: function(data){
                            $('.perfil-dados-form button').prop('disabled', false);

                            $('.perfil-dados-form button').text('salvar as alterações');
                        } 

                    });

            return false;

        }

    });

}

MANGUE.carrinho = function(){

    $.ajax({
        type: "GET",
        url: '/carrinho',

        beforeSend: function() {
           $(".btnFrete").text('OK');
       },


       success: function(data) {

        if(data.quantidade > 0){

            $('.produtos-carrinho-container').empty();

            $('.carrinhoHave').fadeIn(1000);

            if(data.frete.check == true){

               $("#cepCarrinho").css('color','#35DE6E');
               $(".btnFrete").text('OK');

               $("#cepCarrinho").on('click',function() {
                $("#cepCarrinho").css('color','#2D2D2D');
                $(this).val('');
            });

           }

       } else {

        $('.produtos-carrinho-container').empty().html('<span>Carrinho vazio</span>');

        $('.carrinhoHave').hide();
    }

},

error:function (xhr, ajaxOptions, thrownError){

},

complete: function(data){
} 

});

}

MANGUE.checkout_dados = function(){

    $(".form-checkout-dados").validate({

        errorClass: "error",

        rules: {
            nome: { required: true, minlength: 2 },
            dia: { required: true},
            mes: { required: true},
            ano: { required: true},
            cpf: { required: true},
            telefone: { required: true},
            email: {required: true, email: true},
            senha: { maxlength: 16, minlength: 6, alphanumeric: true, upper: true},
            confirmar_senha: { maxlength: 16, minlength: 6, alphanumeric: true, upper: true}
        },
        messages: {
            nome: { required: 'Campo requerido', minlength: 'Mínimo 2 caracteres'},
            dia: { required: 'Campo requerido'},
            mes: { required: 'Campo requerido'},
            ano: { required: 'Campo requerido'},
            cpf: { required: 'Campo requerido'},
            telefone: { required: 'Campo requerido'},
            email: {required: 'Campo requerido', email: 'Digite um email válido'},
            senha: {maxlength: 'Máximo 16 caracteres', minlength: 'Mínimo 6 caracteres', alphanumeric: 'Só são permitidos letras e números', upper: 'É necessário uma letra maiúscula'},
            confirmar_senha: {maxlength: 'Máximo 16 caracteres', minlength: 'Mínimo 6 caracteres', alphanumeric: 'Só são permitidos letras e números', upper: 'É necessário uma letra maiúscula'},
        },

        submitHandler: function(form) {

            var dados = $('.form-checkout-dados');

            $.ajax({
                type: "POST",
                url: '/checkout/checar_dados',
                data: dados.serialize(),

                beforeSend: function() {

                    $('.form-checkout-dados button').text('salvando...');

                    $('.form-checkout-dados button').prop('disabled', true);

                },

                success: function(data) {

                    if (data == 501) {

                        // algum dado não está valido.. caso alguem tente rackear

                    } else if(data == 502){

                            // o email já está cadastrado

                        } else if(data == 503){

                            // quando a senha nova ou senha antiga for preenchida.. precisa que os dois campos estejem preenchidos.

                        } else if(data == 504){

                            // a senha antiga está errada

                        } else if(data == 200){

                            // tudo ok

                            $('.minhasEntregas')[0].click()

                        } else {

                            // n foi possivel salvar

                        }
                    },

                    error:function (xhr, ajaxOptions, thrownError){

                        // erro generico

                    },

                    complete: function(data){
                        $('.form-checkout-dados button').prop('disabled', false);

                        $('.form-checkout-dados button').text('Ir para entrega >');
                    } 

                });

            return false;

        }
    });

}

MANGUE.checkout_entrega = function(){


    $(".form-checkout-entrega").validate({

        errorClass: "error",

        rules: {
            cep: { required: true },
            endereco: { required: true},
            numero: { required: true},
            bairro: { required: true},
            lugar: { required: true},
        },
        messages: {
            cep: { required: 'Campo requerido'},
            endereco: { required: 'Campo requerido'},
            numero: { required: 'Campo requerido'},
            bairro: { required: 'Campo requerido'},
            lugar: { required: 'Campo requerido'},
        },

        submitHandler: function(form) {

            var dados = $('.form-checkout-entrega');

            $.ajax({
                type: "POST",
                url: '/checkout/checar_entrega',
                data: dados.serialize(),

                beforeSend: function() {

                    $('.form-checkout-entrega button').text('salvando...');

                    $('.form-checkout-entrega button').prop('disabled', true);

                },

                success: function(data) {

                    if (data.status == 501) {

                            // preencha o campo cep

                        } else if (data.status == 502) {

                            // cep inválido

                        } else if(data.status == 200){

                            // tudo ok

                            $('.meusPagamentos')[0].click()

                        } else {

                            // erro generico

                        }
                    },

                    error:function (xhr, ajaxOptions, thrownError){

                        // erro generico

                    },

                    complete: function(data){
                        $('.form-checkout-entrega button').prop('disabled', false);

                        $('.form-checkout-entrega button').text('Ir para pagamento >');
                    } 

                });

            return false;

        }
    });

}

MANGUE.checkout_pagamento_cartao = function(){

    $(".form-checkout-pagamento").validate({

        errorClass: "error",

        rules: {
            nomeCartao: { required: true },
            cpfTitular: { required: true},
            numeroCartao: { required: true},
            codCartao: { required: true},
            mes: { required: true},
            ano: { required: true},
            parcela: { required: true}
        },
        messages: {
            nomeCartao: { required: 'Campo requerido'},
            cpfTitular: { required: 'Campo requerido'},
            numeroCartao: { required: 'Campo requerido'},
            codCartao: { required: 'Campo requerido'},
            mes: { required: 'Campo requerido'},
            ano: { required: 'Campo requerido'},
            parcela: { required: 'Campo requerido'}
        },

        submitHandler: function(form) {

            var dados = $('.form-checkout-pagamento');

            $.ajax({
                type: "POST",
                url: '/checkout/finalizar_cartao',
                data: dados.serialize(),

                beforeSend: function() {

                    $('.form-checkout-pagamento button').text('salvando...');

                    $('.form-checkout-pagamento button').prop('disabled', true);

                },

                success: function(data) {

                    if (data.status == 501) {

                            // preencha todos os dados

                        } else if (data.status == 502) {

                            // é preciso estar autenticado

                        } else if (data.status == 503) {

                            // n pode estar no checkout..

                        } else if (data.status == 504) {

                            // o carrinho está vazio

                        } else if (data.status == 505) {

                            // calcule o frete

                        } else if(data.status == 200){

                            if(data.gateway == 200){
                                // deve mostrar um modal informando que a compra está sendo processada

                                setTimeout(function(){
                                    window.location.href = "/perfil";
                                }, 3000);

                            } else {
                                // cartão não ta valido
                            }

                        } else {

                            // erro generico

                        }
                    },

                    error:function (xhr, ajaxOptions, thrownError){

                        // erro generico

                    },

                    complete: function(data){
                        $('.form-checkout-pagamento button').prop('disabled', false);

                        $('.form-checkout-pagamento button').text('Efetuar pagamento');
                    } 

                });

            return false;

        }
    });

}

    /* ==================================================
    Init
    ================================================== */

    $(document).ready(function(){

        // quando quiser passar algo onload passa pela main ou outra div

        $('main[onload]').trigger('onload');

        MANGUE.mascaras();
        MANGUE.preloader();
        MANGUE.slick();
        //MANGUE.fancybox();
        MANGUE.contato();
        MANGUE.login();
        MANGUE.cadastro();
        MANGUE.newsletter();
        MANGUE.editar_dados();
        MANGUE.recuperar();
        MANGUE.reset();
        MANGUE.carrinho();
        MANGUE.checkout_dados();
        MANGUE.checkout_entrega();
        MANGUE.checkout_pagamento_cartao();

    });    

});

/* externas aqui embaixo */

$(function() {

    var FiltroBox = $("#filtroBox");
    var FormularioFiltro = $(".filtro-formulario");
    var Seta = $(".seta-filtro");
    var AreaClick = $(".iconsMeio");
    var AreaClick2 = $ (".seta-box");
    AreaClick.on("click", function(){
        FiltroBox.toggleClass("Box-ativo");
        setTimeout(function(){
            FormularioFiltro.toggle();
            Seta.toggleClass("seta-inversa");
        },100);
    });
    AreaClick2.on("click", function(){
        FiltroBox.toggleClass("Box-ativo");
        setTimeout(function(){
            FormularioFiltro.toggle();
            Seta.toggleClass("seta-inversa");
        },100);
    });

    $(".place-card-large").css("display", "none !important");


    $(".content").on("click", function(){
        $(".content").removeClass("blur");
        $(".carrinho-box").show();
        $(".formLogin").removeClass("formLoginOn");
        $(".formLogin").removeClass("formLoginSmall");
        $(".formRecuperar").removeClass("formRecuperarOn");
        $(".formCadastro").removeClass("formCadastroOn");
        $(".formLogin").find("#email").val('');
        $(".formLogin").find("#senha").val('');
        $('.formCarrinho').removeClass("formCarrinhoOn");
        $(".formLogado").removeClass("formLogadoOn");
        $('.minhaContaLinkDisable').show();
        $('.minhaContaLink').show();

    });

    $(".minhaContaLink").on("click", function(){
        $(".content").toggleClass("blur");
        $(".carrinho-box").toggle(100);
        $(".formLogin").toggleClass("formLoginOn");
        $(".formLogin").removeClass("formLoginSmall");
        $(".formRecuperar").removeClass("formRecuperarOn");
        $(".formCadastro").removeClass("formCadastroOn");
        $(".formLogin").find("#email").val('');
        $(".formLogin").find("#senha").val('');

    });
    $(".recuperarSenha").on("click", function() {
        $(".formRecuperar").toggleClass("formRecuperarOn");
        $(".formLogin").addClass("formLoginSmall");
        $(".formLogin").find("#email").val('');
        $(".formLogin").find("#senha").val('');

    });
    $(".recuperarVoltar").on("click",function(){
        $(".formRecuperar").removeClass("formRecuperarOn");
        $(".formLogin").removeClass("formLoginSmall");
        $(".formRecuperar").find("#email").val('');
    });
    $(".cadastrarMe").on("click", function(){
        $(".formCadastro").addClass("formCadastroOn");
        $(".formRecuperar").removeClass("formRecuperarOn");
        $(".formLogin").find("#email").val('');
        $(".formLogin").find("#senha").val('');
    });
    $(".cadastrarVoltar").on("click", function(){
        $(".formCadastro").removeClass("formCadastroOn");
        $(".formCadastro").find("#email").val('');
        $(".formCadastro").find("#nome").val('');
        $(".formCadastro").find("#senha").val('');
    });

    /*Carrinho */

    $(".carrinho-box").on("click", function(){
        $(".content").toggleClass("blur");
        $(".minhaContaLink").toggle(10);
        // $(".formLogin").toggleClass("formLoginOn");
        // $(".formLogin").removeClass("formLoginSmall");
        $(".formCarrinho").toggleClass("formCarrinhoOn");
        $('.minhaContaLinkDisable').hide();
        $('.minhaContaLink').hide();
        $("#cepCarrinho").val('');

        // $(".formCadastro").removeClass("formCadastroOn");

    });
    /* Meus pedidos e dados */
    var email = $(".perfil-dados-form").find("#email");
    var nome = $(".perfil-dados-form").find("#nome");
    var cpf = $(".perfil-dados-form").find("#cpf");
    var telefone = $(".perfil-dados-form").find("#telefone");
    var cep = $(".perfil-dados-form").find("#cep");
    var endereco = $(".perfil-dados-form").find("#endereco");
    var numero = $(".perfil-dados-form").find("#numero");
    var complemento = $(".perfil-dados-form").find("#complemento");
    var bairro = $(".perfil-dados-form").find("#bairro");
    var lugar = $(".perfil-dados-form").find("#lugar");
    var senhaAntiga = $(".perfil-dados-form").find("#senhaAntiga");
    var senhaNova = $(".perfil-dados-form").find("#senhaNova");
    var dia = $(".perfil-dados-form").find("#dia");
    var mes = $(".perfil-dados-form").find("#mes");
    var ano = $(".perfil-dados-form").find("#ano");

    $(".meusPedidosLink").on("click", function() {
        $(".perfil-dados").fadeOut(500);
        $(".perfil-pedidos").fadeIn(1000);
        $(".perfil-pedidos").addClass("perfilOn");
        $(this).addClass("activeLink");
        $(".meusDadosLink").removeClass("activeLink");
        email.val('');
        nome.val('');
        cpf.val('');
        telefone.val('');
        cep.val('');
        endereco.val('');
        numero.val('');
        complemento.val('');
        bairro.val('');
        lugar.val('');
        senhaAntiga.val('');
        senhaNova.val('');
        dia.val('Dia');
        mes.val('Mês');
        ano.val('Ano');
    });
    $(".meusDadosLink").on("click", function() {
        $(".perfil-pedidos").fadeOut(500);
        $(".perfil-dados").fadeIn(1000);
        $(".perfil-pedidos").removeClass("perfilOn");
        $(this).addClass("activeLink");
        $(".meusPedidosLink").removeClass("activeLink");
    });

    /* ----------------------------------------------------- */

    /* Single */
    
    $("#photos-1").on("click", function(){
        $("#photos-view2").fadeOut(); 
        $("#photos-view3").fadeOut();
        $("#photos-view1").fadeIn();

        $("#photos-1").addClass('produtos-photos-active');
        $("#photos-2").removeClass('produtos-photos-active');
        $("#photos-3").removeClass('produtos-photos-active');
    });

    $("#photos-2").on("click", function(){
        $("#photos-view1").fadeOut();
        $("#photos-view3").fadeOut();
        $("#photos-view2").fadeIn();

        $("#photos-2").addClass('produtos-photos-active');
        $("#photos-1").removeClass('produtos-photos-active');
        $("#photos-3").removeClass('produtos-photos-active');
    });

    $("#photos-3").on("click", function(){
        $("#photos-view2").fadeOut();
        $("#photos-view1").fadeOut();
        $("#photos-view3").fadeIn();

        $("#photos-3").addClass('produtos-photos-active');
        $("#photos-1").removeClass('produtos-photos-active');
        $("#photos-2").removeClass('produtos-photos-active');
    });

    $(".corSelect").on("click", function(){
        $(".corOptions").toggle();
    });
    document.documentElement.onclick = function(event) {
        if (event.target === document.documentElement) {
            document.documentElement.classList.remove('corOptions');
        }
    };


    /* Tons de Cinza */
    var i000000 = $(".corOptions").find('.000000');
    var i696969 = $(".corOptions").find('.696969');
    var iC0C0C0 = $(".corOptions").find('.C0C0C0');
    var iDCDCDC = $(".corOptions").find('.DCDCDC');

    i000000.on('click', function(){$(".corSelecionada").css("background-color",'#000000');});
    i696969.on('click', function(){$(".corSelecionada").css("background-color",'#696969');});
    iC0C0C0.on('click', function(){$(".corSelecionada").css("background-color",'#C0C0C0');});
    iDCDCDC.on('click', function(){$(".corSelecionada").css("background-color",'#DCDCDC');});

    /* Tons de azul */
    var i0000FF = $(".corOptions").find('.0000FF');
    var i6A5ACD = $(".corOptions").find('.6A5ACD');
    var i6495ED = $(".corOptions").find('.6495ED');
    var i00BFFF = $(".corOptions").find('.00BFFF');

    i0000FF.on('click', function(){$(".corSelecionada").css("background-color",'#0000FF');});
    i6A5ACD.on('click', function(){$(".corSelecionada").css("background-color",'#6A5ACD');});
    i6495ED.on('click', function(){$(".corSelecionada").css("background-color",'#6495ED');});
    i00BFFF.on('click', function(){$(".corSelecionada").css("background-color",'#00BFFF');});

    /* Tons de cyano */

    var i00CED1 = $(".corOptions").find('.00CED1');
    var i008B8B = $(".corOptions").find('.008B8B');
    var i7FFFD4 = $(".corOptions").find('.7FFFD4');
    var i48D1CC = $(".corOptions").find('.48D1CC');

    i00CED1.on('click', function(){$(".corSelecionada").css("background-color",'#00CED1');});
    i008B8B.on('click', function(){$(".corSelecionada").css("background-color",'#008B8B');});
    i7FFFD4.on('click', function(){$(".corSelecionada").css("background-color",'#7FFFD4');});
    i48D1CC.on('click', function(){$(".corSelecionada").css("background-color",'#48D1CC');});

    /* Tons de verde */

    var i00FF7F = $(".corOptions").find('.00FF7F');
    var i90EE90 = $(".corOptions").find('.90EE90');
    var i006400 = $(".corOptions").find('.006400');
    var i00FF00 = $(".corOptions").find('.00FF00');

    i00FF7F.on('click', function(){$(".corSelecionada").css("background-color",'#00FF7F');});
    i90EE90.on('click', function(){$(".corSelecionada").css("background-color",'#90EE90');});
    i006400.on('click', function(){$(".corSelecionada").css("background-color",'#006400');});
    i00FF00.on('click', function(){$(".corSelecionada").css("background-color",'#00FF00');});

    /* Tons de marrom */

    var i8B4513 = $(".corOptions").find('.8B4513');
    var iDAA520 = $(".corOptions").find('.DAA520');
    var iBDB76B = $(".corOptions").find('.BDB76B');
    var iD2691E = $(".corOptions").find('.D2691E');
    var iFFDEAD = $(".corOptions").find('.FFDEAD');
    var iF4A460 = $(".corOptions").find('.F4A460');
    var iD2B48C = $(".corOptions").find('.D2B48C');

    i8B4513.on('click', function(){$(".corSelecionada").css("background-color",'#8B4513');});
    iDAA520.on('click', function(){$(".corSelecionada").css("background-color",'#DAA520');});
    iBDB76B.on('click', function(){$(".corSelecionada").css("background-color",'#BDB76B');});
    iD2691E.on('click', function(){$(".corSelecionada").css("background-color",'#D2691E');});
    iFFDEAD.on('click', function(){$(".corSelecionada").css("background-color",'#FFDEAD');});
    iF4A460.on('click', function(){$(".corSelecionada").css("background-color",'#F4A460');});
    iD2B48C.on('click', function(){$(".corSelecionada").css("background-color",'#D2B48C');});

    /* Tons de roxo */

    var i8A2BE2 = $(".corOptions").find('.8A2BE2');
    var i4B0082 = $(".corOptions").find('.4B0082');
    var i9400D3 = $(".corOptions").find('.9400D3');
    var i800080 = $(".corOptions").find('.800080');

    i8A2BE2.on('click', function(){$(".corSelecionada").css("background-color",'#8A2BE2');});
    i4B0082.on('click', function(){$(".corSelecionada").css("background-color",'#4B0082');});
    i9400D3.on('click', function(){$(".corSelecionada").css("background-color",'#9400D3');});
    i800080.on('click', function(){$(".corSelecionada").css("background-color",'#800080');});

    /* Tons de rosa */

    var iFF1493 = $(".corOptions").find('.FF1493');
    var iFF69B4 = $(".corOptions").find('.FF69B4');
    var iDC143C = $(".corOptions").find('.DC143C');
    var iF08080 = $(".corOptions").find('.F08080');

    iFF1493.on('click', function(){$(".corSelecionada").css("background-color",'#FF1493');});
    iFF69B4.on('click', function(){$(".corSelecionada").css("background-color",'#FF69B4');});
    iDC143C.on('click', function(){$(".corSelecionada").css("background-color",'#DC143C');});
    iF08080.on('click', function(){$(".corSelecionada").css("background-color",'#F08080');});

    /* Tons de vermelho */

    var iFF0000 = $(".corOptions").find('.FF0000');
    var iFF7F50 = $(".corOptions").find('.FF7F50');
    var iFF6347 = $(".corOptions").find('.FF6347');
    var i800000 = $(".corOptions").find('.800000');

    iFF0000.on('click', function(){$(".corSelecionada").css("background-color",'#FF0000');});
    iFF7F50.on('click', function(){$(".corSelecionada").css("background-color",'#FF7F50');});
    iFF6347.on('click', function(){$(".corSelecionada").css("background-color",'#FF6347');});
    i800000.on('click', function(){$(".corSelecionada").css("background-color",'#800000');});

    /* Tons de laranja */

    var iFF4500 = $(".corOptions").find('.FF4500');
    var iFF8C00 = $(".corOptions").find('.FF8C00');
    var iFFA500 = $(".corOptions").find('.FFA500');

    iFF4500.on('click', function(){$(".corSelecionada").css("background-color",'#FF4500');});
    iFF8C00.on('click', function(){$(".corSelecionada").css("background-color",'#FF8C00');});
    iFFA500.on('click', function(){$(".corSelecionada").css("background-color",'#FFA500');});

    /* Tons de amarelo */

    var iFFD700 = $(".corOptions").find('.FFD700');
    var iFFFF00 = $(".corOptions").find('.FFFF00');
    var iF0E68C = $(".corOptions").find('.F0E68C');

    iFFD700.on('click', function(){$(".corSelecionada").css("background-color",'#FFD700');});
    iFFFF00.on('click', function(){$(".corSelecionada").css("background-color",'#FFFF00');});
    iF0E68C.on('click', function(){$(".corSelecionada").css("background-color",'#F0E68C');});

    /* Tons de branco */

    var iFFFFFF = $(".corOptions").find('.FFFFFF');


    iFFFFFF.on('click', function(){$(".corSelecionada").css("background-color",'#FFFFFF');});


    /* Checkout */ 


    $(".meusDadosPessoais").on("click", function() {
        $(".box-esquerdaE").fadeOut(10);
        $(".box-esquerdaP").fadeOut(10);
        $(".box-esquerdaC").fadeIn(1500);
        // $(".perfil-pedidos").addClass("perfilOn");
        $(this).addClass("activeLink");
        $(".minhasEntregas").removeClass("activeLink");
        $(".meusPagamentos").removeClass("activeLink");
    });
    $(".minhasEntregas").on("click", function() {
        $(".box-esquerdaP").fadeOut(10);
        $(".box-esquerdaC").fadeOut(10);
        $(".box-esquerdaE").fadeIn(1500);
        // $(".perfil-pedidos").removeClass("perfilOn");
        $(this).addClass("activeLink");
        $(".meusDadosPessoais").removeClass("activeLink");
        $(".meusPagamentos").removeClass("activeLink");
    });
    $(".meusPagamentos").on("click", function() {
        $(".box-esquerdaC").fadeOut(10);
        $(".box-esquerdaE").fadeOut(10);
        $(".box-esquerdaP").fadeIn(1500);
        // $(".perfil-pedidos").removeClass("perfilOn");
        $(this).addClass("activeLink");
        $(".meusDadosPessoais").removeClass("activeLink");
        $(".minhasEntregas").removeClass("activeLink");
    });
    
    $(".inputBoleto").on('click', function(){
        $(".cartaoForm").fadeOut(200);
        $(".cartaoForm").removeClass('flexbox');
        $(".btnConfirmar").addClass('flexbox');
        $(".desc-boleto").fadeIn(200);
        $(".btnConfirmar").fadeIn(200);

        $(".#nomeCartao").val('');
        $(".#cpfTitular").val('');
        $(".#numeroCartao").val('');
        $(".#codCarta").val('');

    });
    $(".inputCartao").on('click', function(){
        $(".btnConfirmar").removeClass('flexbox');
        $(".cartaoForm").addClass('flexbox');
        $(".desc-boleto").fadeOut(200);
        $(".btnConfirmar").fadeOut(200);
        $(".cartaoForm").fadeIn(200);

    });

    $(".frete-checkout").on("click", ".calculaCEP", function(){

        $('.cupomForm').hide();
        $('.cupomForm').removeClass('cepFormOn');
        $('.cupomResumo').hide();
        $('.btnCupom').hide();
        $('.box-direitaC').removeClass('cupomOn');

        $('.calculaCEPForm').toggle();
        $('.calculaCEPForm').toggleClass('cepFormOn');
        $('.box-direitaC').toggleClass('cepOn');
        $('.cepResumo').toggle();
        $('.btnCep').toggle();


    });


    $(".adicionaCupom").on('click', function(){
        $('.calculaCEPForm').hide();
        $('.calculaCEPForm').removeClass('cepFormOn');
        $('.cepResumo').hide();
        $('.btnCep').hide();
        $('.box-direitaC').removeClass('cepOn');


        $('.cupomForm').toggle();
        $('.cupomForm').toggleClass('cepFormOn');
        $('.box-direitaC').toggleClass('cupomOn');
        $('.cupomResumo').toggle();
        $('.btnCupom').toggle();
    });



    /*------------------------------------------------------ */

    /* Lookbook */

    $(".lookbook-box").hover(function(){
        $(this).find('.blend-lookbook').fadeIn(200);
        $(this).find('.fullscreen').fadeIn(200);
        $(this).find('.lookbook-img').addClass('lookbookOn');
    }, function(){
        $(this).find('.blend-lookbook').fadeOut(200);
        $(this).find('.fullscreen').fadeOut(200);
        $(this).find('.lookbook-img').removeClass('lookbookOn');
    });

    /*------------------------------------------------------ */

});

function scroll_produtos(){

    $(window).on("scroll", function() {

        scroll = (window.innerHeight + window.scrollY) - 85;

        //console.log(scroll);

        //console.log(document.body.offsetHeight);

        if ((scroll) >= document.body.offsetHeight) {

            if ( window.quantidade == undefined) {
                quantidade = 12;
            } else {
                quantidade = quantidade + 4;
            }

            $('.btn-load').show();

            $('body').addClass('overflow');

            setTimeout(function(){

                var tamanho = $('#Tamanho').val();

                var preco = $('#Preco').val();

                $.ajax({
                    type: "GET",
                    url: 'produtos_json?quantidade='+quantidade+'&tamanho='+tamanho+'&faixa_preco='+preco,
                    success: function(data) {

                        $('#Produtos-catalogo main').empty();
                        
                        for (var i = 0; i < data.length; i++) {

                            $('#Produtos-catalogo main').append(
                                '<div class="produtos-box">'+
                                '<span class="produtos-img">'+
                                '<img src="'+data[i].imagem+'">'+
                                '<span class="produto-blend"></span>'+
                                '<i class="icon-eye"></i>'+
                                '</span>'+
                                '<span class="produtos-descricao">'+
                                '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Heading-4">'+data[i].nome+'</a>'+ 
                                '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Display-3 parcelas">6x de '+data[i].preco_dividido+'</a>'+ 
                                '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Display-3 preco">'+data[i].preco+'</a>'+ 
                                '</span>'+
                                '</div>'
                                );

                        }
                        
                    }
                });

            }, 1000);

            //$(document).unbind('scroll');

            $('body').removeClass('overflow');

            $('.btn-load').hide();
            
        }

    });

}

function filtros_produtos(){

    var tamanho = $('#Tamanho').val();

    var preco = $('#Preco').val();

    $.ajax({
        type: "GET",
        url: 'produtos_json?quantidade=8&tamanho='+tamanho+'&faixa_preco='+preco,
        success: function(data) {

            $('#Produtos-catalogo main').empty();
            
            for (var i = 0; i < data.length; i++) {

                $('#Produtos-catalogo main').append(
                    '<div class="produtos-box">'+
                    '<span class="produtos-img">'+
                    '<img src="'+data[i].imagem+'">'+
                    '<span class="produto-blend"></span>'+
                    '<i class="icon-eye"></i>'+
                    '</span>'+
                    '<span class="produtos-descricao">'+
                    '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Heading-4">'+data[i].nome+'</a>'+ 
                    '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Display-3 parcelas">6x de '+data[i].preco_dividido+'</a>'+ 
                    '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Display-3 preco">'+data[i].preco+'</a>'+ 
                    '</span>'+
                    '</div>'
                    );

            }
            
        }
    });

}

function scroll_outlet(){

    $(window).on("scroll", function() {

        scroll = (window.innerHeight + window.scrollY) - 85;

        //console.log(scroll);

        //console.log(document.body.offsetHeight);

        if ((scroll) >= document.body.offsetHeight) {

        	if ( window.quantidade == undefined) {
        		quantidade = 12;
        	} else {
        		quantidade = quantidade + 4;
        	}

            $('.btn-load').show();

            $('body').addClass('overflow');

            setTimeout(function(){

            	var tamanho = $('#Tamanho').val();

                var preco = $('#Preco').val();

                $.ajax({
                    type: "GET",
                    url: 'outlet_json?quantidade='+quantidade+'&tamanho='+tamanho+'&faixa_preco='+preco,
                    success: function(data) {

                      $('#Produtos-catalogo main').empty();

                      for (var i = 0; i < data.length; i++) {

                         $('#Produtos-catalogo main').append(
                            '<div class="produtos-box">'+
                            '<span class="produtos-img">'+
                            '<img src="'+data[i].imagem+'">'+
                            '<span class="produto-blend"></span>'+
                            '<i class="icon-eye"></i>'+
                            '</span>'+
                            '<span class="produtos-descricao">'+
                            '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Heading-4">'+data[i].nome+'</a>'+ 
                            '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Display-3 parcelas">6x de '+data[i].preco_dividido+'</a>'+ 
                            '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Display-3 preco">'+data[i].preco+'</a>'+ 
                            '</span>'+
                            '</div>'
                            );

                     }

                 }
             });

            }, 1000);

            //$(document).unbind('scroll');

            $('body').removeClass('overflow');

            $('.btn-load').hide();
            
        }

    });

}

function filtros_outlet(){

	var tamanho = $('#Tamanho').val();

    var preco = $('#Preco').val();

    $.ajax({
        type: "GET",
        url: 'outlet_json?quantidade=8&tamanho='+tamanho+'&faixa_preco='+preco,
        success: function(data) {
        	
        	$('#Produtos-catalogo main').empty();
        	
        	for (var i = 0; i < data.length; i++) {

        		$('#Produtos-catalogo main').append(
        			'<div class="produtos-box">'+
                 '<span class="produtos-img">'+
                 '<img src="'+data[i].imagem+'">'+
                 '<span class="produto-blend"></span>'+
                 '<i class="icon-eye"></i>'+
                 '</span>'+
                 '<span class="produtos-descricao">'+
                 '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Heading-4">'+data[i].nome+'</a>'+ 
                 '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Display-3 parcelas">6x de '+data[i].preco_dividido+'</a>'+ 
                 '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Display-3 preco">'+data[i].preco+'</a>'+ 
                 '</span>'+
                 '</div>'
                 );

        	}
        	
        }
    });

}

function scroll_tshirts(){

    $(window).on("scroll", function() {

        scroll = (window.innerHeight + window.scrollY) - 85;

        //console.log(scroll);

        //console.log(document.body.offsetHeight);

        if ((scroll) >= document.body.offsetHeight) {

            if ( window.quantidade == undefined) {
                quantidade = 12;
            } else {
                quantidade = quantidade + 4;
            }

            $('.btn-load').show();

            $('body').addClass('overflow');

            setTimeout(function(){

                var tamanho = $('#Tamanho').val();

                var preco = $('#Preco').val();

                $.ajax({
                    type: "GET",
                    url: 'tshirts_json?quantidade='+quantidade+'&tamanho='+tamanho+'&faixa_preco='+preco,
                    success: function(data) {

                        $('#Produtos-catalogo main').empty();
                        
                        for (var i = 0; i < data.length; i++) {

                            $('#Produtos-catalogo main').append(
                                '<div class="produtos-box">'+
                                '<span class="produtos-img">'+
                                '<img src="'+data[i].imagem+'">'+
                                '<span class="produto-blend"></span>'+
                                '<i class="icon-eye"></i>'+
                                '</span>'+
                                '<span class="produtos-descricao">'+
                                '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Heading-4">'+data[i].nome+'</a>'+ 
                                '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Display-3 parcelas">6x de '+data[i].preco_dividido+'</a>'+ 
                                '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Display-3 preco">'+data[i].preco+'</a>'+ 
                                '</span>'+
                                '</div>'
                                );

                        }
                        
                    }
                });

            }, 1000);

            //$(document).unbind('scroll');

            $('body').removeClass('overflow');

            $('.btn-load').hide();
            
        }

    });

}

function filtros_tshirts(){

    var tamanho = $('#Tamanho').val();

    var preco = $('#Preco').val();

    $.ajax({
        type: "GET",
        url: 'tshirts_json?quantidade=8&tamanho='+tamanho+'&faixa_preco='+preco,
        success: function(data) {

            $('#Produtos-catalogo main').empty();
            
            for (var i = 0; i < data.length; i++) {

                $('#Produtos-catalogo main').append(
                    '<div class="produtos-box">'+
                    '<span class="produtos-img">'+
                    '<img src="'+data[i].imagem+'">'+
                    '<span class="produto-blend"></span>'+
                    '<i class="icon-eye"></i>'+
                    '</span>'+
                    '<span class="produtos-descricao">'+
                    '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Heading-4">'+data[i].nome+'</a>'+ 
                    '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Display-3 parcelas">6x de '+data[i].preco_dividido+'</a>'+ 
                    '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Display-3 preco">'+data[i].preco+'</a>'+ 
                    '</span>'+
                    '</div>'
                    );

            }
            
        }
    });

}

function scroll_vestidos(){

    $(window).on("scroll", function() {

        scroll = (window.innerHeight + window.scrollY) - 85;

        //console.log(scroll);

        //console.log(document.body.offsetHeight);

        if ((scroll) >= document.body.offsetHeight) {

            if ( window.quantidade == undefined) {
                quantidade = 12;
            } else {
                quantidade = quantidade + 4;
            }

            $('.btn-load').show();

            $('body').addClass('overflow');

            setTimeout(function(){

                var tamanho = $('#Tamanho').val();

                var preco = $('#Preco').val();

                $.ajax({
                    type: "GET",
                    url: 'vestidos_json?quantidade='+quantidade+'&tamanho='+tamanho+'&faixa_preco='+preco,
                    success: function(data) {

                        $('#Produtos-catalogo main').empty();
                        
                        for (var i = 0; i < data.length; i++) {

                            $('#Produtos-catalogo main').append(
                                '<div class="produtos-box">'+
                                '<span class="produtos-img">'+
                                '<img src="'+data[i].imagem+'">'+
                                '<span class="produto-blend"></span>'+
                                '<i class="icon-eye"></i>'+
                                '</span>'+
                                '<span class="produtos-descricao">'+
                                '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Heading-4">'+data[i].nome+'</a>'+ 
                                '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Display-3 parcelas">6x de '+data[i].preco_dividido+'</a>'+ 
                                '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Display-3 preco">'+data[i].preco+'</a>'+ 
                                '</span>'+
                                '</div>'
                                );

                        }
                        
                    }
                });

            }, 1000);

            //$(document).unbind('scroll');

            $('body').removeClass('overflow');

            $('.btn-load').hide();
            
        }

    });

}

function filtros_vestidos(){

    var tamanho = $('#Tamanho').val();

    var preco = $('#Preco').val();

    $.ajax({
        type: "GET",
        url: 'vestidos_json?quantidade=8&tamanho='+tamanho+'&faixa_preco='+preco,
        success: function(data) {

            $('#Produtos-catalogo main').empty();
            
            for (var i = 0; i < data.length; i++) {

                $('#Produtos-catalogo main').append(
                    '<div class="produtos-box">'+
                    '<span class="produtos-img">'+
                    '<img src="'+data[i].imagem+'">'+
                    '<span class="produto-blend"></span>'+
                    '<i class="icon-eye"></i>'+
                    '</span>'+
                    '<span class="produtos-descricao">'+
                    '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Heading-4">'+data[i].nome+'</a>'+ 
                    '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Display-3 parcelas">6x de '+data[i].preco_dividido+'</a>'+ 
                    '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Display-3 preco">'+data[i].preco+'</a>'+ 
                    '</span>'+
                    '</div>'
                    );

            }
            
        }
    });

}

function scroll_macacoes(){

    $(window).on("scroll", function() {

        scroll = (window.innerHeight + window.scrollY) - 85;

        //console.log(scroll);

        //console.log(document.body.offsetHeight);

        if ((scroll) >= document.body.offsetHeight) {

            if ( window.quantidade == undefined) {
                quantidade = 12;
            } else {
                quantidade = quantidade + 4;
            }

            $('.btn-load').show();

            $('body').addClass('overflow');

            setTimeout(function(){

                var tamanho = $('#Tamanho').val();

                var preco = $('#Preco').val();

                $.ajax({
                    type: "GET",
                    url: 'macacoes_json?quantidade='+quantidade+'&tamanho='+tamanho+'&faixa_preco='+preco,
                    success: function(data) {

                        $('#Produtos-catalogo main').empty();
                        
                        for (var i = 0; i < data.length; i++) {

                            $('#Produtos-catalogo main').append(
                                '<div class="produtos-box">'+
                                '<span class="produtos-img">'+
                                '<img src="'+data[i].imagem+'">'+
                                '<span class="produto-blend"></span>'+
                                '<i class="icon-eye"></i>'+
                                '</span>'+
                                '<span class="produtos-descricao">'+
                                '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Heading-4">'+data[i].nome+'</a>'+ 
                                '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Display-3 parcelas">6x de '+data[i].preco_dividido+'</a>'+ 
                                '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Display-3 preco">'+data[i].preco+'</a>'+ 
                                '</span>'+
                                '</div>'
                                );

                        }
                        
                    }
                });

            }, 1000);

            //$(document).unbind('scroll');

            $('body').removeClass('overflow');

            $('.btn-load').hide();
            
        }

    });

}

function filtros_macacoes(){

    var tamanho = $('#Tamanho').val();

    var preco = $('#Preco').val();

    $.ajax({
        type: "GET",
        url: 'macacoes_json?quantidade=8&tamanho='+tamanho+'&faixa_preco='+preco,
        success: function(data) {

            $('#Produtos-catalogo main').empty();
            
            for (var i = 0; i < data.length; i++) {

                $('#Produtos-catalogo main').append(
                    '<div class="produtos-box">'+
                    '<span class="produtos-img">'+
                    '<img src="'+data[i].imagem+'">'+
                    '<span class="produto-blend"></span>'+
                    '<i class="icon-eye"></i>'+
                    '</span>'+
                    '<span class="produtos-descricao">'+
                    '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Heading-4">'+data[i].nome+'</a>'+ 
                    '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Display-3 parcelas">6x de '+data[i].preco_dividido+'</a>'+ 
                    '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Display-3 preco">'+data[i].preco+'</a>'+ 
                    '</span>'+
                    '</div>'
                    );

            }
            
        }
    });

}

function scroll_calcas(){

    $(window).on("scroll", function() {

        scroll = (window.innerHeight + window.scrollY) - 85;

        //console.log(scroll);

        //console.log(document.body.offsetHeight);

        if ((scroll) >= document.body.offsetHeight) {

            if ( window.quantidade == undefined) {
                quantidade = 12;
            } else {
                quantidade = quantidade + 4;
            }

            $('.btn-load').show();

            $('body').addClass('overflow');

            setTimeout(function(){

                var tamanho = $('#Tamanho').val();

                var preco = $('#Preco').val();

                $.ajax({
                    type: "GET",
                    url: 'calcas_json?quantidade='+quantidade+'&tamanho='+tamanho+'&faixa_preco='+preco,
                    success: function(data) {

                        $('#Produtos-catalogo main').empty();
                        
                        for (var i = 0; i < data.length; i++) {

                            $('#Produtos-catalogo main').append(
                                '<div class="produtos-box">'+
                                '<span class="produtos-img">'+
                                '<img src="'+data[i].imagem+'">'+
                                '<span class="produto-blend"></span>'+
                                '<i class="icon-eye"></i>'+
                                '</span>'+
                                '<span class="produtos-descricao">'+
                                '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Heading-4">'+data[i].nome+'</a>'+ 
                                '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Display-3 parcelas">6x de '+data[i].preco_dividido+'</a>'+ 
                                '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Display-3 preco">'+data[i].preco+'</a>'+ 
                                '</span>'+
                                '</div>'
                                );

                        }
                        
                    }
                });

            }, 1000);

            //$(document).unbind('scroll');

            $('body').removeClass('overflow');

            $('.btn-load').hide();
            
        }

    });

}

function filtros_calcas(){

    var tamanho = $('#Tamanho').val();

    var preco = $('#Preco').val();

    $.ajax({
        type: "GET",
        url: 'calcas_json?quantidade=8&tamanho='+tamanho+'&faixa_preco='+preco,
        success: function(data) {

            $('#Produtos-catalogo main').empty();
            
            for (var i = 0; i < data.length; i++) {

                $('#Produtos-catalogo main').append(
                    '<div class="produtos-box">'+
                    '<span class="produtos-img">'+
                    '<img src="'+data[i].imagem+'">'+
                    '<span class="produto-blend"></span>'+
                    '<i class="icon-eye"></i>'+
                    '</span>'+
                    '<span class="produtos-descricao">'+
                    '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Heading-4">'+data[i].nome+'</a>'+ 
                    '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Display-3 parcelas">6x de '+data[i].preco_dividido+'</a>'+ 
                    '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Display-3 preco">'+data[i].preco+'</a>'+ 
                    '</span>'+
                    '</div>'
                    );

            }
            
        }
    });

}

function scroll_saias(){

    $(window).on("scroll", function() {

        scroll = (window.innerHeight + window.scrollY) - 85;

        //console.log(scroll);

        //console.log(document.body.offsetHeight);

        if ((scroll) >= document.body.offsetHeight) {

            if ( window.quantidade == undefined) {
                quantidade = 12;
            } else {
                quantidade = quantidade + 4;
            }

            $('.btn-load').show();

            $('body').addClass('overflow');

            setTimeout(function(){

                var tamanho = $('#Tamanho').val();

                var preco = $('#Preco').val();

                $.ajax({
                    type: "GET",
                    url: 'saias_json?quantidade='+quantidade+'&tamanho='+tamanho+'&faixa_preco='+preco,
                    success: function(data) {

                        $('#Produtos-catalogo main').empty();
                        
                        for (var i = 0; i < data.length; i++) {

                            $('#Produtos-catalogo main').append(
                                '<div class="produtos-box">'+
                                '<span class="produtos-img">'+
                                '<img src="'+data[i].imagem+'">'+
                                '<span class="produto-blend"></span>'+
                                '<i class="icon-eye"></i>'+
                                '</span>'+
                                '<span class="produtos-descricao">'+
                                '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Heading-4">'+data[i].nome+'</a>'+ 
                                '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Display-3 parcelas">6x de '+data[i].preco_dividido+'</a>'+ 
                                '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Display-3 preco">'+data[i].preco+'</a>'+ 
                                '</span>'+
                                '</div>'
                                );

                        }
                        
                    }
                });

            }, 1000);

            //$(document).unbind('scroll');

            $('body').removeClass('overflow');

            $('.btn-load').hide();
            
        }

    });

}

function filtros_saias(){

    var tamanho = $('#Tamanho').val();

    var preco = $('#Preco').val();

    $.ajax({
        type: "GET",
        url: 'saias_json?quantidade=8&tamanho='+tamanho+'&faixa_preco='+preco,
        success: function(data) {

            $('#Produtos-catalogo main').empty();
            
            for (var i = 0; i < data.length; i++) {

                $('#Produtos-catalogo main').append(
                    '<div class="produtos-box">'+
                    '<span class="produtos-img">'+
                    '<img src="'+data[i].imagem+'">'+
                    '<span class="produto-blend"></span>'+
                    '<i class="icon-eye"></i>'+
                    '</span>'+
                    '<span class="produtos-descricao">'+
                    '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Heading-4">'+data[i].nome+'</a>'+ 
                    '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Display-3 parcelas">6x de '+data[i].preco_dividido+'</a>'+ 
                    '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Display-3 preco">'+data[i].preco+'</a>'+ 
                    '</span>'+
                    '</div>'
                    );

            }
            
        }
    });

}

function scroll_blusas(){

    $(window).on("scroll", function() {

        scroll = (window.innerHeight + window.scrollY) - 85;

        //console.log(scroll);

        //console.log(document.body.offsetHeight);

        if ((scroll) >= document.body.offsetHeight) {

            if ( window.quantidade == undefined) {
                quantidade = 12;
            } else {
                quantidade = quantidade + 4;
            }

            $('.btn-load').show();

            $('body').addClass('overflow');

            setTimeout(function(){

                var tamanho = $('#Tamanho').val();

                var preco = $('#Preco').val();

                $.ajax({
                    type: "GET",
                    url: 'blusas_json?quantidade='+quantidade+'&tamanho='+tamanho+'&faixa_preco='+preco,
                    success: function(data) {

                        $('#Produtos-catalogo main').empty();
                        
                        for (var i = 0; i < data.length; i++) {

                            $('#Produtos-catalogo main').append(
                                '<div class="produtos-box">'+
                                '<span class="produtos-img">'+
                                '<img src="'+data[i].imagem+'">'+
                                '<span class="produto-blend"></span>'+
                                '<i class="icon-eye"></i>'+
                                '</span>'+
                                '<span class="produtos-descricao">'+
                                '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Heading-4">'+data[i].nome+'</a>'+ 
                                '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Display-3 parcelas">6x de '+data[i].preco_dividido+'</a>'+ 
                                '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Display-3 preco">'+data[i].preco+'</a>'+ 
                                '</span>'+
                                '</div>'
                                );

                        }
                        
                    }
                });

            }, 1000);

            //$(document).unbind('scroll');

            $('body').removeClass('overflow');

            $('.btn-load').hide();
            
        }

    });

}

function filtros_saias(){

    var tamanho = $('#Tamanho').val();

    var preco = $('#Preco').val();

    $.ajax({
        type: "GET",
        url: 'saias_json?quantidade=8&tamanho='+tamanho+'&faixa_preco='+preco,
        success: function(data) {

            $('#Produtos-catalogo main').empty();
            
            for (var i = 0; i < data.length; i++) {

                $('#Produtos-catalogo main').append(
                    '<div class="produtos-box">'+
                    '<span class="produtos-img">'+
                    '<img src="'+data[i].imagem+'">'+
                    '<span class="produto-blend"></span>'+
                    '<i class="icon-eye"></i>'+
                    '</span>'+
                    '<span class="produtos-descricao">'+
                    '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Heading-4">'+data[i].nome+'</a>'+ 
                    '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Display-3 parcelas">6x de '+data[i].preco_dividido+'</a>'+ 
                    '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Display-3 preco">'+data[i].preco+'</a>'+ 
                    '</span>'+
                    '</div>'
                    );

            }
            
        }
    });

}

function scroll_mvendidos(){

    $(window).on("scroll", function() {

        scroll = (window.innerHeight + window.scrollY) - 85;

        //console.log(scroll);

        //console.log(document.body.offsetHeight);

        if ((scroll) >= document.body.offsetHeight) {

            if ( window.quantidade == undefined) {
                quantidade = 12;
            } else {
                quantidade = quantidade + 4;
            }

            $('.btn-load').show();

            $('body').addClass('overflow');

            setTimeout(function(){

                var tamanho = $('#Tamanho').val();

                var preco = $('#Preco').val();

                $.ajax({
                    type: "GET",
                    url: 'mais-vendidas_json?quantidade='+quantidade+'&tamanho='+tamanho+'&faixa_preco='+preco,
                    success: function(data) {

                        $('#Produtos-catalogo main').empty();
                        
                        for (var i = 0; i < data.length; i++) {

                            $('#Produtos-catalogo main').append(
                                '<div class="produtos-box">'+
                                '<span class="produtos-img">'+
                                '<img src="'+data[i].imagem+'">'+
                                '<span class="produto-blend"></span>'+
                                '<i class="icon-eye"></i>'+
                                '</span>'+
                                '<span class="produtos-descricao">'+
                                '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Heading-4">'+data[i].nome+'</a>'+ 
                                '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Display-3 parcelas">6x de '+data[i].preco_dividido+'</a>'+ 
                                '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Display-3 preco">'+data[i].preco+'</a>'+ 
                                '</span>'+
                                '</div>'
                                );

                        }
                        
                    }
                });

            }, 1000);

            //$(document).unbind('scroll');

            $('body').removeClass('overflow');

            $('.btn-load').hide();
            
        }

    });

}

function filtros_mvendidos(){

    var tamanho = $('#Tamanho').val();

    var preco = $('#Preco').val();

    $.ajax({
        type: "GET",
        url: 'mais-vendidas_json?quantidade=8&tamanho='+tamanho+'&faixa_preco='+preco,
        success: function(data) {

            $('#Produtos-catalogo main').empty();
            
            for (var i = 0; i < data.length; i++) {

                $('#Produtos-catalogo main').append(
                    '<div class="produtos-box">'+
                    '<span class="produtos-img">'+
                    '<img src="'+data[i].imagem+'">'+
                    '<span class="produto-blend"></span>'+
                    '<i class="icon-eye"></i>'+
                    '</span>'+
                    '<span class="produtos-descricao">'+
                    '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Heading-4">'+data[i].nome+'</a>'+ 
                    '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Display-3 parcelas">6x de '+data[i].preco_dividido+'</a>'+ 
                    '<a href="/produto/'+data[i].codigo_slug+'/'+data[i].slug+'" alt="'+data[i].nome+'" title="produto-nome" class="Display-3 preco">'+data[i].preco+'</a>'+ 
                    '</span>'+
                    '</div>'
                    );

            }
            
        }
    });

}

function busca_cep(e){
    var cep = e.replace(/[^0-9]/, "");

    if(cep.length != 8){
        return false;
    }

    var url = "https://viacep.com.br/ws/"+cep+"/json/";

    $.getJSON(url, function(dadosRetorno){
        try{
            $(".endereco").val(dadosRetorno.logradouro);
            $(".bairro").val(dadosRetorno.bairro);
            $(".lugar").val(dadosRetorno.localidade+'/'+dadosRetorno.uf);
        }catch(ex){}
    });

}
function chama_boasVindas() {
 $(".content").toggleClass("blur");
 $(".carrinho-box").toggle(100);
 $(".formLogin").removeClass("formLoginOn");
 $(".formRecuperar").removeClass("formRecuperarOn");
 $(".formCadastro").removeClass("formCadastroOn");
 $(".formLogin").find("#email").val('');
 $(".formLogin").find("#senha").val('');
 $(".formLogado").toggleClass("formLogadoOn");
 $('.formCarrinho').removeClass("formCarrinhoOn");

}
function meusDados() {
  $(".perfil-pedidos").fadeOut(500);
  $(".perfil-dados").fadeIn(1000);
  $(".perfil-pedidos").removeClass("perfilOn");
  $(this).addClass("activeLink");
  $(".meusPedidosLink").removeClass("activeLink");
}
function calcula_frete(){

    //$('.message').text('');

    var e = $('#cepCarrinho').val();

    if (e.length != 0) {

        $.ajax({
            type: "GET",
            url: '/calcular_frete?cep='+e,

            beforeSend: function(data){

                $(".btnFrete").text('...');
            },

            success: function(data) {

                if (data.status == 200) {

                 $("#cepCarrinho").css('color','#35DE6E');
                 $(".btnFrete").text('OK');

                 $("#cepCarrinho").on('click',function() {
                    $("#cepCarrinho").css('color','#2D2D2D');
                    $(this).val('');
                });

             } else {

                   // cep incorreto
               }

                //$('.frete button').html('Ok');

            },

            error: function(data) {
                // erro generico
                //$('.frete button').html('Ok');
            },
            complete: function(data) {

            }

        });

    }

}

function calcula_frete_checkout(){

    var e = $('#cepCheckout').val();

    if (e.length != 0) {

        $.ajax({
            type: "GET",
            url: '/calcular_frete?cep='+e,

            beforeSend: function(data){
               $(".btnFrete").text('...');
           },

           success: function(data) {

            if (data.status == 200) {

              $("#cepCarrinho").css('color','#35DE6E');
              $(".btnFrete").text('OK');

              $("#cepCarrinho").on('click',function() {
                $("#cepCarrinho").css('color','#2D2D2D');
                $(this).val('');
            });

              $('.frete-checkout').val('');

              $('.cupomForm').hide();
              $('.cupomForm').removeClass('cepFormOn');
              $('.cupomResumo').hide();
              $('.btnCupom').hide();
              $('.box-direitaC').removeClass('cupomOn');

              $('.calculaCEPForm').toggle();
              $('.calculaCEPForm').toggleClass('cepFormOn');
              $('.box-direitaC').toggleClass('cepOn');
              $('.cepResumo').toggle();
              $('.btnCep').toggle();

          } else {
                   // cep incorreto
               }

           },

           error: function(data) {
                // erro generico
            },
            complete: function(data) {

            }

        });

    }

}

function cupom_promocional(){

    var e = $('#cupomCheckout').val();

    $.ajax({
        type: "GET",
        url: '/cupom_promocional?cupom='+e,

        beforeSend: function() {
            // animação
        },

        success: function(data) {

            if (data.status == 501) {
                // digite o código do cupom
            } else if(data.status == 502){
                // codigo não valido
            } else if(data.status == 502){
                // calcule o frete
            } else if(data.status == 200){

                // código valido
                
                $('.cupom-link').empty().text('-' +data.valor).removeClass('adicionaCupom').removeClass('resumo-link').addClass('desconto-link');

                $('.resumo-total-preco').empty().text(data.total);

                $('.calculaCEPForm').hide();
                $('.calculaCEPForm').removeClass('cepFormOn');
                $('.cepResumo').hide();
                $('.btnCep').hide();
                $('.box-direitaC').removeClass('cepOn');


                $('.cupomForm').toggle();
                $('.cupomForm').toggleClass('cepFormOn');
                $('.box-direitaC').toggleClass('cupomOn');
                $('.cupomResumo').toggle();
                $('.btnCupom').toggle();

            } else {
                // erro generico
            }
        }, 

        error:function (data){
             // erro generico
         },

         complete:function (data){

         }

     });
}

/* carrinho */

function carrinho(){

    console.log('+dtete');

    $.ajax({
        type: "GET",
        url: '/carrinho',

        beforeSend: function() {

        },

        success: function(data) {

            if(data.quantidade > 0){

                $('.carrinhoHave').fadeIn();

                $('.produtos-carrinho-container').empty();

                data.items.reverse();

                var del = 'del';
                var add = 'add';

                for (var i = 0; i < data.quantidade; i++) {

                    $('.produtos-carrinho-container').append(
                        '<div class="produtos-carrinho-box">'+
                        '<span class="photo-carrinho">'+
                        '<img src="'+data.items[i].imagem+'">'+
                        '</span>'+
                        '<span class="desc-carrinho">'+
                        '<h2 class="label">'+data.items[i].nome+'</h2>'+
                        '<span class="tamanhoCorCarrinho"><p class="tamanhoCarrinho"> P</p> | <p class="corCarrinho"> Cinza</p></span>'+
                        '<div class="MaisMenos">'+
                        '<button type="button" class="btnMenos" onclick="editar_produto('+data.items[i].id+', \''+data.items[i].quantidade+'\', \''+del+'\')">-</button>'+
                        '<span class="contadorQuantidade">'+data.items[i].quantidade+'</span>'+
                        '<button type="button" class="btnMais" onclick="editar_produto('+data.items[i].id+', \''+data.items[i].quantidade+'\', \''+add+'\')">+</button>'+
                        '</div>'+
                        '</span>'+
                        '<span class="preco-carrinho">'+
                        '<p class="preco-carrinho-produto">'+data.items[i].preco+'</p>'+
                        '<button class="btn-status remover" type="button" onclick="remover_produto('+data.items[i].id+', \''+data.items[i].tamanho+'\')">Remover</button>'+
                        '</span>'+
                        '</div>'
                        );

                }

            } else {

                $('.carrinhoHave').hide();

                $('.produtos-carrinho-container').empty().html('<p class="label carrinhoVazio">Carrinho vazio</p>');
            }

        },

        error:function (xhr, ajaxOptions, thrownError){

        },

        complete: function(data){
        } 

    });

}

function adicionar_produto(id){

    var tamanho = $('#tamanhoProduto').val();

    var qtd = $('#quantidadeProduto').val();

    //$('.modal-body').html('');

    // verifica se ta marcado

    if (tamanho != undefined || tamanho != "" || qtd != undefined || qtd != "") {

        $.ajax({
            type: "GET",
            url: 'carrinho/add_produto?id='+id+'&tamanho='+tamanho+'&qtd='+qtd,

            beforeSend: function(data){
                $('.btn-add-carrinho').text('Adicionando...');
                $('.btn-add-carrinho').prop('disable', true);
            },

            success: function(data) {

                if (data == 200) {

                    $('.carrinhoHave').show(); // mostra item

                    // sugestão.. chamar modal de carrinho.. ele automaticamente atualizara só de chamar ai pode jogar um efeito

                    /*$('.modal-title').text('Seu produto foi selecionado!');

                    $('.modal-body').html(
                        '<div class="continue-loja">'+
                            '<div class="button-loja button-loja-continuar">'+
                                '<a href="javascript:window.location.href=window.location.href">Continuar Comprando</a>'+
                            '</div>'+
                            '<div class="button-loja button-loja-carrinho">'+
                                '<a href="/carrinho/">Ver Carrinho</a>'+
                            '</div>'+
                        '</div>'
                        );*/

                    } else if(data == 501){

                    // caso tente hackear o back verifica se tem estoque

                } else {

                    // erro generico

                }

            },

            complete: function(data){
                $('.btn-add-carrinho').text('Adicionar ao carrinho');
                $('.btn-add-carrinho').prop('disable', false);
            }

        });

    } else {

        // por favor selecione o tamanho

    }

}

function editar_produto(id, qtd, acao){

    if (acao == "add") {
        qtd = parseInt(qtd) + 1;
    } else {
        qtd = parseInt(qtd) - 1;
    }

    $.ajax({
        type: "GET",
        url: 'carrinho/edit/produto?id='+id+'&qtd='+qtd,

        success: function(data) {

            carrinho();

        }
    });

}

function remover_produto(id, tamanho){

    $.ajax({
        type: "GET",
        url: 'carrinho/remove/produto?id='+id+'&tamanho='+tamanho,

        success: function(data) {

            carrinho();

        }

    });

}

/* fim carrinho */

function finalizar_compra(){

    $.ajax({
        type: "GET",
        url: '/finalizar_compra',

        success: function(data) {

            if (data == 503) {

                // calcule o frete

            } else if(data == 502){

                // o carrinho está vazio

            } else if(data == 200){

                window.location.assign("/checkout");

            } else {

                // erro generico
            }

        }

    });

}

/* adicionais dps reorganizar */

function menuAtivo() {
    $(".menu-abrir").on("click", function() {
        document.documentElement.classList.add('menu-ativo')
    })
}

function fechaMenu() {
    $(".menu-fechar").on("click", function() {
        document.documentElement.classList.remove('menu-ativo')
    });
}

function carrega_colecao(e){

    $.ajax({
        type: "GET",
        url: 'lookbook/colecao?ano='+e,

        success: function(data) {

            if(data.status == 200){

                //$('.lookbook').empty();

                var content = data.itens;
                
                for (var i = 0; i < content.length; i++) {

                    console.log('exibe');

                    $('.lookbook').append(
                        '<div class="lookbook-box">'+
                            '<img src="img/galeria/lookbook/'+content[i].imagem+'" class="lookbook-img">'+
                            '<i class="material-icons fullscreen">fullscreen</i>'+
                            '<span class="blend-lookbook"></span>'+
                        '</div>'
                    );
                }

            } else {

            }

            

        }

    });

}