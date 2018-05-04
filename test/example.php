<?php
/**
 * Created by PhpStorm.
 * User: caomengxin
 * Date: 2018/5/4
 * Time: 下午3:49
 */

include_once "./lib/autoload.php";

class A {
    use \phpcmx\common\trait_base\SimpleSingleton;

    private $a;
    public function show() {
        echo $this->a;
    }

    public function init() {
        $this->a = 'yes';
    }
}

A::getInstance()->show();
