<?php

namespace phpcmx\common\app\router;


use phpcmx\common\app\request\Request;

/**
 * route 基类
 * Class RouteBase
 *
 * @package phpcmx\common\app\router
 */
abstract class RouteBase
{

    /**
     * 返回是否匹配上了
     *
     * @param Request $request
     * @return bool
     */
    abstract function route(Request $request) : bool;

    /**
     * 将指定路由规则组合成一个url
     * @param array $info
     * @param array $query
     *
     * @return string
     */
    abstract function assemble(array $info, array $query = []) : string;
}