<?php

class shopGiftforyouPlugin extends shopPlugin
{
    /**
     * @throws waException
     */
    public function frontendFooter(): string
    {
        $pathShop = wa()->getAppStaticUrl('shop', true);
        $html = <<<HTML
<script src="{$pathShop}plugins/giftforyou/js/gift.js"></script>
HTML;
        return $html;
    }

    /**
     * @throws waException
     */
    public function routing($route = []) {
        $plugin_routing = [];
        $plugin_path = wa()->getAppPath('plugins/giftforyou/lib/config/routing.php', 'shop');
        if (file_exists($plugin_path)) {
            $plugin_routing = include($plugin_path);
        }
        return $plugin_routing;
    }
}

