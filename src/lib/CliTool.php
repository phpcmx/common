<?php
/**
 * @author 不二进制
 * @datetime 2020年05月30日17
 */


namespace phpcmx\common\lib;


use phpcmx\common\trait_base\StaticClass;

/**
 * Class CliTool
 *
 * @package phpcmx\common\lib
 */
class CliTool
{
    use StaticClass;

    /**
     * 是否是cli请求
     * @return false|int
     */
    public static function isCli() {
        return preg_match("/cli/i", php_sapi_name());
    }
}