<?php
/**
 * @author 不二进制
 * @datetime 2020年06月13日22
 */


namespace app\service\model\dao;


use app\lib\Conf;
use phpcmx\common\orm\simple\ModelBase;

/**
 * Class TestModel
 *
 * @package app\service\model\dao
 */
class TestModel extends ModelBase
{

    /**
     * 获取连接数据
     * [
     * host => '',
     * port => '',
     * dbname => '',
     * username => '',
     * passwd => '',
     * options => [],
     * ]
     *
     * @return array
     */
    function getConnConf(): array {
        return Conf::getInstance()->getConf('db')['test'];
    }

    /**
     * 获取table名
     *
     * @return string
     */
    function getTableName(): string {
        return 'test';
    }

    public function getAll() {
        $fields = ['*'];
        $conditions = [
            '1 = 1'
        ];

        return $this->select($fields, $conditions);
    }
}