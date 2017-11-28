<?php

class ProductController extends ProductControllerCore
{
    public function init()
    {
        if ($productRewrite = Tools::getValue('rewrite_product')) {
            $idProduct = $this->getProductId ($productRewrite);
            if ($idProduct > 0) {
                $_GET['id_product'] = $idProduct;
            }else if (preg_match('/.*?([0-9]+)\-([a-zA-Z0-9-]*)(\.html)?/', $productRewrite, $arrRewrite)){
                $idProduct = $this->getProductId(end($arrRewrite));
                if($idProduct > 0) {
                    $_GET['id_product'] = $idProduct;
                }
            }
        }
        parent::init();
    }

    public function getProductId ($productRewrite)
    {
        $langId = (int) Context::getContext()->language->id;
        $sql = 'SELECT `id_product`
            FROM `'._DB_PREFIX_.'product_lang`
            WHERE `link_rewrite` = \''.pSQL(str_replace('.html', '', $productRewrite)).'\' AND `id_lang` = '.$langId;
        if (Shop::isFeatureActive() && Shop::getContext() == Shop::CONTEXT_SHOP) {
            $sql .= ' AND `id_shop` = '.(int) Shop::getContextShopID();
        }

        return (int) Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
    }
}