<?php


namespace phpcmx\common\app\dispatch;


use phpcmx\common\app\App;
use phpcmx\common\app\controller\ControllerAbstract;
use phpcmx\common\app\exception\ControllerNotFound;
use phpcmx\common\app\request\Request;
use phpcmx\common\app\router\Router;
use phpcmx\common\trait_base\SimpleSingleton;

class Dispatcher
{
    use SimpleSingleton;

    /** @var Request  */
    private $_request;
    /** @var Router */
    private $_router;
    /** @var bool 自动渲染 */
    private $_autoRender = true;

    private function __construct() {
        $this->_request = new Request();
        $this->_router = new Router();
    }

    /**
     * @param bool $switch
     *
     * @return Dispatcher
     */
    public function autoRender(bool $switch) {
        $this->_autoRender = $switch;
        return $this;
    }

    /**
     * @return Dispatcher
     */
    private function disableView() {
        return $this->autoRender(false);
    }

    /**
     * @return Dispatcher
     */
    private function enableView() {
        return $this->autoRender(true);
    }

    /**
     * @param bool $switch
     * @return Dispatcher
     */
    public function catchException(bool $switch) {
        // TODO
        return $this;
    }

    /**
     * 循环dispatch
     *
     * @param Request $request
     *
     * @return void
     */
    public function dispatch(Request $request) {
        // 解析路由
        $router = $this->getRouter();
        $router->route($request);
        // 循环分发
        $response = [];
        while (!$request->isDispatched()) {
            $controllerClassName = '\\app\\controllers\\' . $request->getControllerName();
            if (!class_exists($controllerClassName)) {
                throw new ControllerNotFound('controller not found. ' . $controllerClassName);
            }
            /** @var ControllerAbstract $controller */
            $controller = new $controllerClassName();
            $response[] = $controller->run($request->getActionName());
        }

        // 响应
        foreach ($response as $index => $item) {
            $item->response();
        }
    }

    /**
     * @return App
     */
    public function getApplication() {
        return App::app();
    }

    /**
     * @return Request
     */
    public function getRequest() {
        return $this->_request;
    }

    /**
     * 获取router
     * @return Router
     */
    public function getRouter() {
        return $this->_router;
    }

}