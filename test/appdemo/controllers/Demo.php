<?php


namespace app\controllers;

use phpcmx\common\app\controller\ControllerAbstract;

/**
 * Class Demo
 */
class Demo extends ControllerAbstract
{
    public $_action = [
        'demo' => \app\action\Demo::class,
    ];
}