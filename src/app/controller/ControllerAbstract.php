<?php
/**
 * @author 不二进制
 * @datetime 2020年05月30日21
 */

namespace phpcmx\common\app\controller;


use phpcmx\common\app\action\ActionAbstract;
use phpcmx\common\app\dispatch\Dispatcher;
use phpcmx\common\app\exception\ActionNotFound;
use phpcmx\common\app\response\ResponseAbstract;

/**
 * 控制器
 * Class ControllerAbstract
 *
 * @package phpcmx\common\app\controller
 */
abstract class ControllerAbstract
{
    /**
     * @var array
     *           key => actionName
     */
    public $_action = [];

    /** @var string | null */
    private $_redirect = null;
    /** @var array */
    private $_forward = [
        'effect' => false,
        'forward' => ['', ''],
        'params' => [],
    ];


    public function __construct() {
    }

    /**
     * 执行action
     *
     * @param string $actionName
     *
     * @return ResponseAbstract
     */
    public function run(string $actionName) {
        // 调用action类
        $actionClassName = $this->_action[$actionName] ?? $actionName;
        if (!class_exists($actionClassName)) {
            throw new ActionNotFound('action not found. ' . $actionClassName);
        }
        /** @var ActionAbstract $action */
        $action = new $actionClassName($this);
        $response = $action->run();

        // 跳转
        if (!is_null($this->_redirect)) {
            $response = ResponseAbstract::getResponse();
            $response->setRedirect($this->_redirect);
            return $response;
        }

        $request = Dispatcher::getInstance()->getRequest();
        // 调用其他action
        if ($this->_forward['effect']) {
            $request->setControllerName($this->_forward['forward'][0]);
            $request->setActionName($this->_forward['forward'][1]);
            foreach ($this->_forward['params'] as $name => $value) {
                $request->setParam($name, $value);
            }
        } else {
            // 不再循环分发
            Dispatcher::getInstance()->getRequest()->setDispatched();
        }

        return $response;
    }

    /**
     * @param string $url
     */
    public function redirect(string $url) {
        $this->_redirect = $url;
    }

    /**
     * 调用其他action
     *
     * @param       $forward
     * @param array $params
     */
    public function forward($forward, $params = []) {
        if (is_string($forward)) {
            // controller/action
            $forward = explode('/', $forward);
        }

        if (!is_array($forward)) {
            throw new \LogicException('forward error type :' . gettype($forward));
        }

        if (empty($forward)) {
            throw new \LogicException('forward empty Error');
        } else if (isset($forward[1])) {
            // [controller, action]
            list($controller, $action) = $forward;
        } else {// if (isset($forward[0])) {
            // [action]
            $controller = Dispatcher::getInstance()->getRequest()->getControllerName();
            $action = $forward[0];
        }

        $this->_forward = [
            'effect' => true,
            'forward' => [$controller, $action],
            'params' => $params,
        ];
    }
}