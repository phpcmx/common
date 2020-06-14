<?php
/**
 * @author 不二进制
 * @datetime 2020年06月10日00
 */


namespace phpcmx\common\orm\simple\sql;


/**
 * Class Query
 *
 * @package phpcmx\common\orm\simple\sql
 */
abstract class SqlBase
{
    /** @var string */
    protected $_query;

    /**
     * @return string
     */
    public function getQuery(): string {
        return $this->_query;
    }
}