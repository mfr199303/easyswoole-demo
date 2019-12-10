<?php
/**
 * Created by PhpStorm.
 * User: mfr
 * Date: 19-11-16
 * Time: 下午4:42
 */

namespace App\Middleware;

class TokenValidationMiddleware
{
    protected static $instance;
    public static function getInstance()
    {
        if ( !isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }


    public function handle($request, $response)
    {
        $response->withStatus(300);
        $response->write(json_encode(['code' => 300 , 'errstr' => '没有登录的说' ]));
        $response->withHeader('Content-type', 'application/json;charset=utf-8');
        return $response->end();
    }

}