<?php
/**
 * Created by PhpStorm.
 * User: leexiaohui(oranzh.cc@gmail.com)
 * Date: 2018/4/24
 * Time: 15:53
 */

namespace app\Controllers;

use app\Exception\BlueWarningException;
use Respect\Validation\Validator as v;
use Server\CoreBase\ChildProxy;

class Filter extends BaseController
{
    protected $username;
    public function __construct($proxy = ChildProxy::class)
    {
        parent::__construct($proxy);
    }

    protected function initialization($controller_name, $method_name)
    {
        parent::initialization($controller_name, $method_name); // TODO: Change the autogenerated stub
    }

    public function http_between()
    {
        $input = $this->http_input->getPost('input');
        try {
            $res = v::intVal()->validate($input);
        }catch (BlueWarningException $exception) {

        }
        $this->end($res);
    }

    public function http_object()
    {
        $user = new \stdClass;
        $user->name = 'Alexandre';
        $user->birthdate = '1987-07-01';
        $userValidator = v::attribute('name', v::stringType()->length(1, 32))
            ->attribute('birthdate', v::minimumAge(18));
        $res = $userValidator->validate($user);
        $this->end($res);
    }

    public function http_array()
    {
        $url = 'http://www.google.com/search';
        //$parts = parse_url($url);

        $res = v::call(
            'parse_url',
            v::arrayVal()->key('scheme', v::startsWith('http'))
                ->key('host',   v::domain())
                ->key('path',   v::stringType())
                ->key('query',  v::notEmpty())
        )->validate($url);
        $this->end($res);
    }

    public function http_parse()
    {
        $url = 'http://www.google.com/search?q=respect.github.com';
        $parts = parse_url($url);
        $this->end($parts);
    }

    public function http_arr()
    {
        $arr = [
            'name' => 'lee',
            'age' => 18,
        ];

        $res = v::arrayVal()->key('name', v::stringType()->length(1, 30))
            ->key('age', v::intType()->between(20, 30))->check($arr);

        $this->end($res);
    }

    public function http_user()
    {
        $username = v::alnum()->noWhitespace()->length(1, 15);
        $res = $username->validate('leexiaohui');
        $this->end($res);
    }

    public function http_test()
    {
        try {
            v::arrayVal()->key('name', v::stringType()->length(4, 30))
                ->key('age', v::intVal()->between(20, 30))->check($this->http_input->getAllGet());
        } catch (BlueWarningException $exception) {
            //什么也不用写,中断程序
        }
        $data = $this->http_input->getAllGet();
        $this->end($data);
    }

    public function http_ex()
    {
        try {
            $input = [
                'username' => 'u3',
                'birthdate' => '2018-02-13',
                'password' => '1',
                'email' => 'ab.c@om',
                'sex' => 1
            ];
            v::key('username', v::length(2, 32))
                ->key('birthdate', v::date())
                ->key('password', v::notEmpty())
                ->key('email', v::filterVar(FILTER_VALIDATE_EMAIL))
                ->check($input);
        } catch (BlueWarningException $e) {
        }
        $this->end(1);
    }
}