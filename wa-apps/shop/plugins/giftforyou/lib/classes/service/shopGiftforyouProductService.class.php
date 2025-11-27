<?php

class shopGiftforyouProductService
{
    /**
     * Получение списка ID товаров из набора (статического или динамического)
     *
     * @param string $set_id
     * @return array
     * @throws waException
     */
    public function getProductsFromSet(string $set_id): array
    {
        $set_model = new shopSetModel();
        $set = $set_model->getById($set_id);

        if (empty($set)) {
            return [];
        }

        $product_ids = [];

        if (shopSetModel::TYPE_STATIC === $set['type']) {
            $set_products_model = new shopSetProductsModel();
            $products = $set_products_model->getByField('set_id', $set_id, true);
            foreach ($products as $product) {
                $product_ids[] = $product['product_id'];
            }
        } else {
            $collection = new shopProductsCollection('set/' . $set_id);
            $product_ids = array_keys($collection->getProducts('id', 0, 1000));
        }

        return $product_ids;
    }

    /**
     * Получение случайного товара из набора
     *
     * @param string $set_id
     * @return int|null
     * @throws waException
     */
    public function getRandomProductFromSet(string $set_id): ?int
    {
        $product_ids = $this->getProductsFromSet($set_id);

        if (empty($product_ids)) {
            return null;
        }

        $random_key = array_rand($product_ids);
        return $product_ids[$random_key];
    }

    public function getProductData(int $product_id): ?array
    {
        $product_model = new shopProductModel();
        $product = $product_model->getById($product_id);

        if (empty($product)) {
            return null;
        }

        // Получаем изображение товара
        $product_images_model = new shopProductImagesModel();
        $images = $product_images_model->getByField('product_id', $product_id, true);
        $image_url = '';
        if (!empty($images)) {
            $image = reset($images);
            $image_url = shopImage::getUrl($image, '200x200');
        }

        $product_url = wa()->getRouteUrl('shop/frontend/product', array('product_url' => $product['url']));

        $price = shop_currency($product['price'], $product['currency'], null, true);

        return [
            'id'          => $product['id'],
            'name'        => $product['name'],
            'price'       => $price,
            'price_raw'   => $product['price'],
            'currency'    => $product['currency'],
            'image'       => $image_url,
            'url'         => $product_url,
            'product_url' => $product['url'],
            'product'     => $product,
        ];
    }

    public function formatProductForResponse(array $product_data): array
    {
        return [
            'id'    => $product_data['id'],
            'name'  => $product_data['name'],
            'price' => $product_data['price'],
            'image' => $product_data['image'],
            'url'   => $product_data['url'],
        ];
    }
}

