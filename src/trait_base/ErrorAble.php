<?php
/**
 * Created by PhpStorm.
 * User: Bool Number
 * Date: 2020年05月22日23
 */



namespace phpcmx\common\trait_base;


/**
 * Class ErrorAble
 * 错误功能支持
 * @package phpcmx\common\trait_base
 */
trait ErrorAble
{
    private $_error_able_list = [];

    /**
     * 记录错误
     *
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function addError($key, $value) {
        $this->_error_able_list[$key] = $value;
        return $this;
    }

    /**
     * 是否有错误
     * $ins->hasError() // 判断$ins是否有错误
     * $ins->hasError($attr) // 判断$ins的$attr是否有错误
     *
     * @param mixed $attribute 错误的key
     *
     * @return bool
     */
    public function hasError($attribute = null) {
        if (is_null($attribute)) {
            return !!$this->_error_able_list;
        } else {
            // 有key就认为是有错误，不在乎内容，包括null
            return key_exists($attribute, $this->_error_able_list);
        }
    }

    /**
     * 获取错误信息
     * $ins->hasError($attr) // 判断$ins的$attr是否有错误
     *
     * @param mixed $attribute
     *
     * @return mixed
     */
    public function get_error($attribute) {
        return $this->_error_able_list[$attribute] ?? null;
    }

    /**
     * 获取所有的错误信息
     * @return array
     */
    public function get_errors() {
        return $this->_error_able_list;
    }

    /**
     * 返回最后一个错误
     * @return array
     */
    public function lastError() {
        end($this->_error_able_list);
        return [key($this->_error_able_list), current($this->_error_able_list)];
    }
}