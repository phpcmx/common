<?php
/**
 * Created by PhpStorm.
 * User: caomengxin
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
     * @param array ...$param
     */
    private function __construct(...$param) {
        if (method_exists($this, 'init')){
            $this->init($param);
        }
    }

    /**
     * 获取实例的方法
     * @return static
     */
    public static function getInstance() {
        static $_obj = null;
        if (is_null($_obj)){
            $_obj = new self();
        }
        return $_obj;
    }

    public function init(...$param){}
}