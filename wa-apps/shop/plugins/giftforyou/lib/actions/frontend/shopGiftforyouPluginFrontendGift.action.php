<?php

class shopGiftforyouPluginFrontendGiftAction extends waViewAction
{
    public function execute()
    {
        $this->setLayout(new shopFrontendLayout());

        $current_url = wa()->getConfig()->getRequestUrl(false, true);
        $base_url = rtrim(dirname($current_url), '/') . '/';

        $get_gift_url = $base_url . 'gift/get/';
        $send_email_url = $base_url . 'gift/send/';

        $this->view->assign([
            'get_gift_url' => $get_gift_url,
            'send_email_url' => $send_email_url,
        ]);
    }

    public function display($clear_assign = true)
    {
        $template_path = wa()->getAppPath('plugins/giftforyou/templates/actions/frontend/FrontendGift.html', 'shop');
        $this->setTemplate($template_path);

        return parent::display($clear_assign);
    }
}

