<?php

return array(
    'name' => 'Лайк  товару (Оценка пользователей)',
    'description' => 'Плагин позволяет добавить к товару кнопку, которая позволяет оценить товар',
    'img' => 'img/icon.png',
    'vendor' => '1005778',
    'version' => '1.1.0',
	'frontend' => true,
    'handlers' => array(
		'frontend_footer' => 'frontendFooter',
		'backend_products' => 'backendProducts',
		'products_collection' => 'likeproductProductsCollection',
		'backend_product_edit' => 'backendProductEdit',
	)
);
