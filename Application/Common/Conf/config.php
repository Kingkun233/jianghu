<?php
return array(
	//'配置项'=>'配置值'
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => 'localhost', // 服务器地址
    'DB_NAME'   => 'jianghu', // 数据库名
    'DB_USER'   => 'root', // 用户名
    'DB_PWD'    => '4411918471', // 密码
    'DB_PORT'   => 3306, // 端口
    'DB_PREFIX' => 'jianghu_', // 数据库表前缀
    'DB_CHARSET'=> 'utf8', // 字符集
    'DB_DEBUG'  =>  TRUE, // 数据库调试模式 开启后可以记录SQL日志 3.2.3新增
    'URL_CASE_INSENSITIVE' =>true,
//     //***********************************SESSION设置**********************************
//     'SESSION_OPTIONS'         =>  array(
//         'name'                =>  'BJYSESSION',                    //设置session名
//         'expire'              =>  24*3600*15,                      //SESSION保存15天
//         'use_trans_sid'       =>  1,                               //跨页传递
//         'use_only_cookies'    =>  0,                               //是否只开启基于cookies的session的会话方式
//     ),
);
