<?php
/**
 * @author 不二进制
 * @datetime 2020年06月10日00
 */


namespace phpcmx\common\orm\simple\sql;


use phpcmx\common\lib\ArrayTool;

/**
 * Class Select
 *
 * @package phpcmx\common\orm\simple\sql
 */
class Select extends SqlBase
{
    protected $_fields = '*';
    protected $_table = '';
    protected $_join = [];
    protected $_where = '1=1';
    protected $_groupBy = '';
    protected $_order = '';
    protected $_limit = '';

    /**
     * $query->select($f1, $f2, $f3);
     * $query->select([$f1, $f2, $f3]);
     * $query->select("$1, $2, $3")
     *
     * @param mixed ...$fields
     *
     * @return $this
     */
    public function select(...$fields) {
        if (is_array($fields[0])) {
            $fields = $fields[0];
        }
        $this->_fields = implode(', ', $fields);
        return $this;
    }

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
     * $query->join('left', 'tableName t', [['t.id', '=', 'tid']])
     *
     * @param $pre
     * @param $table
     * @param $on
     *
     * @return Select
     */
    public function join($pre, $table, $on) {
        $this->_join[] = "{$pre} JOIN {$table} ON " . Where::_and($on);
        return $this;
    }

    /**
     * @param array $where
     *
     * @return $this
     */
    public function where($where) {
        $this->_where = Where::_and($where);
        return $this;
    }

    public function addWhere($where) {
        $this->_where = Where::_and([$this->_where, $where]);
        return $this;
    }

    /**
     * $query->groupBy('id, name')
     * $query->groupBy(['id', 'name'])
     *
     * @param mixed $fields
     *
     * @return $this
     */
    public function groupBy($fields) {
        if (is_array($fields[0])) {
            $fields = implode(', ', $fields);
        }
        $this->_groupBy = $fields;
        return $this;
    }

    /**
     * $query->orderBy('id DESC, username ASC')
     * $query->orderBy(['id DESC', 'username ASC'])
     *
     * @param $fields string | array
     *
     * @return Select
     */
    public function orderBy($fields) {
        if (is_array($fields)) {
            $fields = implode(', ', $fields);
        }
        $this->_order = $fields;
        return $this;
    }

    /**
     * @param int|string $offset
     * @param int|null $length
     *
     * @return $this
     */
    public function limit($offset, int $length = null) {
        if (is_null($length)) {
            // limit $offset
            $limit = $offset;
        } else {
            // limit $offset, $length
            $limit = $offset . ', '. $length;
        }
        $this->_limit = $limit;
        return $this;
    }

    /**
     * @return string
    protected $_fields = '*';
    protected $_table = '';
    protected $_join = [];
    protected $_where = '1=1';
    protected $_groupBy = '';
    protected $_order = '';
    protected $_limit = '';
     */
    public function getQuery(): string {
        $query = "SELECT {$this->_fields} FROM {$this->_table}";
        if ($this->_join) {
            $query .= ' ' . implode(' ', $this->_join);
        }
        if ($this->_where) {
            $query .= " WHERE {$this->_where}";
        }
        if ($this->_groupBy) {
            $query .= " GROUP BY {$this->_groupBy}";
        }
        if ($this->_order) {
            $query .= " ORDER BY {$this->_order}";
        }
        if ($this->_limit) {
            $query .= " LIMIT {$this->_limit}";
        }
        $this->_query = $query;
        return parent::getQuery();
    }
}
