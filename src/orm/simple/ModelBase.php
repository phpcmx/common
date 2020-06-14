<?php


namespace phpcmx\common\orm\simple;


use PDOStatement;
use phpcmx\common\orm\simple\connections\ConnectionBase;
use phpcmx\common\orm\simple\connections\Mysql;
use phpcmx\common\orm\simple\exception\SimpleOrmError;
use phpcmx\common\orm\simple\logger\Logger;
use phpcmx\common\orm\simple\sql\Delete;
use phpcmx\common\orm\simple\sql\Insert;
use phpcmx\common\orm\simple\sql\Select;
use phpcmx\common\orm\simple\sql\Update;

abstract class ModelBase
{
    /** @var ConnectionBase */
    protected $_conn;

    /**
     * ModelBase constructor.
     */
    public function __construct() {
        $conf = $this->getConnConf();

        $host     = $conf['host'];
        $port     = $conf['port'];
        $dbname   = $conf['dbname'];
        $username = $conf['username'];
        $passwd   = $conf['passwd'];
        $options  = $conf['options'];

        $this->_conn = new Mysql($host, $port, $dbname, $username, $passwd, $options);
    }

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
    abstract function getConnConf() : array;

    /**
     * 获取table名
     * @return string
     */
    abstract function getTableName() : string;

    /**
     * @return ConnectionBase
     */
    function getConn() : ConnectionBase {
        return $this->_conn;
    }

    /**
     * 开始事务
     */
    public function beginTransaction() {
        $this->getConn()->beginTransaction();
    }

    /**
     * 是否在一个事务里
     * @return bool
     */
    public function inTransaction() {
        return $this->getConn()->inTransaction();
    }

    /**
     * 提交事务
     */
    public function commit() {
        $this->getConn()->commit();;
    }

    /**
     * 回滚事务
     */
    public function rollBack() {
        $this->getConn()->rollBack();
    }

    /**
     * sql执行开始
     */
    protected function sqlStart() {
        Logger::getInstance()->t_start();
    }

    /**
     * sql执行结束
     * @param               $sql
     * @param PDOStatement|false $stmt
     * @throws SimpleOrmError
     */
    protected function sqlEnd($sql, $stmt) {
        $errorCode = $stmt ? $stmt->errorCode() : $this->getConn()->getPdo()->errorCode();
        $errorInfo = $stmt ? $stmt->errorInfo() : $this->getConn()->getPdo()->errorInfo();
        $info = [
            'sql' => $sql,
            'cost' => Logger::getInstance()->t_stop(),
            'errorCode' => $errorCode,
            'errorInfo' => $errorInfo,
        ];
        if ($errorCode !== '00000') {
            throw new SimpleOrmError(
                is_array($errorInfo)
                    ? implode(':', $errorInfo)
                    : $errorInfo
            );
        }
        Logger::getInstance()->saveLog($info);
    }

    /**
     * 查询 count
     * $model->count([
     *  'num = 4',
     *  'class' => 'foo',
     *  ['class', '!=', 'foo'],
     *  Where::_or([
     *      'class' => 'a',
     *      ['id', 'in', [1,2,3,4]]
     *  ]),
     * ])
     *
     * @param       $conditions
     *
     * @param array $joins
     *
     * @return mixed
     * @throws SimpleOrmError
     */
    public function count($conditions, $joins=[]) {
        $this->sqlStart();
        $query = (new Select())
            ->select(['count(*)'])
            ->from($this->getTableName())
            ->where($conditions);
        if ($joins) {
            foreach ($joins as $join) {
                list($pre, $table, $on) = $join;
                $query->join($pre, $table, $on);
            }
        }
        $sql = $query->limit(1)
            ->getQuery();
        $stmt = $this->getConn()->query($sql);
        $return = $stmt->fetchColumn();
        $this->sqlEnd($sql, $stmt);
        return $return;
    }

    /**
     * select的别名函数
     *
     * @param       $fields
     * @param       $conditions
     * @param array $joins
     * @param null  $group
     * @param null  $order
     * @param null  $limit
     *
     * @return array
     * @throws SimpleOrmError
     */
    public function filter($fields, $conditions, $joins=[], $group=null, $order=null, $limit=null) {
        return $this->select($fields, $conditions, $joins, $group, $order, $limit);
    }

    /**
     * 查询数据
     * $fields = ['id', 'name', 'sex'];
     * $conditions = [['create_time', '>', '2020-06-13 00:00:00']];
     * $joins = [
     *  ['left', 'table t', 't.id = tid'],
     * ];
     * $group = ['id', 'create_time'];
     * // $group = 'id, create_time'
     * $order = ['id desc', 'create_time desc'];
     * // $order = 'id desc, create_time desc'
     * $limit = '10, 100';
     * $model->filter($fields, $conditions, $group, $order, $limit);
     *
     * @param string|array $fields
     * @param string|array $conditions
     * @param array        $joins
     * @param string|array $group
     * @param string|array $order
     * @param string       $limit
     *
     * @return array
     * @throws SimpleOrmError
     */
    public function select($fields, $conditions, $joins=[], $group=null, $order=null, $limit=null) {
        $this->sqlStart();
        $query = (new Select())
            ->select($fields)
            ->from($this->getTableName())
            ->where($conditions);
        if ($joins) {
            foreach ($joins as $join) {
                list($pre, $table, $on) = $join;
                $query->join($pre, $table, $on);
            }
        }
        if ($group) {
            $query->groupBy($group);
        }
        if ($order) {
            $query->orderBy($order);
        }
        if ($limit) {
            $query->limit($limit);
        }

        $sql = $query->getQuery();
        $stmt = $this->getConn()->query($sql);
        $return = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $this->sqlEnd($sql, $stmt);
        return $return;
    }

    /**
     * 获取一行数据
     *
     * @param string|array $fields
     * @param string|array $conditions
     * @param array        $joins
     * @param string|array $group
     * @param string       $order
     *
     * @return array
     * @throws SimpleOrmError
     */
    public function get($fields, $conditions, $joins=[], $group=null, $order=null) {
        $res = $this->select($fields, $conditions, $joins, $group, $order, 1);
        if ($res) {
            $res = $res[0];
        }
        return $res;
    }

    /**
     * $row = [
     *  'name' => 'foo',
     *  'sex' => 1,
     * ];
     * $conditions = [
     *  'id' => '10'
     * ];
     * $model->update($row, $conditions);
     *
     * @param array $row
     * @param array $conditions
     *
     * @return int
     * @throws SimpleOrmError
     */
    public function update(array $row, array $conditions) {
        $this->sqlStart();
        $sql = (new Update())
            ->update($this->getTableName())
            ->set($row)
            ->where($conditions)
            ->getQuery();
        $stmt = $this->getConn()->query($sql);
        $return = $stmt ? $stmt->rowCount() : false;
        $this->sqlEnd($sql, $stmt);
        return $return;
    }

    /**
     * @param $conditions
     *
     * @return int
     * @throws SimpleOrmError
     */
    public function delete($conditions) {
        $this->sqlStart();
        $sql = (new Delete())
            ->from($this->getTableName())
            ->where($conditions)
            ->getQuery();
        $stmt = $this->getConn()->query($sql);
        $return = $stmt ? $stmt->rowCount() : false;
        $this->sqlEnd($sql, $stmt);
        return $return;
    }


    /**
     * $row = [
     *  'id' => 3,
     *  'name' => 'foo',
     *  'sex' => 1,
     * ];
     * $onDup = [
     *  'name' => 'foo',
     *  'sex' => 1,
     * ];
     * $model->insert($row, $onDup)
     *
     * @param array $row
     * @param array $onDup
     *
     * @return int
     * @throws SimpleOrmError
     */
    public function insert($row, $onDup=null) {
        $this->sqlStart();
        $query = (new Insert())
            ->insertInto($this->getTableName())
            ->values($row);
        if ($onDup) {
            $query->onDuplicate($onDup);
        }
        $sql = $query->getQuery();
        $stmt = $this->getConn()->query($sql);
        $return = $stmt ? $this->getConn()->lastInsertId() : false;
        $this->sqlEnd($sql, $stmt);
        return $return;
    }

    /**
     * insert 的别名函数
     *
     * @param array $row
     * @param array $onDup
     *
     * @return int
     * @throws SimpleOrmError
     */
    public function create($row, $onDup=null) {
        return $this->insert($row, $onDup);
    }

    /**
     * $res = $model->query("select * from user");
     * foreach ($res as $row) {
     *  var_dump($row);
     * }
     *
     * @param $sql
     *
     * @return array|int|string
     * @throws SimpleOrmError
     */
    public function query($sql) {
        $this->sqlStart();
        $stmt = $this->getConn()->query($sql);
        $sqlCmd = explode(' ', strtolower(trim($sql)))[0];

        if (!$stmt) {
            $return = false;
        } else if (in_array($sqlCmd, ['show', 'select'])){
            $return = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } else if (in_array($sqlCmd, ['insert'])) {
            $return = $this->getConn()->lastInsertId();
        } else {
            $return = $stmt->rowCount();
        }
        $this->sqlEnd($sql, $stmt);
        return $return;
    }
}