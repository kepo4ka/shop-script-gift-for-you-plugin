<?php

$set_model = new shopSetModel();
$sets = $set_model->getAll();

$set_id_options = [
    '' => _wp('Выберите набор товаров'),
];

foreach ($sets as $set) {
    $set_id_options[$set['id']] = $set['name'] . ' (' . $set['id'] . ')';
}

return [
    'set_id' => [
        'title'        => _wp('Набор товаров'),
        'control_type' => waHtmlControl::SELECT,
        'options'      => $set_id_options,
        'description'  => _wp('Выберите набор товаров, из которого будет выбираться случайный товар для подарка'),
        'required'     => true,
    ],
];

