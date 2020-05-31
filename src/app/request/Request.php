<?php


namespace phpcmx\common\app\request;


use phpcmx\common\lib\ArrayTool;
use phpcmx\common\lib\CliTool;
use phpcmx\common\lib\HttpTool;

/**
 * Class Request
 *
 * @package phpcmx\common\app\request
 */
class Request
{
    /** @var string  */
    private $_method = '';
    /** @var string  */
    private $_actionName = '';
    /** @var string  */
    private $_controllerName = '';
    /** @var array  */
    private $_params = [];

    /** @var bool 是否已经完成调度 */
    private $_dispatched = false;

    /**
     * Request constructor.
     */
    public function __construct() {
        // 请求方法
        $this->_method = strtoupper(
            HttpTool::getMethod() ?:
                (CliTool::isCli() ? 'cli' : '')
        );
        // 构造params
        $body = [];
        if ($raw = $this->getRaw()) {
            $raw = json_decode($raw, true);
            $body = $raw ?: [];
        }
        $this->_params = array_merge($_GET, $_POST, $body);
    }

    /**
     * 从 get post cookie service env 中搜索用户参数
     * @param string $name
     * @param        $default
     * @return null
     */
    public function get(string $name, $default = null) {
        $return = $default;
        do {
            if ($result = $this->getParam($name)) {
                break;
            }
            if ($result = $this->getCookie($name)) {
                break;
            }
            if ($result = $this->getServer($name)) {
                break;
            }
            if ($result = $this->getEnv($name)) {
                break;
            }
        }while(false);

        if (is_null($result)) {
            $return = $default;
        }
        return $return;
    }

    /**
     * @return string
     */
    public function getActionName() {
        return $this->_actionName;
    }

    /**
     * @return string
     */
    public function getControllerName() {
        return $this->_controllerName;
    }

    /**
     * @param string      $name
     * @param string|null $default
     *
     * @return mixed|string
     */
    public function getEnv(string $name, string $default = null) {
        return ArrayTool::getItem($_ENV, $name, $default);
    }

    /**
     * @return mixed
     */
    public function getParams() {
        return $this->_params;
    }

    /**
     * get post body(json)
     * @param string $name
     * @param null   $default
     * @return bool|mixed
     */
    public function getParam(string $name, $default = null) {
        return ArrayTool::getItem($this->_params, $name, $default);
    }

    /**
     * @param string      $name
     * @param string|null $default
     *
     * @return mixed|string
     */
    public function getServer(string $name, string $default = null) {
        return ArrayTool::getItem($_SERVER, $name, $default);
    }

    /**
     *
     * @param string      $name
     * @param string|null $default
     *
     * @return mixed|string
     */
    public function getCookie(string $name, string $default = null) {
        return ArrayTool::getItem($_COOKIE, $name, $default);
    }

    /**
     * @return mixed
     */
    public function getFiles() {
        return $_FILES;
    }

    /**
     * 获取post请求
     * @param string      $name
     * @param string|null $default
     *
     * @return bool|mixed
     */
    public function getPost(string $name, $default = null) {
        return ArrayTool::getItem($_POST, $name, $default);
    }

    /**
     * 获取get请求
     * @param string $name
     * @param null   $default
     *
     * @return bool|mixed
     */
    public function Query(string $name, $default = null) {
        return ArrayTool::getItem($_GET, $name, $default);
    }

    /**
     * 获取body原始数据
     * @return false|string
     */
    public function getRaw() {
        return file_get_contents('php://input');
    }

    /**
     * 是否是xmlhttprequest(ajax)
     * @return bool
     */
    public function isXmlHttpRequest() {
        return HttpTool::isXmlHttpRequest();
    }

    /**
     * @return bool
     */
    public function isCli() {
        return $this->_method === 'CLI';
    }

    /**
     * @return bool
     */
    public function isGet() {
        return $this->_method === 'GET';
    }

    /**
     * @return bool
     */
    public function isPost() {
        return $this->_method === 'POST';
    }

    /**
     * @return bool
     */
    public function isHead() {
        return $this->_method === 'HEAD';
    }

    /**
     * @return bool
     */
    public function isOptions() {
        return $this->_method === 'OPTIONS';
    }

    /**
     * @return bool
     */
    public function isPut() {
        return $this->_method === 'PUT';
    }

    /**
     * @return bool
     */
    public function isDelete() {
        return $this->_method === 'DEL' || $this->_method === 'DELETE';
    }

    /**
     * @return bool
     */
    public function isDispatched() {
        return $this->_dispatched;
    }

    /**
     * 设置是否继续调度
     *
     * @param bool $flag
     */
    public function setDispatched(bool $flag = true) {
        $this->_dispatched = $flag;
    }

    /**
     * @param string $action
     */
    public function setActionName(string $action) {
        $this->_actionName = $action;
    }

    /**
     * @param string $controller
     */
    public function setControllerName(string $controller) {
        $this->_controllerName = $controller;
    }

    /**
     * 增加参数
     * @param string $name
     * @param null   $value
     */
    public function setParam(string $name, $value = null) {
        $this->_params[$name] = $value;
    }
}