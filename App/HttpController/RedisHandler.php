<?php
/**
 * Created by PhpStorm.
 * User: mfr
 * Date: 19-11-29
 * Time: 下午1:56
 */

namespace App\HttpController;

use EasySwoole\Session\AbstractSessionController;

class RedisHandler extends AbstractSessionController
{
    protected function sessionHandler(): \SessionHandlerInterface
    {
        /*
         * 此处应该由连接池拿链接，否则实际生产会导致不断创建链接
         */
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        return  new RedisHandler($redis);
    }

    function index()
    {
        $this->session()->start();
        $time = $this->session()->get('test');
        if($time){
            $this->response()->write('session time is '.$time);
        }else{
            $this->session()->set('test',time());
            $this->response()->write('session time is new set');
        }
    }




}