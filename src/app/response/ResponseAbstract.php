<?php
/**
 * @author 不二进制
 * @datetime 2020年05月30日21
 */


namespace phpcmx\common\app\response;


use phpcmx\common\lib\CliTool;

/**
 * Class Response
 *
 * @package phpcmx\common\app\response
 */
class ResponseAbstract
{
    const DEFAULT_BODY = 'default_body';

    protected $_body = [];
    protected $_header = [];
    protected $_redirect = '';

    /**
     * return ResponseCli | ResponseHttp
     */
    public static function getResponse() {
        if (CliTool::isCli()) {
            return new ResponseCli();
        }
        return new ResponseHttp();
    }

    /**
     * @param string      $body
     * @param string|null $name
     *
     * @return ResponseAbstract
     */
    public function setBody(string $body, string $name = null) {
        $name = $name ?: self::DEFAULT_BODY;
        $this->_body[$name] = $body;
        return $this;
    }

    /**
     * @param string      $body
     * @param string|null $name
     *
     * @return ResponseAbstract
     */
    public function prependBody(string $body, string $name = null) {
        $name = $name ?: self::DEFAULT_BODY;
        $bodyNow = $this->_body[$name] ?? '';
        $this->_body[$name] = $body . $bodyNow;
        return $this;
    }

    /**
     * @param string      $body
     * @param string|null $name
     *
     * @return ResponseAbstract
     */
    public function appendBody(string $body, string $name = null) {
        $name = $name ?: self::DEFAULT_BODY;
        $bodyNow = $this->_body[$name] ?? '';
        $this->_body[$name] = $bodyNow . $body;
        return $this;
    }

    /**
     * @param string|null $name
     *
     * @return ResponseAbstract
     */
    public function clearBody(string $name = null) {
        if ($name) {
            unset($this->_body[$name]);
        } else {
            $this->_body = [];
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getBody() {
        return $this->_body;
    }

    /**
     * @return array
     */
    public function getHeader() {
        return $this->_header;
    }

    /**
     * 输出
     */
    public function response() {
        if ($this->_redirect) {
            header('Location: ' . $this->_redirect);
            return ;
        }
        foreach ($this->_header as $header) {
            header($header);
        }

        echo $this->__toString();
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setRedirect(string $url) {
        $this->_redirect = $url;
        return $this;
    }

    /**
     * @param string $header
     *
     * @return $this
     */
    public function setHeader(string $header) {
        $this->_header[] = $header;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString() {
        return implode($this->_body);
    }
}