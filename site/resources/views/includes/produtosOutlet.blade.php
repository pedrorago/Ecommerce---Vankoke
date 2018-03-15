<?php

foreach ($itens as $key => $value):

?>

<div class="produtos-box">
 	<a href="/produto/<?= $value['codigo_slug'].'/'.$value['slug']; ?>">
        <span class="produtos-img">
            <img src="<?= $value['imagem']; ?>">
            <span class="produto-blend"></span>
            <i class="icon-eye"></i>
        </span>
    </a>
 	<span class="produtos-descricao">
 		<a href="/produto/<?= $value['codigo_slug'].'/'.$value['slug']; ?>" alt="produto-nome" title="produto-nome" class="Heading-4"><?= strtoupper($value['nome']); ?></a>
 		<a href="/produto/<?= $value['codigo_slug'].'/'.$value['slug']; ?>" alt="produto-nome" title="produto-nome" class="Display-3 parcelas">6x de <?= $value['preco_dividido']; ?></a>
 		<a href="/produto/<?= $value['codigo_slug'].'/'.$value['slug']; ?>" alt="produto-nome" title="produto-nome" class="Display-3 preco"><?= $value['preco']; ?></a>
 	</span>
</div>

<?php

endforeach;

?>