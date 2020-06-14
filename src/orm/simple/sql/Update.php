<?php
/**
 * @author 不二进制
 * @datetime 2020年06月13日13
 */


namespace phpcmx\common\orm\simple\sql;


/**
 * Class Update
 *
 * @package phpcmx\common\orm\simple\sql
 */
class Update extends SqlBase
{
    protected $_table = '';
    protected $_set = '';
    protected $_where = '1=2';

    /**
     * @param string $table
     *
     * @return $this
     */
    public function update(string $table) {
        $this->_table = $table;
        return $this;
    }

    /**
     * @param $set
     *
     * @return $this
     */
    public function set($set) {
        $this->_set = implode(',', Where::parse($set));
        return $this;
    }

    /**
     * @param $where
     *
     * @return $this
     */
    public function where($where) {
        $this->_where = Where::_and($where);
        return $this;
    }

    /**
     * @return string
     */
    public function getQuery(): string {
        $this->_query =
            "UPDATE {$this->_table} SET {$this->_set} WHERE {$this->_where}";
        return parent::getQuery();
    }
}