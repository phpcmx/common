<?php


namespace phpcmx\common\lib;


use phpcmx\common\trait_base\StaticClass;

class ArrayTool
{
    use StaticClass;

    /**
     * @param array  $arr
     * @param string $key
     * @param bool   $default
     *
     * @return bool|mixed
     */
    public static function getItem(array $arr, string $key, $default = null) {
        if (key_exists($key, $arr)) {
            return $arr[$key];
        }
        return $default;
    }
}