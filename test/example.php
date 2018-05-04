<?php
/**
 * Created by PhpStorm.
 * User: caomengxin
 * Date: 2018/5/4
 * Time: 下午3:49
 */

use phpcmx\common\quality_tool\TickTime;

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


TickTime::getInstance()->tick(1);
sleep(1);
TickTime::getInstance()->tick(2);
sleep(2);
TickTime::getInstance()->tick(3);

var_dump(TickTime::getInstance()->time(2, 3));
var_dump(TickTime::getInstance()->getAllTime());