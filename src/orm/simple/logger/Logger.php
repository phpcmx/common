<?php
/**
 * @author 不二进制
 * @datetime 2020年06月13日20
 */


namespace phpcmx\common\orm\simple\logger;


use phpcmx\common\trait_base\SimpleSingleton;

/**
 * Class Logger
 *
 * @package phpcmx\common\orm\simple\logger
 */
class Logger
{
    use SimpleSingleton;

    /** @var int */
    private $_time = null;
    private $_log = [];

    /**
     * 开始计时
     */
    public function t_start() {
        $this->_time = intval(microtime(1) * 1000);
    }

    /**
     * 记录时间
     * @return int
     */
    public function t_stop() {
        return intval(microtime(1) * 1000) - $this->_time;
    }

    /**
     * 记录log
     * @param $info
     */
    public function saveLog($info) {
        $this->_log[] = $info;
    }

    /**
     * @return array
     */
    public function getLog() {
        return $this->_log;
    }
}