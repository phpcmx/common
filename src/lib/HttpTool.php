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

    /**
     * get path_info
     */
    public static function getPathInfo() {
        $path = $_SERVER['PATH_INFO'] ?? null;
        if (1 || is_null($path)) {
            $info = parse_url($_SERVER['REQUEST_URI']);
            $path = $info['path'];
        }
        return $path;
    }
}