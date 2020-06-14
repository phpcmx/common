<?php


namespace phpcmx\common\orm\simple\sql;


use phpcmx\common\lib\ArrayTool;

class Where
{

    /**
     * 解析where条件
     *
     * @param $where
     *
     * @return array
     */
    public static function parse($where) {
        if (empty($where)) {
            return [];
        }

        if (is_string($where)) {
            return [$where];
        }

        $res = [];
        foreach ($where as $index => $item) {
            // 字符串
            if (!is_numeric($index)) {
                // field => value
                $res[] = self::parseField($index)
                    . '='
                    . self::parseValue($item);
            } else {
                if (is_array($item)) {
                    // [key, op, value]
                    if (count($item) < 3) {
                        throw new \LogicException('bad expression: ' . print_r($item, 1));
                    }
                    list($key, $op, $value) = $item;
                    $op = trim(strtoupper($op));

                    if ($op == 'IN' && is_array($value)) {
                        // in
                        $res[] = self::parseField($key)
                            . $op
                            . '(' . self::implodeList(',', $value) . ')';
                    } else if ($op == 'BETWEEN' && is_array($value)) {
                        // between
                        $res[] = self::parseField($key)
                            . $op
                            . '(' . self::implodeList(',', $value) . ')';
                    } else {
                        // 其他操作符
                        $res[] = self::parseField($key)
                            . $op
                            . self::parseValue($value);
                    }
                } else {
                    // 字符串
                    $res[] = $item;
                }
            }
        }

        return $res;
    }

    /**
     * Where::_and([
     *      "id=10",
     *      "name"=>"zhangsan",
     *      ["tid", "in", [1,2,3]],
     *      ["create_time", "=", "now()"]
     * ])
     *
     * @param array $where
     *
     * @return string
     */
    public static function _and($where) {
        $res = self::parse($where);
        return implode(' AND ', array_map(function($v) {
            return "($v)";
        }, $res));
    }

    /**
     * 同 _and
     *
     * @param array $where
     *
     * @return string
     */
    public static function _or(array $where) {
        $res = self::parse($where);
        return implode(' OR ', array_map(function($v) {
            return "($v)";
        }, $res));
    }


    /**
     * 合并字段
     *
     * @param $glue
     * @param $values
     *
     * @return string
     */
    public static function implodeList($glue, $values) {
        return implode($glue, array_map(function($value) {
            return self::parseValue($value);
        }, $values));
    }

    /**
     * 特殊字段，增加 ``
     *
     * @param $field
     *
     * @return mixed
     */
    public static function parseField($field) {
        return $field;
    }

    /**
     * 特殊value不加 ''
     * 数字和函数直接展示，字符串加 ''
     *
     * @param $value
     *
     * @return mixed
     */
    public static function parseValue($value) {
        $sqlKey = [
            'now()'
        ];
        if (!(
            // 不需要设置引号的条件
            is_numeric($sqlKey)
            || ($value[0] ?? '') === "'"
            || in_array(trim(strtolower($value)), $sqlKey)
        )) {
            $value = "'{$value}'";
        }
        return $value;
    }
}