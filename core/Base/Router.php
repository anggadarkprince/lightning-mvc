<?php
namespace Core\Base;

use Exception;

class Router
{
    protected $routes = [];

    protected $params = [];

    public function add($route, $params = [])
    {
        $route = empty($route) ? '/' : $route;
        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
        $route = '/^' . $route . '$/i';
        $this->routes[$route] = $params;
    }

    /**
     * Get routes collection.
     *
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Matching url with pattern in route collection.
     *
     * @param $url
     * @return bool
     */
    public function match($url)
    {
        $url = empty($url) ? '/' : $url;
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                $params = empty($params) ? [] : $params;
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    /**
     * Get parameter of route request.
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Dispatching request to controller.
     *
     * @param $url
     * @throws Exception
     */
    public function dispatch($url)
    {
        $url = $this->removeQueryStringVariables($url);

        if ($this->match($url)) {
            $controller = $this->params['controller'];
            $controller = $this->convertToStudlyCaps($controller);
            $controller = $this->getNameSpace() . $controller . 'Controller';

            if (class_exists($controller)) {
                $controllerObject = new $controller($this->params);
                $action = $this->params['action'];
                $action = $this->convertToCamelCase($action);

                // because we implement __call magic method in controller, this line is always true.
                if (is_callable([$controllerObject, $action])) {
                    // we decide that method with suffix "Action" / "action" is not allowed to direct call,
                    // because we need to invoke before() and after() filter.
                    if (preg_match('/action$/i', $action) == 0) {
                        $controllerObject->$action();
                    } else {
                        throw new Exception("Calling method $action (in controller $controller) not allowed");
                    }
                } else {
                    throw new Exception("Method $action (in controller $controller) not found");
                }
            } else {
                throw new Exception("Controller class $controller not found.");
            }
        } else {
            throw new Exception("No route matched.", 404);
        }
    }

    /**
     * Extract route, exclude query string.
     *
     * @param $url
     * @return string
     */
    protected function removeQueryStringVariables($url)
    {
        if ($url != '') {
            // url home/index?page=2&order=asc become Array([0] => home/index [1] => page=2&order=asc)
            // url ?page=2&order=asc become Array([0] => page=2 [1] => order=asc)
            $parts = explode('&', $url, 2);
            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }

        // unset route from query string.
        foreach ($_GET as $part => $value) {
            if ($part == $url) {
                unset($_GET[$url]);
            }
        }

        return $url;
    }

    /**
     * Change url part to StudlyCaps for class name.
     * activity-report become ActivityReport
     * activity become Activity
     *
     * @param $string
     * @return mixed
     */
    protected function convertToStudlyCaps($string)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    /**
     * Change url part to camelCase for method name.
     * save-user become saveUser
     * ajax-get-user become ajaxGetUser
     *
     * @param $string
     * @return string
     */
    protected function convertToCamelCase($string)
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }

    /**
     * Get default namespace.
     * @return string
     */
    protected function getNameSpace()
    {
        $namespace = 'App\Controllers\\';

        // add additional namespace from route setting
        if (array_key_exists('namespace', $this->params)) {
            $namespace .= $this->params['namespace'] . '\\';
        }

        return $namespace;
    }

}