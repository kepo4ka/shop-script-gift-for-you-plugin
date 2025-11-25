<?php

/*
	Westering Studio
	E-mail: wa_plugins@westering.ru
*/

$model = new waModel();
try {
    $model->query('SELECT `like_count` FROM `shop_product` WHERE 0');
} catch (waDbException $e) {
    $model->exec("ALTER TABLE `shop_product` ADD `like_count` INT(11) NOT NULL DEFAULT '0'");
}

?>