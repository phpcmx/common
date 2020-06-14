<?php


namespace app\service\resource;


use app\service\model\dao\TestModel;
use phpcmx\common\app\action\RestfulActionAbstract;
use phpcmx\common\app\exception\HttpException;

class User extends RestfulActionAbstract
{
    /** @var TestModel */
    private $model;

    /**
     * @return mixed
     */
    protected function init() {
        $this->model = new TestModel();
    }

    /**
     * @return mixed
     */
    public function get() {
        // http://127.0.0.1:8080/resource/user/id/1
        $request = $this->getRequest();
        $id = $request->getParam('id', null);

        $fields = ['id', 'create_time', 'update_time'];
//        $fields = 'id, create_time, update_time';
        if ($id) {
            $conditions = [
                ['id', '=', $id],
            ];
            $row = $this->model->get($fields, $conditions);
            if ($row) {
                return $row;
            } else {
                throw new HttpException('resource miss', 404);
            }
        } else {
            $conditions = [
                '1=1',
            ];
            return $this->model->filter($fields, $conditions);
        }
    }

    /**
     * @return mixed
     */
    public function post() {
        $body = $this->getRequest()->getPost();
        $id = $this->model->insert($body);
        return [
            'status' => '200',
            'message' => 'ok',
            'data' => [
                'last_id' => $id,
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function put() {
        $id = $this->getRequest()->getParam('id');
        $body = $this->getRequest()->getPost();
        if (empty($id) || empty($body)) {
            throw new HttpException('param error', 400);
        }

        $conditions = [
            'id' => $id,
        ];

        $row = $this->model->get('*', $conditions);
        if (empty($row)) {
            throw new HttpException('not found', 404);
        }

        $this->model->update($body, $conditions);
        return [
            'status' => '200',
            'message' => 'ok'
        ];
    }

    /**
     * @return array|void
     */
    public function delete() {
        $id = $this->getRequest()->getParam('id');
        if (empty($id)) {
            throw new HttpException('param error', 400);
        }

        $conditions = [
            'id' => $id,
        ];

        $row = $this->model->get('*', $conditions);
        if (empty($row)) {
            throw new HttpException('not found', 404);
        }
        $this->model->delete($conditions);
        return [
            'status' => '200',
            'message' => 'ok'
        ];
    }
}