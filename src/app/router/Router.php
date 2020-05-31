<?php


namespace phpcmx\common\app\router;


use phpcmx\common\app\request\Request;

/**
 * 路由调度类
 * Class Router
 *
 * @package phpcmx\common\app\router
 */
class Router
{
    /** @var RouteBase[] */
    private $_routes = [];
    /** @var RouteBase */
    private $_current_route;

    /**
     * run
     *
     * @param Request $request
     */
    public function route(Request $request) {
        if (empty($this->_routes)) {
            // 默认router
            $this->_routes = [new RouteStatic()];
        }

        // 倒序执行
        $routes = array_reverse($this->_routes);
        foreach ($routes as $route_name => $route) {
            if ($route->route($request)) {
                $this->_current_route = $route_name;
                break;
            }
        }

        if (empty($this->_current_route)) {
            $this->_current_route = new RouteStatic();
        }
    }

    /**
     * 注册路由。
     * 最后注册的路由，最先使用
     * @param           $name
     * @param RouteBase $route
     */
    public function addRoute($name, RouteBase $route) {
        $this->_routes[$name] = $route;
    }

    /**
     * 获取当前有效的路由名
     * @return RouteBase
     */
    public function getCurrentRoute() : string {
        return $this->_current_route;
    }

    /**
     * @param $name
     * @return RouteBase
     */
    public function getRoute($name) {
        return $this->_routes[$name];
    }

    /**
     * @return RouteBase[]
     */
    public function getRoutes() {
        return $this->_routes;
    }
}