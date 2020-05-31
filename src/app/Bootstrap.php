<?php


namespace phpcmx\common\app;


use phpcmx\common\app\dispatch\Dispatcher;

abstract class Bootstrap
{
    /**
     * 入口
     *
     * @param Dispatcher $dispatch
     *
     * @return mixed
     */
    abstract function init(Dispatcher $dispatch);
}