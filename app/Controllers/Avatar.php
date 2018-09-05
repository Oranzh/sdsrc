<?php
/**
 * Created by PhpStorm.
 * User: lee
 * Date: 5/3/18
 * Time: 8:57 AM
 */

namespace app\Controllers;


use Server\CoreBase\ChildProxy;
use LasseRafn\InitialAvatarGenerator\InitialAvatar;

class Avatar extends BaseController
{
    public function __construct($proxy = ChildProxy::class)
    {
        parent::__construct($proxy);
    }
    protected function initialization($controller_name, $method_name)
    {
        parent::initialization($controller_name, $method_name); // TODO: Change the autogenerated stub
    }

    public function http_perform()
    {
        $avatar = new InitialAvatar();
        $image = $avatar->name('Lasse Rafn')->generate();
        $res = $image->stream('png',100);
        $this->http_output->setHeader('Content-Type', 'image/png');
        //$this->http_output->setContentType('image/png');
        $this->http_output->end($res);
    }

}