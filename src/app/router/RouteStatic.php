<?php


namespace phpcmx\common\app\router;


use phpcmx\common\app\request\Request;
use phpcmx\common\lib\HttpTool;

/**
 * 静态路由，默认路由，
 * /controller/action/param1/value1/param2/value2
 * Class RouteStatic
 *
 * @package phpcmx\common\app\router
 */
class RouteStatic extends RouteBase
{
    private $defaultControllerName = 'Index';
    private $defaultActionName = 'index';

    /**
     * RouteStatic constructor.
     *
     * @param string $defaultActionName 默认的action name
     * @param string $defaultControllerName 默认的controller name
     */
    public function __construct($defaultActionName='index', $defaultControllerName='Index') {
        $this->defaultActionName = $defaultActionName;
        $this->defaultControllerName = $defaultControllerName;
    }

    /**
     * 返回是否匹配上了
     *
     * @param Request $request
     *
     * @return bool
     */
    function route(Request $request): bool {
        $path = HttpTool::getPathInfo() ?: '/';
        $path = trim($path, '/');
        $path_info = explode('/', $path);

        $controller = $path_info[0] ?: $this->defaultControllerName;
        $action = $path_info[1] ?? $this->defaultActionName;
        $request->setControllerName($controller);
        $request->setActionName($action);

        $path_info_length = count($path_info);
        for ($i = 2; $i < $path_info_length; $i += 2) {
            $request->setParam(
                $path_info[$i],
                $path_info[$i + 1] ?? null
            );
        }
        // 一定命中
        return true;
    }

    /**
     * 将指定路由规则组合成一个url
     *
     * @param array $info
     * @param array $query
     *
     * @return string
     */
    function assemble(array $info, array $query = []): string {
        return array_reduce($info, function($r, $v) {
            return $r . '/'. $v;
        })
        . $query
            ? '?' . http_build_query($query)
            : '';
    }
}