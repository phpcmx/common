<?php
/**
 * @author 不二进制
 * @datetime 2020年06月13日13
 */


namespace phpcmx\common\orm\simple\sql;


/**
 * Class Insert
 *
 * @package phpcmx\common\orm\simple\sql
 */
class Insert extends SqlBase
{
    protected $_table = '';
    protected $_fields = '';
    protected $_values = '';
    protected $_onDup = '';

    /**
     * @param string $table
     *
     * @return $this
     */
    public function insertInto(string $table) {
        $this->_table = $table;
        return $this;
    }


    /**
     * $query->value(['id' => 3, 'name' => 'foo'])
     * $query->value([3, 'foo]) // sql中不会带field字段，value必须同表一一对应，不推荐
     *
     * @param array $info
     *
     * @return Insert
     */
    public function values(array $info) {
        $fields = array_keys($info);
        $values = array_values($info);

        $i = 0;
        foreach ($fields as $_k) {
            if ($i !== $_k) {
                // 非数字递增数组
                $this->_fields = '(' . implode(', ', $fields) . ')';
                break;
            }
            $i ++;
        }

        $this->_values = Where::implodeList(', ', $values);
        return $this;
    }

    /**
     * 当主键冲突时
     * @param $set
     */
    public function onDuplicate($set) {
        $this->_onDup = implode(',', Where::parse($set));
    }


    /**
     * @return string
     */
    public function getQuery(): string {
        $query =
            "INSERT INTO {$this->_table} "
            . "{$this->_fields} VALUES ({$this->_values})";
        if ($this->_onDup) {
            $query .= " ON DUPLICATE KEY UPDATE {$this->_onDup}";
        }
        $this->_query = $query;
        return parent::getQuery();
    }
}