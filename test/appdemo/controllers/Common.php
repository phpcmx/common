<?php


namespace app\controllers;


use app\action\common\Page404;
use app\action\Restful;
use phpcmx\common\app\controller\ControllerAbstract;

class Common extends ControllerAbstract
{
    public $_action = [
        '404' => Page404::class,
        'restful' => Restful::class,
    ];
}