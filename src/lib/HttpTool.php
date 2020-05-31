<?php
/**
 * @author 不二进制
 * @datetime 2020年05月30日17
 */


namespace phpcmx\common\lib;


use phpcmx\common\trait_base\StaticClass;

/**
 * Class HttpTool
 *
 * @package phpcmx\common\lib
 */
class HttpTool
{
    use StaticClass;

    /**
     * 后去method
     * @return bool|mixed
     */
    public static function getMethod() {
        return ArrayTool::getItem($_SERVER, 'REQUEST_METHOD');
    }

    /**
     * 是否是 xmlhttprequest 请求（ajax）
     * @return bool
     */
    public static function isXmlHttpRequest() {
        return ArrayTool::getItem($_SERVER, 'HTTP_X_REQUESTED_WITH')
            === 'xmlhttprequest';
    }
}