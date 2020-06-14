<?php


namespace app;


use phpcmx\common\app\dispatch\Dispatcher;
use phpcmx\common\app\router\RouteRewrite;
use app\lib\Conf;

class Bootstrap extends \phpcmx\common\app\Bootstrap
{

    /**
     * 入口
     *
     * @param Dispatcher $dispatch
     *
     * @return void
     */
    function init(Dispatcher $dispatch) {
        $this->init_const();
        $this->init_router($dispatch);
        $this->init_conf();
    }

    /**
     * 注册常量
     */
    public function init_const() {
        define('ROOT_PATH', dirname(__FILE__));
        define('APP_PATH', ROOT_PATH);
    }

    /**
     * router
     *
     * @param Dispatcher $dispatch
     */
    public function init_router(Dispatcher $dispatch) {
        $router = $dispatch->getRouter();

        // 404
        $router->addRoute('404',
            new RouteRewrite('/*', [
                'controller' => 'Common',
                'action' => '404',
            ])
        );
        // restful 建议用action来分发
        $router->addRoute('resource',
            new RouteRewrite('resource/:resource/*', [
                'controller' => 'Common',
                'action' => 'restful'
            ])
        );
        // rewrite
        $router->addRoute('test',
            new RouteRewrite( 'demo/:id/*', [
                'controller' => 'Demo',
                'action' => 'demo',
            ])
        );
    }

    /**
     * init conf
     */
    public function init_conf() {
        Conf::getInstance()->initConf(APP_PATH.'/conf');
    }
}