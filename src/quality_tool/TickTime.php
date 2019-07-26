<?php
/**
 * Created by PhpStorm.
 * User: Bool Number
 * Date: 2018/5/4
 * Time: 下午5:46
 */

namespace phpcmx\common\quality_tool;

use phpcmx\common\trait_base\SimpleSingleton;

/**
 * 打点日志
 *
 * 在需要打点的地方
 * TickTime::getInstance()->dot('flag1');
 * 在程序最后，执行
 * TickTime::getInstance()->register();
 * [flag1:0,flag2:3,flag3:10]
 *
 * Class TickTime
 *
 * @package phpcmx\common\quality_tool
 */
class TickTime {
    use SimpleSingleton;

    private $logTime = [];

    /**
     * @param $flag
     * @return void
     */
    public function dot($flag)
    {
        // 当前时间 ms
        $time = intval(microtime(1)*1000);
        // 基准时间
        if (isset($this->logTime[0], $this->logTime[0]['time'])){
            $baseTime = $this->logTime[0]['time'];
        }else{
            $baseTime = $time;
        }
        // 相对于基准时间的相对时间
        $phaseTime = $time - $baseTime;

        $this->logTime[] = [
            'flag' => $flag,
            'time' => $time,
            'phase' => $phaseTime,
        ];
    }

    /**
     * 记录到日志中
     */
    public function register()
    {
        $timedMap = array_map(function($v){
            return "{$v['flag']}:{$v['phase']}";
        }, $this->logTime);
        $timed = implode(',', $timedMap);

        return $timed;
    }
}