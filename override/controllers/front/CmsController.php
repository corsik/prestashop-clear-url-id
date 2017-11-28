<?php

class CmsController extends CmsControllerCore
{
    public function init()
    {
        $shopSql = '';
        if (Shop::isFeatureActive() && Shop::getContext() == Shop::CONTEXT_SHOP) {
            $shopSql = ' AND s.`id_shop` = '.(int) Shop::getContextShopID();
        }

        if ($cmsRewrite = Tools::getValue('rewrite_cms')) {
            $idCms = $this->getCmsId ($cmsRewrite, $shopSql);
            if ($idCms > 0) {
                $_GET['id_cms'] = $idCms;
            }else if (preg_match('/.*?([0-9]+)\-([a-zA-Z0-9-]*)/', $cmsRewrite, $arrRewrite)) {
                $idCms = $this->getCmsId(end($arrRewrite), $shopSql);
                if ($idCms > 0) {
                    $_GET['id_cms'] = $idCms;
                }
            }
        }elseif ($cmsCategoryRewrite = Tools::getValue('rewrite_cms_category')) {
            $idCmsCategory = $this->getCmsCategoryId ($cmsCategoryRewrite, $shopSql);
            if ($idCmsCategory > 0) {
                $_GET['id_cms_category'] = $idCmsCategory;
            }else if (preg_match('/.*?([0-9]+)\-([a-zA-Z0-9-]*)/', $cmsCategoryRewrite, $arrRewrite)) {
                $idCmsCategory = $this->getCmsCategoryId (end($arrRewrite), $shopSql);
                if ($idCmsCategory > 0) {
                    $_GET['id_cms_category'] = $idCmsCategory;
                }
            }
        }

        parent::init();
    }

    public function getCmsId ($cmsRewrite, $shopSql)
    {
        $sql = 'SELECT l.`id_cms`
                FROM `'._DB_PREFIX_.'cms_lang` l
                LEFT JOIN `'._DB_PREFIX_.'cms_shop` s ON (l.`id_cms` = s.`id_cms`)
                WHERE l.`link_rewrite` = \''.pSQL(str_replace('.html', '', $cmsRewrite)).'\''.$shopSql;

        return (int) Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
    }

    public function getCmsCategoryId ($cmsRewrite, $shopSql)
    {
        $sql = 'SELECT l.`id_cms_category`
                FROM `'._DB_PREFIX_.'cms_category_lang` l
                LEFT JOIN `'._DB_PREFIX_.'cms_category_shop` s ON (l.`id_cms_category` = s.`id_cms_category`)
                WHERE `link_rewrite` = \''.pSQL(str_replace('.html', '', $cmsRewrite)).'\''.$shopSql;

        return (int) Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
    }
}
