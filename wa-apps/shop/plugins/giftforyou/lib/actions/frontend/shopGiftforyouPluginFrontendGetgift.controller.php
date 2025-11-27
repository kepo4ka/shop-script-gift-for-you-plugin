<?php

class shopGiftforyouPluginFrontendGetgiftController extends waJsonController
{
    private ?shopGiftforyouProductService $productService;

    /**
     * @throws waException
     */
    public function execute()
    {
        $set_id = $this->getSetId();
        if (null === $set_id) {
            return;
        }

        if (!$this->validateSet($set_id)) {
            return;
        }

        $product_data = $this->getRandomProduct($set_id);
        if (null === $product_data) {
            return;
        }

        $this->buildResponse($product_data);
    }

    protected function getSetId(): ?string
    {
        $plugin = wa()->getPlugin('giftforyou');
        $set_id = $plugin->getSettings('set_id');

        if (empty($set_id)) {
            $this->errors = _wp('Набор товаров не настроен');
            return null;
        }

        return $set_id;
    }

    protected function validateSet(string $set_id): bool
    {
        $set_model = new shopSetModel();
        $set = $set_model->getById($set_id);

        if (empty($set)) {
            $this->errors = _wp('Набор товаров не найден');
            return false;
        }

        $product_ids = $this->getProductService()->getProductsFromSet($set_id);
        if (empty($product_ids)) {
            $this->errors = _wp('В наборе нет товаров');
            return false;
        }

        return true;
    }

    /**
     * Получение случайного товара из набора через сервис
     *
     * @param string $set_id ID набора
     * @return array|null Данные товара или null, если товар не найден
     * @throws waException
     */
    protected function getRandomProduct(string $set_id): ?array
    {
        $product_id = $this->getProductService()->getRandomProductFromSet($set_id);

        if (null === $product_id) {
            $this->errors = _wp('Не удалось выбрать товар из набора');
            return null;
        }

        $product_data = $this->getProductService()->getProductData($product_id);

        if (null === $product_data) {
            $this->errors = _wp('Товар не найден');
            return null;
        }

        return $product_data;
    }

    protected function buildResponse(array $product_data): void
    {
        $this->response = $this->getProductService()->formatProductForResponse($product_data);
    }

    /**
     * Получение экземпляра сервиса продуктов
     *
     * @return shopGiftforyouProductService
     */
    protected function getProductService()
    {
        if (null === $this->productService) {
            $this->productService = new shopGiftforyouProductService();
        }
        return $this->productService;
    }
}

