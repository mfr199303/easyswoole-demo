<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2019-01-01
 * Time: 20:06
 */

return [
    'SERVER_NAME' => "EasySwoole",
    'MAIN_SERVER' => [
        'LISTEN_ADDRESS' => '0.0.0.0',
        'PORT' => 9501,
        'SERVER_TYPE' => EASYSWOOLE_WEB_SERVER, //可选为 EASYSWOOLE_SERVER  EASYSWOOLE_WEB_SERVER EASYSWOOLE_WEB_SOCKET_SERVER,EASYSWOOLE_REDIS_SERVER
        'SOCK_TYPE' => SWOOLE_TCP,
        'RUN_MODEL' => SWOOLE_PROCESS,
        'SETTING' => [
            'worker_num' => 8,
//            'task_worker_num' => 8,
            'reload_async' => true,
            'task_enable_coroutine' => true,
            'max_wait_time'=> 3
        ]
    ],
    'TASK'=>[
        'workerNum'=>4,
        'maxRunningNum'=>128,
        'timeout'=>15
    ],

    'MYSQL' => [
        //数据库配置
        'host'                 => '192.168.18.100',//数据库连接ip
        'user'                 => 'root',//数据库用户名
        'password'             => 'root',//数据库密码
        'database'             => 'baizhitang_customer',//数据库
        'port'                 => 3306,//端口
        'timeout'              => '3',//超时时间
        'connect_timeout'      => '5',//连接超时时间
        'charset'              => 'utf8mb4',//字符编码
    ],

    'TEMP_DIR' => null,
    'LOG_DIR' => null
];
