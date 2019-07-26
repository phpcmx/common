<?php
/**
 * Created by PhpStorm.
 * User: Bool Number
 * Date: 2018/5/4
 * Time: 下午3:40
 */

namespace phpcmx\common\trait_base;

/**
 * 简易单例
 * Trait SimpleSingleton
 * @package phpcmx\common\trait_base
 */
trait SimpleSingleton {
    /**
     * 给构造函数添加init接口
     * SimpleSingleton constructor.
     */
    private function __construct() {
        if (method_exists($this, 'init')){
            $this->init();
        }
    }

    /**
     * 获取实例的方法
     * @return static
     */
    public static function getInstance() {
        static $instance = [];
        if (!key_exists(static::class, $instance)){
            $instance[static::class] = new static();
        }
        return $instance[static::class];
    }

    /**
     * @return mixed
     */
    public function init(){
        return null;
    }
}