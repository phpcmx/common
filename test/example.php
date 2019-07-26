<?php
/**
 * Created by PhpStorm.
 * User: Bool Number
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

echo PHP_EOL;

TickTime::getInstance()->dot('start');
//sleep(1);
TickTime::getInstance()->dot('sleep1');
//sleep(2);
TickTime::getInstance()->dot('sleep2');

var_dump(TickTime::getInstance()->register());