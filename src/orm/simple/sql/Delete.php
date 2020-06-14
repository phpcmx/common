<?php
/**
 * @author 不二进制
 * @datetime 2020年06月13日14
 */


namespace phpcmx\common\orm\simple\sql;


/**
 * Class Delete
 *
 * @package phpcmx\common\orm\simple\sql
 */
class Delete extends SqlBase
{
    protected $_table = '';
    protected $_where = '1=2';

    /**
     * @param $table
     *
     * @return $this
     */
    public function from($table) {
        $this->_table = $table;
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
        $this->_query = "DELETE FROM {$this->_table} WHERE {$this->_where}";
        return parent::getQuery();
    }
}