<?php

class shopLikeproductPluginFrontendChangelikeController extends waJsonController
{
	public function execute() {
		$id = waRequest::post('id');
		$type = waRequest::post('type');
		if (is_numeric($id)) {
			$model = new waModel();
			if (!$model->query("UPDATE `shop_product` SET `like_count` = `like_count`" . (($type == "1")? "+" : "-") . "1 WHERE `id` = '".$model->escape($id)."'")) {
				$this->errors = "Error update count like";
			}
			else {
				$count = $model->query("SELECT `like_count` FROM `shop_product` WHERE `id` = '".$model->escape($id)."'")->fetch();
				$this->response['count'] = $count['like_count'];
			}
		}
		else {
			$this->errors = "Error product id";
		}
	}
}