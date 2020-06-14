<?php
/**
 * @author 不二进制
 * @datetime 2020年05月31日16
 */


namespace phpcmx\common\app\action;


use phpcmx\common\app\exception\HttpException;
use phpcmx\common\app\exception\restful\MethodNotAllow;
use phpcmx\common\app\response\ResponseAbstract;
use phpcmx\common\lib\HttpTool;

/**
 * Class RestfulAction
 *
 * @package phpcmx\common\app\action
 */
abstract class RestfulActionAbstract extends ActionAbstract
{
    /**
      * 执行入口
      *
      * @return bool | string | array | ResponseAbstract 执行入口
      */
    protected function execute() {
         $request = $this->getRequest();
         if ($request->isGet()) {
             $method = 'get';
         } else if ($request->isPost()) {
             $method = 'post';
         } else if ($request->isPut()) {
             $method = 'put';
         } else if ($request->isDelete()) {
             $method = 'delete';
         } else {
             throw new MethodNotAllow(HttpTool::getMethod());
         }

         try {
             $response = $this->$method();
             if (!($response instanceof ResponseAbstract)) {
                 $response = $this->getResponse()->setBody(json_encode($response));
             }
         } catch (MethodNotAllow $e) {
             $response = $this->httpResponseCode(
                 405,
                 'Method Not Allowed'
             );
         } catch (HttpException $e) {
             $response = $this->httpResponseCode(
                 $e->getCode(),
                 $e->getMessage()
             );
         } catch (\Exception $e) {
             $response = $this->httpResponseCode(
                 500,
                 'Internal Server Error'
             );
         }

        $response->setHeader('Content-type: application/json');
        return $response;
    }

    /**
     * @param $code
     * @param $message
     *
     * @return ResponseAbstract
     */
    protected function httpResponseCode($code, $message) {
        http_response_code($code);
        $response = $this->getResponse()->setBody(json_encode([
            'status' => $code,
            'message' => $message,
        ]));
        return $response;
    }

    /**
     * get 获取
     * @return array
     */
    function get() {
        throw new MethodNotAllow('GET');
    }

    /**
     * post 新增 不可重复提交
     * @return array
     */
    function post() {
        throw new MethodNotAllow('POST');
    }

    /**
     * put 修改 可重复提交
     * @return array
     */
    function put() {
         throw new MethodNotAllow('PUT');
    }

    /**
     * delete 删除
     * @return array
     */
    function delete() {
        throw new MethodNotAllow('DELETE');
    }
 }