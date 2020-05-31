<?php


namespace app\action\common;


use phpcmx\common\app\action\ActionAbstract;
use phpcmx\common\app\response\ResponseAbstract;

class Page404 extends ActionAbstract
{

    /**
     * 执行入口
     *
     * @return bool | string | array | ResponseAbstract 执行入口
     */
    function execute() {
        return [
            '<h1> :( </h1>',
            '<p>404 not found</p>'
        ];
    }
}