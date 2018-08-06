<?php
/**
 * Created by PhpStorm.
 * User: leexiaohui(oranzh.cc@gmail.com)
 * Date: 2018/8/6
 * Time: 12:39
 */

namespace app\Models\service;

use app\Exception\BlueWarningException;
use app\Models\dao\Passport as dPassport;
use Server\CoreBase\ChildProxy;
use Server\CoreBase\Model;

class Passport extends Model
{
    private $dPassport;
    private $pub_key = 'JKLFD34jklfdsiJKDLFS';

    public function __construct($proxy = ChildProxy::class)
    {
        parent::__construct($proxy);
    }

    public function initialization(&$context)
    {
        parent::initialization($context); // TODO: Change the autogenerated stub
        $this->dPassport = $this->loader->model(dPassport::class,$this);
    }

    public function getFromName($name)
    {
        return $this->dPassport->getFromName($name);
    }

    public function login($name,$password)
    {
        $tmp = $this->getFromName($name);
        if (!password_verify($password,$tmp['password'])) throw new BlueWarningException('用户信息无效');
        $token = [
            'id' => $tmp['id'],
            'name' => $name,
            'expire' => 10 * 86400,
            'time' => time(),
        ];
        $token = encode_aes($token,$this->pub_key,true);
        return [
            'token' => $token,
            'user' => [
                'id' => $tmp['id'],
                'name' => $tmp['name'],
            ],
        ];
    }
}