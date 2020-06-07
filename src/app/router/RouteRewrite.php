<?php
/**
 * @auth 不二进制
 * @datetime 2020年05月30日19
 */


namespace phpcmx\common\app\router;


use phpcmx\common\app\exception\RouteAssembleQuery;
use phpcmx\common\app\request\Request;
use phpcmx\common\lib\HttpTool;

/**
 * Class RouteRewrite
 *
 * @package phpcmx\common\app\router
 */
class RouteRewrite extends RouteBase
{
    private $rewrite_rule = '';
    private $conf = [];
    private $controller = '';
    private $action = '';

    /**
     * RouteRewrite constructor.
     *
     * @param $rewrite_rule
     * @param $conf
     */
    public function __construct($rewrite_rule, $conf) {
        $this->rewrite_rule = trim($rewrite_rule, '/');
        $this->conf = $conf;

        $start_pos = strpos($this->rewrite_rule, '*');
        if ($start_pos !== false) {
            if ($start_pos !== strlen($this->rewrite_rule) - 1) {
                throw new \LogicException('通配符*只允许出现一次，且必须是匹配规则最后');
            }
            if (!in_array(substr($this->rewrite_rule, -2), ['/*', '*'])) {
                throw new \LogicException('通配符配置错误，结尾必须为 /* ');
            }
        }
        $this->controller = $conf['controller'] ?? null;
        $this->action = $conf['action'] ?? null;
        if (empty($this->controller)) {
            throw new \LogicException('controller 缺失');
        }
        if (empty($this->action)) {
            throw new \LogicException('action 缺失');
        }
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
        $rule_info = explode('/', $this->rewrite_rule);

        $params = [];
        do {
            $rule = array_shift($rule_info);
            $part = array_shift($path_info);
            if ($rule == '*') {
                // 末尾匹配所有参数
                $key = $part;
                while($key) {
                    $value = array_shift($path_info);
                    $params[$key] = $value;
                    $key = array_shift($path_info);
                }
            }else if (empty($part)) {
                // url 短与 rule
                return false;
            } else if(substr($rule, 0, 1) == ':') {
                // :var
                $params[substr($rule, 1)] = $part;
            } else {
                // 强匹配
                if ($rule !== $part) {
                    // url 与 rule 不匹配
                    return false;
                }
            }
        } while($rule_info || $path_info);

        // 匹配上了，则设置
        $request->setControllerName($this->controller);
        $request->setActionName($this->action);

        // 设置params
        foreach ($params as $key => $value) {
            $request->setParam($key, $value);
        }

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
        $url = $this->rewrite_rule;
        $res = preg_match_all('/:(\w+)/', $url, $match);
        if ($res && isset($match[1])) {
            $needParams = $match[1];
            $replaceArr = [];
            foreach ($needParams as $needParam) {
                if (!isset($query[$needParam])) {
                    throw new RouteAssembleQuery("缺少参数 {$needParam}");
                }
                $replaceArr[":{$needParam}"] = $query[$needParam];
                unset($query[$needParam]);
            }
            // 替换url中的参数
            $url = strtr($url, $replaceArr);
        }
        // 拼接参数
        if (substr($url, -2) === '/*') {
            $url = substr($url, 0, -1) . implode('/', $query);
        } else {
            $url .= $query ? '?' . http_build_query($query) : '';
        }

        return $url;
    }
}