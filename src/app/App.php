<?php
/**
 * Created by PhpStorm.
 * User: Bool Number
 * Datetime: 2020年05月22日23
 */


namespace phpcmx\common\app;


use phpcmx\common\app\dispatch\Dispatcher;

/**
 * Class App
 * 简易app入口
 *
 * @package phpcmx\common\app
 */
class App
{
    /** @var null | Bootstrap */
    private static $bootstrap = null;
    /** @var self */
    private static $_app = null;

    /**
     * @var Dispatcher
     */
    private $dispatch = null;

    private function __construct() { }

    /**
     * 注册bootstrap
     * @param Bootstrap $bootstrap
     * @return void
     */
    public static function registerBootstrap(Bootstrap $bootstrap) {
        self::$bootstrap = $bootstrap;
    }

    /**
     * 入口
     * @return App|null
     */
    public static function run() {
        self::$_app = new self();
        // 初始化调度
        $dispatch = self::$_app->initDispatch();
        // 启动bootstrap
        self::$_app->runBootstrap($dispatch);
        // request
        $request = $dispatch->getRequest();
        // 循环调度
        $dispatch->dispatch($request);
        return self::$_app;
    }

    /**
     * 初始化调度
     *
     * @return Dispatcher
     */
    private function initDispatch() {
        $this->dispatch = Dispatcher::getInstance();
        return $this->dispatch;
    }

    /**
     * 触发bootstrap
     *
     * @param Dispatcher $dispatch
     */
    private function runBootstrap(Dispatcher $dispatch) {
        if (!is_null(self::$bootstrap)) {
            self::$bootstrap->init($dispatch);
        }
    }

    /**
     * 获取app
     * @return App
     */
    public static function app() {
        return self::$_app;
    }
}