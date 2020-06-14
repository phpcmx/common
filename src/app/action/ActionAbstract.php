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
    protected $_controller;
    /** @var ResponseAbstract */
    private $_response;

    /**
     * ActionAbstract constructor.
     *
     * @param ControllerAbstract $controller
     */
    public function __construct(ControllerAbstract $controller) {
        $this->_controller = $controller;
        $this->_response = ResponseAbstract::getResponse();
        $this->init();
    }

    /**
     * 自定义初始化
     * @return mixed
     */
    protected function init() {
        return null;
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
            return $this->_response;
        }
        // bool 就直接返回 TODO 只有有模板，false不渲染模板
        if (is_bool($res)) {
            return $this->_response;
        }
        if (is_string($res)) {
            $res = [$res];
        }
        if (is_array($res)) {
            $response = $this->_response;
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
        $this->_controller->forward($forward, $params);
    }

    /**
     * @param string $url
     */
    protected function redirect(string $url) {
        $this->_controller->redirect($url);
    }

    /**
     * @return Request
     */
    protected function getRequest() {
        return Dispatcher::getInstance()->getRequest();
    }

    /**
     * @return ResponseAbstract|\phpcmx\common\app\response\ResponseCli|\phpcmx\common\app\response\ResponseHttp
     */
    protected function getResponse() {
        return $this->_response;
    }

    /**
     * @param $array
     *
     * @return ResponseAbstract|\phpcmx\common\app\response\ResponseCli|\phpcmx\common\app\response\ResponseHttp
     */
    protected function returnJson($array) {
        $response = $this->getResponse();
        $response->setBody(json_encode($array, 1));
        $response->setHeader('Content-type: application/json');
        return $response;
    }
}