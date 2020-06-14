<?php
/**
 * @author 不二进制
 * @datetime 2020年06月13日20
 */


namespace app\lib;


use phpcmx\common\trait_base\SimpleSingleton;

/**
 * Class Conf
 *
 * @package phpcmx\common\lib
 */
class Conf
{
    use SimpleSingleton;

    private $_conf = [];

    /**
     * @param $confPath
     */
    public function initConf($confPath) {
        $files = scandir($confPath);
        $files = array_filter($files, function ($f) {
            $spl = explode('.', $f);
            return strtolower($spl[count($spl) - 1]) == 'php';
        });

        foreach ($files as $file) {
            $this->_conf[substr($file, 0, -4)]
                = require_once $confPath.'/'.$file;
        }
    }

    /**
     * @param $name
     *
     * @return array|mixed
     */
    public function getConf($name) {
        return $this->_conf[$name];
    }
}