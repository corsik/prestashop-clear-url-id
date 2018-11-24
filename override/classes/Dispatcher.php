<?php

class Dispatcher extends DispatcherCore
{
    public $default_routes = array(
        /* Must be after the product and category rules in order to avoid conflict */
        'layered_rule' => array(
            'controller' =>    'category',
            'rule' =>        '{id}-{rewrite}{/:selected_filters}',
            'keywords' => array(
                'id' =>            array('regexp' => '[0-9]+'),
                /* Selected filters is used by the module blocklayered */
                'selected_filters' =>    array('regexp' => '.*', 'param' => 'selected_filters'),
                'rewrite' =>        array('regexp' => '([_a-zA-Z0-9\pL\pS-]){2,}', 'param' => 'rewrite_category'),
                'meta_keywords' =>    array('regexp' => '[_a-zA-Z0-9-\pL]*'),
                'meta_title' =>        array('regexp' => '[_a-zA-Z0-9-\pL]*'),
            ),
        ),
        'product_rule' => array(
            'controller' =>    'product',
            'rule' =>        '{category:/}{-:id_product_attribute}{rewrite}{-:ean13}.html',
            'keywords' => array(
                'id' =>            array('regexp' => '[0-9]+'),
                'id_product_attribute' => array('regexp' => '[0-9]+'),
                'rewrite' =>        array('regexp' => '([_a-zA-Z0-9\pL\pS-]){2,}', 'param' => 'rewrite_product'),
                'ean13' =>        array('regexp' => '[0-9\pL]*'),
                'category' =>        array('regexp' => '([_a-zA-Z0-9-\pL]){2,}'),
                'categories' =>        array('regexp' => '[/_a-zA-Z0-9-\pL]*'),
                'reference' =>        array('regexp' => '[_a-zA-Z0-9-\pL]*'),
                'meta_keywords' =>    array('regexp' => '[_a-zA-Z0-9-\pL]*'),
                'meta_title' =>        array('regexp' => '[_a-zA-Z0-9-\pL]*'),
                'manufacturer' =>    array('regexp' => '[_a-zA-Z0-9-\pL]*'),
                'supplier' =>        array('regexp' => '[_a-zA-Z0-9-\pL]*'),
                'price' =>            array('regexp' => '[0-9\.,]*'),
                'tags' =>            array('regexp' => '[a-zA-Z0-9-\pL]*'),
            ),
        ),
        'category_rule' => array(
            'controller' =>    'category',
            'rule' =>        '{id}-{rewrite}',
            'keywords' => array(
                'id' =>            array('regexp' => '[0-9]+'),
                'rewrite' =>        array('regexp' => '([_a-zA-Z0-9\pL\pS-]){2,}', 'param' => 'rewrite_category'),
                'meta_keywords' =>    array('regexp' => '[_a-zA-Z0-9-\pL]*'),
                'meta_title' =>        array('regexp' => '[_a-zA-Z0-9-\pL]*'),
            ),
        ),
        'supplier_rule' => array(
            'controller' =>    'supplier',
            'rule' =>        '{id}__{rewrite}',
            'keywords' => array(
                'id' =>            array('regexp' => '[0-9]+', 'param' => 'id_supplier'),
                'rewrite' =>        array('regexp' => '[_a-zA-Z0-9\pL\pS-]*'),
                'meta_keywords' =>    array('regexp' => '[_a-zA-Z0-9-\pL]*'),
                'meta_title' =>        array('regexp' => '[_a-zA-Z0-9-\pL]*'),
            ),
        ),
        'manufacturer_rule' => array(
            'controller' =>    'manufacturer',
            'rule' =>        '{id}_{rewrite}',
            'keywords' => array(
                'id' =>            array('regexp' => '[0-9]+'),
                'rewrite' =>        array('regexp' => '[_a-zA-Z0-9\pL\pS-]*', 'param' => 'rewrite_manufacturer'),
                'meta_keywords' =>    array('regexp' => '[_a-zA-Z0-9-\pL]*'),
                'meta_title' =>        array('regexp' => '[_a-zA-Z0-9-\pL]*'),
            ),
        ),
        'cms_rule' => array(
            'controller' =>    'cms',
            'rule' =>        'content/{rewrite}',
            'keywords' => array(
                'id' =>            array('regexp' => '[0-9]+'),
                'rewrite' =>        array('regexp' => '[_a-zA-Z0-9\pL\pS-]*', 'param' => 'rewrite_cms'),
                'meta_keywords' =>    array('regexp' => '[_a-zA-Z0-9-\pL]*'),
                'meta_title' =>        array('regexp' => '[_a-zA-Z0-9-\pL]*'),
            ),
        ),
        'cms_category_rule' => array(
            'controller' =>    'cms',
            'rule' =>        'content/category/{rewrite}',
            'keywords' => array(
                'id' =>            array('regexp' => '[0-9]+'),
                'rewrite' =>        array('regexp' => '[_a-zA-Z0-9\pL\pS-]*', 'param' => 'rewrite_cms_category'),
                'meta_keywords' =>    array('regexp' => '[_a-zA-Z0-9-\pL]*'),
                'meta_title' =>        array('regexp' => '[_a-zA-Z0-9-\pL]*'),
            ),
        ),
        'module' => array(
            'controller' =>    null,
            'rule' =>        'module/{module}{/:controller}',
            'keywords' => array(
                'module' =>        array('regexp' => '[_a-zA-Z0-9_-]+', 'param' => 'module'),
                'controller' =>        array('regexp' => '[_a-zA-Z0-9_-]+', 'param' => 'controller'),
            ),
            'params' => array(
                'fc' => 'module',
            ),
        ),

    );

    public function prepRoutes($routes = []) {

        $reverse_routes = array_reverse($routes, true);
        $index_routes = $reverse_routes['index'];
        unset($reverse_routes['index']);
        $reverse_routes['index'] = $index_routes;
        return $reverse_routes;

    }

    public function getController($id_shop = null)
    {
        if (defined('_PS_ADMIN_DIR_')) {
            $_GET['controllerUri'] = Tools::getvalue('controller');
        }
        if ($this->controller) {
            $_GET['controller'] = $this->controller;
            return $this->controller;
        }

        if (isset(Context::getContext()->shop) && $id_shop === null) {
            $id_shop = (int)Context::getContext()->shop->id;
        }

        $controller = Tools::getValue('controller');

        if (isset($controller) && is_string($controller) && preg_match('/^([0-9a-z_-]+)\?(.*)=(.*)$/Ui', $controller, $m)) {
            $controller = $m[1];
            if (isset($_GET['controller'])) {
                $_GET[$m[2]] = $m[3];
            } elseif (isset($_POST['controller'])) {
                $_POST[$m[2]] = $m[3];
            }
        }

        if (!Validate::isControllerName($controller)) {
            $controller = false;
        }

        // Use routes ? (for url rewriting)
        if ($this->use_routes && !$controller && !defined('_PS_ADMIN_DIR_')) {
            if (!$this->request_uri) {
                return strtolower($this->controller_not_found);
            }
            $controller = $this->controller_not_found;
            $test_request_uri = preg_replace('/(=http:\/\/)/', '=', $this->request_uri);

            // If the request_uri matches a static file, then there is no need to check the routes, we keep "controller_not_found" (a static file should not go through the dispatcher)
            if (!preg_match('/\.(gif|jpe?g|png|css|js|ico)$/i', parse_url($test_request_uri, PHP_URL_PATH))) {
                // Add empty route as last route to prevent this greedy regexp to match request uri before right time
                if ($this->empty_route) {
                    $this->addRoute($this->empty_route['routeID'], $this->empty_route['rule'], $this->empty_route['controller'], Context::getContext()->language->id, array(), array(), $id_shop);
                }

                list($uri) = explode('?', $this->request_uri);

                $routes = $this->prepRoutes ($this->routes[$id_shop][Context::getContext()->language->id]);

                if (isset($routes)) {
                    foreach ($routes as $route) {
                        if (preg_match($route['regexp'], $uri, $m)) {
                            // Route found ! Now fill $_GET with parameters of uri
                            foreach ($m as $k => $v) {
                                if (!is_numeric($k)) {
                                    $_GET[$k] = $v;
                                }
                            }

                            $controller = $route['controller'] ? $route['controller'] : $_GET['controller'];
                            if (!empty($route['params'])) {
                                foreach ($route['params'] as $k => $v) {
                                    $_GET[$k] = $v;
                                }
                            }

                            // A patch for module friendly urls
                            if (preg_match('#module-([a-z0-9_-]+)-([a-z0-9_]+)$#i', $controller, $m)) {
                                $_GET['module'] = $m[1];
                                $_GET['fc'] = 'module';
                                $controller = $m[2];
                            }

                            if (isset($_GET['fc']) && $_GET['fc'] == 'module') {
                                $this->front_controller = self::FC_MODULE;
                            }
                            break;
                        }
                    }
                }
            }
            if ($controller == 'index' || preg_match('/^\/index.php(?:\?.*)?$/', $this->request_uri)) {
                $controller = $this->useDefaultController();
            }
        }

        $this->controller = str_replace('-', '', $controller);
        $_GET['controller'] = $this->controller;
        return $this->controller;
    }
}