<?php


namespace phpcmx\common\app\action;


use phpcmx\common\app\controller\ControllerAbstract;
use phpcmx\common\app\dispatch\Dispatcher;
use phpcmx\common\app\exception\ActionReturnError;
use phpcmx\common\app\request\Request;
use phpcmx\common\app\response\ResponseAbstract;

abstract class ActionAbstract
{
    /** @var ControllerAbstract  */
    protected $controller;

    /**
     * ActionAbstract constructor.
     *
     * @param ControllerAbstract $controller
     */
    public function __construct(ControllerAbstract $controller) {
        $this->controller = $controller;
    }

    /**
     * 执行入口
     *
     * @return bool | string | array | ResponseAbstract 执行入口
     */
    abstract protected function execute();

    /**
     * 系统调用
     * @return ResponseAbstract
     */
    public function run() {
        $res = $this->execute();

        if (empty($res)) {
            return ResponseAbstract::getResponse();
        }
        // bool 就直接返回 TODO 只有有模板，false不渲染模板
        if (is_bool($res)) {
            return ResponseAbstract::getResponse();
        }
        if (is_string($res)) {
            $res = [$res];
        }
        if (is_array($res)) {
            $response = ResponseAbstract::getResponse();
            foreach ($res as $key => $item) {
                if (is_int($key)) {
                    $response->setBody($item);
                } else {
                    $response->setBody($item, $key);
                }
            }
            $res = $response;
        }
        if (!($res instanceof ResponseAbstract)) {
            throw new ActionReturnError('error return type. ' . gettype($res));
        }

        return $res;
    }

    /**
     * 调用其他action
     * $this->forward('controller/action', $params)
     * $this->forward(['controller', 'action'], $params)
     * $this->forward('action', $params)
     * $this->forward(['action'], $params)
     * @param $forward
     * @param $params
     */
    protected function forward($forward, $params = []) {
        $this->controller->forward($forward, $params);
    }

    /**
     * @param string $url
     */
    protected function redirect(string $url) {
        $this->controller->redirect($url);
    }

    /**
     * @return Request
     */
    protected function getRequest() {
        return Dispatcher::getInstance()->getRequest();
    }
}