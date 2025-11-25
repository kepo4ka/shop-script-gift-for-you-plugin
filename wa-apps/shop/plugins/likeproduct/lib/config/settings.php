<?php

return array(
    'color_icon' => array(
        'value' => 'red',
        'title' => 'Цвет иконки',
        'control_type' => waHtmlControl::CUSTOM.' '.'shopLikeproductPlugin::settingColor',
		'description' => 'Выберите цвет иконки',
    ),
    'color_icon_hover' => array(
        'value' => 'red',
        'title' => 'Цвет иконки при наведении',
        'control_type' => waHtmlControl::CUSTOM.' '.'shopLikeproductPlugin::settingColor',
		'description' => 'Выберите цвет иконки при наведении',
    ),
    'color_icon_active' => array(
        'value' => 'red',
        'title' => 'Цвет активной иконки',
        'control_type' => waHtmlControl::CUSTOM.' '.'shopLikeproductPlugin::settingColor',
		'description' => 'Выберите цвет активной иконки',
    ),
    'color_count' => array(
        'value' => 'red',
        'title' => 'Цвет текста счетчика',
        'control_type' => waHtmlControl::CUSTOM.' '.'shopLikeproductPlugin::settingColor',
		'description' => 'Выберите цвет текста счетчика',
    ),
    'size_icon' => array(
        'value' => '16',
        'title' => 'Размер иконки',
        'control_type' => waHtmlControl::INPUT,
		'description' => 'Введите размер иконки в пикселях',
    ),
    'title_link' => array(
        'value' => 'Мне нравится',
        'title' => 'Текст при наведении',
        'control_type' => waHtmlControl::INPUT,
		'description' => 'Подсказка пользователю при наведении курсора на иконку',
    ),
    'dop_text' => array(
        'value' => '',
        'title' => 'Дополнительный текст',
        'control_type' => waHtmlControl::INPUT,
		'description' => 'Дополнительный текст между иконкой и счетчиком',
    ),
);