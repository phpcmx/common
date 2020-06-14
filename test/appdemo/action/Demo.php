<?php


namespace app\action;


use app\service\model\dao\TestModel;
use phpcmx\common\app\action\ActionAbstract;
use phpcmx\common\app\dispatch\Dispatcher;
use phpcmx\common\app\response\ResponseAbstract;
use phpcmx\common\orm\simple\logger\Logger;
use phpcmx\common\orm\simple\sql\Select;
use phpcmx\common\orm\simple\sql\Where;

class Demo extends ActionAbstract
{

    /**
     * 执行入口
     *
     * @return bool | string | array | ResponseAbstract 执行入口
     */
    function execute() {
        $request = Dispatcher::getInstance()->getRequest();
        $id = $request->getParam('id');

        return $id;
    }
}