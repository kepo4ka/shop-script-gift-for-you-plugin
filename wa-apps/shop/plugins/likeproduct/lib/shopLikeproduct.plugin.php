<?php

/*
	Westering Studio
	E-mail: wa_plugins@westering.ru
*/

class shopLikeproductPlugin extends shopPlugin
{
	public function frontendFooter() {
		$plugin = wa()->getPlugin('likeproduct');
		$color = ($ci = $plugin->getSettings('color_icon'))? $ci : '#c4c4c4';
		$color_hover = ($cih = $plugin->getSettings('color_icon_hover'))? $cih : '#80807e';
		$color_active = ($cia = $plugin->getSettings('color_icon_active'))? $cia : '#80807e';
		$color_count = ($cic = $plugin->getSettings('color_count'))? $cic : '#80807e';
		$pathShop = wa()->getAppStaticUrl('shop', true);
$html = <<<HTML
<script>
$(function(){
	$("head").append('<style>.product_like {fill:{$color};}.product_like:hover {fill:{$color_hover};}.product_like_link.active .product_like {fill:{$color_active};}.product_icon {margin:0px 5px;vertical-align:middle;}.product_like_count {font-weight:bold;color:{$color_count};}</style>');
	try {
		$.cookie();
	}
	catch(err) {
		var script = document.createElement('script');
		script.type = 'text/javascript';
		script.src = '{$pathShop}plugins/likeproduct/js/jquery.cookie.js';
		$("head").append(script);
	}
});
</script>
<script src="{$pathShop}plugins/likeproduct/js/like.js"></script>
HTML;
		return $html;
	}
	
	public static function settingColor($name, $values) {
$field = <<<HTML
<input type="color" name="{$name}" value="{$values['value']}">
HTML;
		return $field;
	}
	
	public static function getLikeButton($productID) {
		if (is_numeric($productID)) {
			$model = new waModel();
			$plugin = wa()->getPlugin('likeproduct');
			$size = ($si = $plugin->getSettings('size_icon'))? $si : 16;
			$title = ($tl = $plugin->getSettings('title_link'))? htmlspecialchars($tl) : 'Мне нравится';
			$text = ($tx = $plugin->getSettings('dop_text'))? ' ' . htmlspecialchars($tx) . ' ' : '';
			$color = ($ci = $plugin->getSettings('color_icon'))? $ci : '#c4c4c4';
			$url = wa()->getAppUrl('shop');
			$count = $model->query("SELECT `like_count` FROM `shop_product` WHERE `id` = '".$model->escape($productID)."'")->fetch();
			if ($count) {		
$html = <<<HTML
<a href="#" title="{$title}" class="product_like_link cll{$productID}" onclick="product_like('{$productID}','{$url}');return false;">
	<svg class="product_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" width="{$size}px" height="{$size}px" viewBox="0 0 176.104 176.104" style="enable-background:new 0 0 176.104 176.104;" xml:space="preserve">
		<path class="product_like"  d="M150.383,18.301c-7.13-3.928-15.308-6.187-24.033-6.187c-15.394,0-29.18,7.015-38.283,18.015    c-9.146-11-22.919-18.015-38.334-18.015c-8.704,0-16.867,2.259-24.013,6.187C10.388,26.792,0,43.117,0,61.878    C0,67.249,0.874,72.4,2.457,77.219c8.537,38.374,85.61,86.771,85.61,86.771s77.022-48.396,85.571-86.771    c1.583-4.819,2.466-9.977,2.466-15.341C176.104,43.124,165.716,26.804,150.383,18.301z" fill="{$color}"/>
	</svg><span class="product_like_dop_text">{$text}</span><span class="product_like_count">{$count['like_count']}</span>
</a>
HTML;
				return $html;
			}
		}
		return false;
	}
	
	public function backendProducts() {
		$model = new waModel();
		$count = $model->query('SELECT `id` FROM `shop_product` WHERE `like_count` > 0')->count();
		$links = <<<HTML
            <li id="likeproduct">
                <span class="count">{$count}</span>
                <a href="#/products/hash=likeproduct">
				<svg class="product_icon" height="16" width="16" style="margin-left: -20px;vertical-align: middle;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 176.104 176.104" style="enable-background:new 0 0 176.104 176.104;" xml:space="preserve">
					<path class="product_like"  d="M150.383,18.301c-7.13-3.928-15.308-6.187-24.033-6.187c-15.394,0-29.18,7.015-38.283,18.015    c-9.146-11-22.919-18.015-38.334-18.015c-8.704,0-16.867,2.259-24.013,6.187C10.388,26.792,0,43.117,0,61.878    C0,67.249,0.874,72.4,2.457,77.219c8.537,38.374,85.61,86.771,85.61,86.771s77.022-48.396,85.571-86.771    c1.583-4.819,2.466-9.977,2.466-15.341C176.104,43.124,165.716,26.804,150.383,18.301z" fill="red"/>
				</svg>
				Товары понравившиеся посетителям</a>
            </li>
HTML;
        return array(
            'sidebar_top_li' => $links,
        );
	}
	
	public function likeproductProductsCollection($params) {
		$collection = $params['collection'];
        $hash = $collection->getHash();
		if (count($hash) == 1 && $hash[0] == 'likeproduct') {
			$collection->addWhere("p.like_count > 0");
			//$collection->orderBy('like_count', 'desc');
            $collection->addTitle('Товары понравившиеся посетителям');
			//print_r($collection->getSQL());
			//die();
			return true;
        }
		return false;
	}
	
	public function backendProductEdit($product) {
		$html = '<div class="field">
				<div class="name">Количество лайков (мне нравится)</div>
				<div class="value no-shift">
					<input type="number" name="product[like_count]" value="'.$product->like_count.'" />
				</div>
			</div>';
		return array('basics' => $html);
	}
}