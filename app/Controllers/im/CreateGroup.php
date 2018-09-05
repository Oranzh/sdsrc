<?php
/**
 * Created by PhpStorm.
 * User: leexiaohui(oranzh.cc@gmail.com)
 * Date: 2018/8/8
 * Time: 12:54
 */

namespace app\Controllers\im;


use app\Controllers\BaseController;
use app\Exception\BlueWarningException;
use app\Models\commit\group\Create;
use Respect\Validation\Validator as v;
use Server\CoreBase\ChildProxy;

class CreateGroup extends BaseController
{
    private $cGroupCreate;
    public function __construct($proxy = ChildProxy::class)
    {
        parent::__construct($proxy);
    }
    
    protected function initialization($controller_name, $method_name)
    {
        parent::initialization($controller_name, $method_name); // TODO: Change the autogenerated stub
        $this->cGroupCreate = $this->loader->model(Create::class,$this);
    }

    public function setUp()
    {
        $this->needLogin();
    }

    public function http_perform()
    {
        $params = $this->verify();
        //$params['name'] = $this->http_input->getPost('name');
        $params['leader'] = $this->context['sess']['id'];
        $this->context['commit'] = $params;
        $this->cGroupCreate->perform();
        $this->end('群组添加成功');
    }

    private function verify()
    {
        $params = $this->input();
        try {
            v::arrayVal()->key('name', v::stringType()->length(3, 20))
                ->check($params);
        }catch (BlueWarningException $blueWarningException) {

        }
        return $params;
    }

}