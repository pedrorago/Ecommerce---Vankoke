<?php

/* rotas de login / logout / recuperação / reset de senha e  confirmação e cadastro */

Route::get('login',array('as'=>'login',function(){
	return redirect('/');
}));

Route::post('/login', 'LoginController@login');

//Route::get('/reenviar_confirmacao', 'LoginController@reenviar_confirmacao');

Route::get('/resetar_senha', 'LoginController@reset_view');

Route::post('/resetar_senha', 'LoginController@reset');

Route::post('/recuperar_senha', 'LoginController@recuperar');


Route::post('/cadastro', 'CadastroController@cadastro');

Route::get('/confirmacao', 'CadastroController@confirmacao');

/* fim rotas de login/cadastro e etc... */


/* produtos por categorias + rotas de filtros */

Route::get('/produtos', 'ProdutosController@index');

Route::get('/produtos_json', 'ProdutosController@produtos_json');

Route::get('/mais-vendidas', 'ProdutosController@mais_vendidos');

Route::get('/mais-vendidas_json', 'ProdutosController@mais_vendidos_json');

Route::get('/produtos_aleatorios', 'ProdutosController@aleatorios');

Route::get('/vestidos', 'VestidosController@index');

Route::get('/vestidos_json', 'VestidosController@vestidos_json');

Route::get('/saias', 'SaiasController@index');

Route::get('/saias_json', 'SaiasController@saias_json');

Route::get('/macacoes', 'MacacoesController@index');

Route::get('/macacoes_json', 'MacacoesController@macacoes_json');

Route::get('/calcas', 'CalcasController@index');

Route::get('/calcas_json', 'CalcasController@calcas_json');

Route::get('/blusas', 'BlusasController@index');

Route::get('/blusas_json', 'BlusasController@blusas_json');

Route::get('/colecoes', 'ProdutosController@colecoes'); // revisar essa rota

Route::get('/tshirts', 'TShirtsController@index');

Route::get('/tshirts_json', 'TShirtsController@tshirts_json');

Route::get('/outlet', 'ProdutosController@outlet');

Route::get('/outlet_json', 'ProdutosController@outlet_json');

Route::get('/produto/{cod}/{slug}', 'ProdutosController@produto'); // single

/* fim de produtos por categorias + rotas por filtros */


/* frete */

Route::get('/calcular_frete', 'CarrinhoController@calcular_frete');

/* cupom */

Route::get('/cupom_promocional', 'CheckoutController@cupom_promocional');

/* carrinho de compras */

Route::get('/carrinho', 'CarrinhoController@index');

Route::get('/carrinho/checar', 'CarrinhoController@carrinho');

Route::get('/carrinho/add_produto', 'CarrinhoController@add_produto');

Route::get('/carrinho/edit/produto', 'CarrinhoController@mudar_quantidade');

Route::get('/carrinho/remove/produto', 'CarrinhoController@remove_produto');

Route::get('/carrinho/finalizar', 'CarrinhoController@finalizar');

Route::get('/carrinho/calcular_frete', 'CarrinhoController@calcular_frete');

Route::post('/carrinho/cupom', 'CarrinhoController@checa_cupom');

/* fim de rotas carrinho de compras */

/* ir para o checkout */

Route::get('/finalizar_compra', 'CarrinhoController@finalizar');


/* rotas de páginas fixas */

Route::get('/institucional', 'PaginasController@institucional');

Route::get('/contato', 'PaginasController@contato');

Route::get('/politica_privacidade', 'PaginasController@politica_privacidade');

/* fim páginas */


/* lookbook */

Route::get('/lookbook', 'LookBookController@index');

Route::get('/lookbook/colecao', 'LookBookController@colecao');

/* home */
Route::get('/checkout', 'CheckoutController@index');

Route::get('/', 'HomeController@home');

Route::get('/home', 'HomeController@home');

Route::group(['middleware' => 'auth'], function () {

	/* logout */

	Route::get('/logout', 'LoginController@logout');

	/* perfil */

	Route::get('/perfil', 'PerfilController@index');

	Route::post('/perfil/editar', 'PerfilController@editar');

	Route::get('/perfil/pedidos', 'PerfilController@pedidos');

	Route::post('/perfil/pedidos', 'PerfilController@pedidos_listar');

	/* fim perfil */

	/* rotas de checkout */

	Route::post('/checkout/checar_dados', 'CheckoutController@salvar_dados');

	Route::get('/checkout/calcular_frete', 'CheckoutController@calcular_frete');

	Route::post('/checkout/checar_entrega', 'CheckoutController@checar_entrega');

	Route::post('/checkout/finalizar_cartao', 'CheckoutController@finalizar_cartao');

	Route::get('/checkout/teste_rede', 'CheckoutController@teste_rede');

	Route::post('/checkout/finalizar_boleto', 'CheckoutController@finalizar_boleto');

	Route::get('/checkout/finalizar', 'CheckoutController@finalizar_compra');

	Route::get('/checkout/fechar', 'CheckoutController@criar_pedido');

	/* fim de rotas de checkout */



});
