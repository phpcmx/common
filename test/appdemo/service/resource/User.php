<?php


namespace app\service\resource;


use phpcmx\common\app\action\RestfulActionAbstract;

class User extends RestfulActionAbstract
{
    /**
     * @return array
     */
    public function get() {
        $request = $this->getRequest();

        // http://127.0.0.1:8080/resource/user/id/1
        $id = $request->getParam('id', null);
        if ($id) {
            return ['name' => $id, ];
        }

        return [
            ['name' => 'foo', 'sex' => 1],
            ['name' => 'bar', 'sex' => 0],
        ];
    }
}