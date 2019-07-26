<?php
/**
 * Created by PhpStorm.
 * User: Bool Number
 * Date: 2018/5/4
 * Time: 下午5:08
 */

namespace phpcmx\common;

/**
 * 客户端相关函数
 * Class ClientUnit
 *
 * @package phpcmx\common
 */
final class ClientUnit {
    private function __construct() {}

    /**
     * 获取客户端ip
     * @return string
     */
    public static function getIp() {
        if(getenv('HTTP_CLIENT_IP')) {
            $clientIp = getenv('HTTP_CLIENT_IP');
        } elseif(getenv('HTTP_X_FORWARDED_FOR')) {
            $clientIp = getenv('HTTP_X_FORWARDED_FOR');
        } elseif(getenv('REMOTE_ADDR')) {
            $clientIp = getenv('REMOTE_ADDR');
        } else {
            $clientIp = $HTTP_SERVER_VARS['REMOTE_ADDR'];
        }
        return $clientIp;
    }
}