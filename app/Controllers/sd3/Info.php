<?php
/**
 * Created by PhpStorm.
 * User: leexiaohui(oranzh.cc@gmail.com)
 * Date: 2018/7/19
 * Time: 16:46
 */

namespace app\Controllers\sd3;

use app\Controllers\BaseController;
use app\Models\service\MysqlService;
use Respect\Validation\Validator as v;

class Info extends BaseController
{
    private $mysqlService;
    protected function initialization($controller_name, $method_name)
    {
        parent::initialization($controller_name, $method_name); // TODO: Change the autogenerated stub
        $this->mysqlService = $this->loader->model(MysqlService::class,$this);
    }

    public function http_perform()
    {
        $params = $this->verify();
        $info = $this->mysqlService->selectOne($params['id']);
        $this->end($info);
    }

    private function verify()
    {
        $params = $this->http_input->getAllGet();
        v::arrayVal()->key('id',v::intVal()->min(1,true))->check($params);
        return $params;
    }

}