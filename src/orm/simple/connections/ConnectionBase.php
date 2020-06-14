<?php
/**
 * @author 不二进制
 * @datetime 2020年06月09日00
 */


namespace phpcmx\common\orm\simple\connections;


use PDO;
use PDOException;
use PDOStatement;

/**
 * 连接
 * Class SqlBase
 *
 * @package phpcmx\common\orm\simple
 */
abstract class ConnectionBase
{
    protected $dsn;
    protected $username;
    protected $passwd;
    protected $options;

    /** @var PDO */
    private $_pdo;

    /**
     * 创建连接
     */
    protected function connect() {
        // 默认option
        $options = array_merge([
            // 有问题抛异常
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            // 长连接
            PDO::ATTR_PERSISTENT => true,
        ], $this->options);
        $this->_pdo = new PDO($this->dsn, $this->username, $this->passwd, $options);
    }

    /**
     * 获取 pdo
     * @return PDO
     */
    public function getPdo() {
        return $this->_pdo;
    }

    public function __destruct() {
        $this->_pdo = null;
    }

    /**
     * 开始事务
     */
    public function beginTransaction() {
        $this->getPdo()->beginTransaction();
    }

    /**
     * 是否在一个事务里
     * @return bool
     */
    public function inTransaction() {
        return $this->getPdo()->inTransaction();
    }

    /**
     * 提交事务
     */
    public function commit() {
        $this->getPdo()->commit();
    }

    /**
     * 回滚事务
     */
    public function rollBack() {
        $this->getPdo()->rollBack();
    }

    /**
     * 取回最后一个插入的id
     * @param null $name
     *
     * @return string
     */
    public function lastInsertId($name = null) {
        return $this->getPdo()->lastInsertId($name);
    }

    /**
     * @param $sql
     *
     * @return false|PDOStatement
     */
    public function query(string $sql) {
        return $this->getPdo()->query($sql);
    }

    /**
     * @param $sql
     *
     * @return bool|PDOStatement
     */
    public function prepare($sql) {
        return $this->getPdo()->prepare($sql);
    }
}