<?php

class shopGiftforyouPluginFrontendSendemailController extends waJsonController
{
    protected ?shopGiftforyouProductService $productService;

    public function execute()
    {
        $email = $this->validateEmail();
        if (null === $email) {
            return;
        }

        $product_id = $this->validateProductId();
        if (null === $product_id) {
            return;
        }

        $product_data = $this->getProductService()->getProductData($product_id);
        if (null === $product_data) {
            $this->errors = _wp('Товар не найден');
            return;
        }

        $this->sendEmail($email, $product_data);
    }

    protected function validateEmail()
    {
        $email = waRequest::post('email', '', waRequest::TYPE_STRING_TRIM);

        if (empty($email)) {
            $this->errors = _wp('Не указан email');
            return null;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors = _wp('Некорректный email адрес');
            return null;
        }

        return $email;
    }

    protected function validateProductId()
    {
        $product_id = waRequest::post('product_id', 0, waRequest::TYPE_INT);

        if (empty($product_id)) {
            $this->errors = _wp('Не указан товар');
            return null;
        }

        return $product_id;
    }

    /**
     * Отправка email с информацией о товаре
     *
     * @param string $email Email адрес получателя
     * @param array $product_data Данные товара
     * @throws SmartyException
     * @throws waException
     */
    protected function sendEmail(string $email, array $product_data): void
    {
        $view = wa()->getView();
        $view->assign(array(
            'product_name'  => $product_data['name'],
            'product_price' => $product_data['price'],
            'product_url'   => $product_data['url'],
        ));

        $template_path = wa()->getAppPath('plugins/giftforyou/templates/mail/GiftEmail.html', 'shop');
        if (!file_exists($template_path)) {
            // Если шаблон не найден, используем простой текст
            $body = _wp("Здравствуйте!\n\n");
            $body .= sprintf(_wp("Ваш подарок — товар: %s\n"), $product_data['name']);
            $body .= sprintf(_wp("Цена: %s\n"), $product_data['price']);
            $body .= "Ссылка: {$product_data['url']}\n\n";
            $body .= sprintf(_wp("Ссылка: %s\n\n"), $product_data['url']);
            $body .= _wp('Спасибо, что участвуете в акции!');
        } else {
            $body = $view->fetch($template_path);
        }

        try {
            $message = new waMailMessage(_wp('Ваш подарок!'), $body);
            $message->setTo($email);
            $message->send();

            $this->response = array(
                'message' => _wp('Письмо успешно отправлено'),
            );
        } catch (Exception $e) {
            $this->errors = _wp('Ошибка при отправке письма: ') . $e->getMessage();
        }
    }

    protected function getProductService()
    {
        if (null === $this->productService) {
            $this->productService = new shopGiftforyouProductService();
        }
        return $this->productService;
    }
}

