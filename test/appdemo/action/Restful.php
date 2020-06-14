<?php


namespace app\action;


use app\service\resource\User;
use phpcmx\common\app\action\ActionAbstract;
use phpcmx\common\app\action\RestfulActionAbstract;
use phpcmx\common\app\response\ResponseAbstract;

class Restful extends ActionAbstract
{
    protected $_resource = [
        'user' => User::class,
    ];

    /**
     * 执行入口
     *
     * @return bool | string | array | ResponseAbstract 执行入口
     */
    function execute() {
        $request = $this->getRequest();
        $resource = $request->getParam('resource');
        if (!key_exists($resource, $this->_resource)) {
            $this->forward('common/404');
            return false;
        }

        $className = $this->_resource[$resource];
        /** @var RestfulActionAbstract $action */
        $action = new $className($this->_controller);
        if (!($action instanceof RestfulActionAbstract)) {
            throw new \LogicException('资源类必须继承 ' . RestfulActionAbstract::class . '。' . $className);
        }

        // 调用资源
        return $action->run();
    }
}