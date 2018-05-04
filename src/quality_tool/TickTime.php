<?php
/**
 * Created by PhpStorm.
 * User: caomengxin
 * Date: 2018/5/4
 * Time: 下午5:46
 */

namespace phpcmx\common\quality_tool;

use phpcmx\common\trait_base\SimpleSingleton;

/**
 * 打点即时，方便记录日志和对时间的控制
 * Class TickTime
 *
 * @package phpcmx\common\quality_tool
 */
class TickTime {
    use SimpleSingleton;

    /**
     * @var float[]
     */
    private $_tick = [];

    /**
     * 添加记录点（tick必须为数字，并且不能重复设置，会覆盖）
     * @param int $tick
     * @return void
     */
    public function tick(int $tick) {
        $this->_tick[$tick] = microtime(true);
    }

    /**
     * 获取两个tick之间的时间
     * @param int $startTick
     * @param int $endTick
     * @return float
     */
    public function time(int $startTick, int $endTick) {
        $sta = $this->_tick[$startTick] ?: 0;
        $end = $this->_tick[$endTick] ?: 0;

        return $end - $sta;
    }

    /**
     * 获取所有的tick
     * @return float[]
     */
    public function getRecord() {
        return $this->_tick;
    }

    /**
     * 获取所有的耗时
     * @return float
     */
    public function getAllTime() {
        reset($this->_tick);
        $first = current($this->_tick);
        $last = end($this->_tick);
        return $last - $first;
    }
}