<?php
/**
 * Created by PhpStorm.
 * User: caomengxin
 * Date: 2018/5/4
 * Time: 下午3:50
 */

spl_autoload_register(function($className){
    $rootPath = realpath(__DIR__."/../../src");
    $classPath = strtr($className, [
        'phpcmx\common' => DIRECTORY_SEPARATOR,
        "\\" => DIRECTORY_SEPARATOR,
    ]);
    include_once $rootPath.$classPath.".php";
}, true);