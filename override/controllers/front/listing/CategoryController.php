<?php
class CategoryController extends CategoryControllerCore
{
    /*
    * module: crk_urls_clear
    * date: 2017-11-27 23:11:33
    * version: 0.0.2
    */
    /*
    * module: crk_urls_clear
    * date: 2018-06-14 16:09:19
    * version: 0.0.2
    */
    public function init()
    {
        if ($categoryRewrite = Tools::getValue('rewrite_category')) {
            $idCategory = $this->getCategorytId ($categoryRewrite);
            if ($idCategory > 0) {
                $_GET['id_category'] = $idCategory;
            }else if (preg_match('/.*?([0-9]+)\-([a-zA-Z0-9-]*)(\.html)?/', $categoryRewrite, $arrRewrite)){
                $idCategory = $this->getCategorytId(end($arrRewrite));
                if($idCategory > 0) {
                    $_GET['id_category'] = $idCategory;
                }
            }
        }
        parent::init();
    }
    /*
    * module: crk_urls_clear
    * date: 2017-11-27 23:11:34
    * version: 0.0.2
    */
    /*
    * module: crk_urls_clear
    * date: 2018-06-14 16:09:19
    * version: 0.0.2
    */
    public function getCategorytId ($categoryRewrite)
    {
        $sql = 'SELECT `id_category` FROM `'._DB_PREFIX_.'category_lang`
            WHERE `link_rewrite` = \''.pSQL(str_replace('.html', '', $categoryRewrite)).'\' AND `id_lang` = '.Context::getContext()->language->id;
        if (Shop::isFeatureActive() && Shop::getContext() == Shop::CONTEXT_SHOP) {
            $sql .= ' AND `id_shop` = '.(int) Shop::getContextShopID();
        }
        return (int) Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
    }
}
