<?php
/**
 * @author 不二进制
 * @datetime 2020年06月13日13
 */


namespace phpcmx\common\orm\simple\sql;


/**
 * Class Query
 *
 * @package phpcmx\common\orm\simple\sql
 */
class Query extends SqlBase
{
    /**
     * @param $sql
     *
     * @return $this
     */
    public function query($sql) {
        $this->_query = $sql;
        return $this;
    }
}