<?php

/*
	Westering Studio
	E-mail: wa_plugins@westering.ru
*/

$model = new waModel();
try {
    $model->query('SELECT `like_count` FROM `shop_product` WHERE 0');
} catch (waDbException $e) {
    $model->exec("ALTER TABLE `shop_product` DROP `like_count`");
}

?>