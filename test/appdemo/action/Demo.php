<?php


namespace app\action;


use phpcmx\common\app\action\ActionAbstract;
use phpcmx\common\app\dispatch\Dispatcher;
use phpcmx\common\app\response\ResponseAbstract;

class Demo extends ActionAbstract
{

    /**
     * 执行入口
     *
     * @return bool | string | array | ResponseAbstract 执行入口
     */
    function execute() {
        $request = Dispatcher::getInstance()->getRequest();
        echo "<pre>";var_dump($request->getParams());die;
    }
}