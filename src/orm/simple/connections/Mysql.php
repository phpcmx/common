<?php
/**
 * @author 不二进制
 * @datetime 2020年06月09日00
 */


namespace phpcmx\common\orm\simple\connections;


/**
 * mysql连接
 * Class Mysql
 *
 * @package phpcmx\common\orm\simple\connections
 */
class Mysql extends ConnectionBase
{
    protected $host;
    protected $port;
    protected $dbname;

    public function __construct(
        string $host, int $port, string $dbname,
        string $username, string $passwd, array $options = []
    ) {
        $this->host = $host;
        $this->port = $port;
        $this->dbname = $dbname;
        $this->dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->dbname}";
        $this->username = $username;
        $this->passwd = $passwd;
        $this->options = $options;
        $this->connect();
    }
}