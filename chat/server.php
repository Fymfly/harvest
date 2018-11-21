<?php

/*
WebSocket 的服务器端
*/
// 为了使用 composer 下载的类包必须先引入自动加载文件
require('./vendor/autoload.php');
require_once '../Workerman-master/Autoloader.php';

use Firebase\JWT\JWT;
use Workerman\Worker;

// 实例化 Worker 类对象
$worker = new Worker('websocket://0.0.0.0:9999');
// 设置进程数
$worker->count = 1;

// 保存所有的用户
// $users = [];
$users = array();

// 定义数组，保存用户ID和这个客户端的关系
// $userConn = [];
$userConn = array();

// 绑定连接的回调函数，这个函数会在有客户端连接时调用
// 参数：TcpConnection 类的对象，代表每个客户端
$worker->onConnect = function( $connection ) {
    
    $connection->onWebSocketConnect = function ($connection, $http_header) {

        // 保存当前用户信息
        global $users, $worker, $userConn;

        // JWT 解析
        try
        {   
            $key = 'abcd1234';
            $data = JWT::decode($_GET['token'], $key, array('HS256'));
            // var_dump( $data );

            // 把ID和name保存到这个连接上，以区分这个连接是谁
            $connection->uid = $data->id;
            $connection->uname = $data->username;

            // 保存用户到数组中
            // $users[$data->id] = $data->name;
            $users[] = array('id'=>$data->id,'username'=>$data->username);

            // 把当前这个客户端的对象保存到数组中，用户ID是下标
            $userConn[$data->id] = $connection;

            $connection->send(json_encode(array('type'=>'users','users'=>$users,'myuser'=>$data->username)));
            // 如果用户连接成功，就通知所有其他客户端有新客户端连接
            foreach($worker->connections as $c) {

                if($c !== $connection)
                    $c->send(json_encode(array(
                        'type'=>'newUser',
                        'newUser'=>array('id'=>$data->id,'username'=>$data->username),
                    )));
            }
            // var_dump($users);
        }
        catch(  \Firebase\JWT\ExpiredException $e)
        {   
            // 断开连接
            $connection->close();
            var_dump( '过期' );
        }
        catch( \Exception $e)
        {      
            // 断开连接
            $connection->close();
            var_dump( '令牌无效' );
        }
        
    };

};
// 接收消息
// $worker->onMessage = function($connection, $data) {
//     // var_dump($data);
//     global $worker;

//     $ret = explode(':', $data);

//     // 去掉第一个：前面的内容
//     $code = $ret[0];
//     // var_dump($code);
//     unset($ret[0]);
//     $rawData = implode(':',$ret);


//     // 判断第一个元素
//     if($code == 'all') {

//         // 循环所有的客户端，给它们发消息
//         foreach($worker->connections as $c) {

//             $c->send(json_encode([
//                 'type'=>'message',
//                 'message'=>$rawData
//             ]));
//         }

//     } else {

//         global $userConn;
        
//         // 根据用户ID找到对应的客户端对象
//         // 然后调用这个对象的 send 方法给这个客户端发消息
//         $userConn[$code]->send(json_encode([

//             'type'=>'message',
//             'message'=>$rawData
//         ]));
//     }

// };


// 接收信息
$worker->onMessage = function ($connection,$data) {

    global $worker;

    $arr = explode(':',$data);
    $code = $arr[0];
    unset($arr[0]);
    $arrData = implode(':',$arr);

    // 根据 id 找到对应客户端对象
    global $userConn;

    $userConn[$code]->send(json_encode(array(

        'send' => $connection->uid,
        'type' => 'message',
        'to' => $code,
        'message' => $arrData
    )));
   var_dump($arrData);
};


// 当有人断开时调用的回调函数
$worker->onClose = function($connection) {

    // 删除这个用户
    global $users, $worker;
    // 根据用户id从数组中删除
    // unset($users[$connection->uid]);
    // 通知所有其他用户（退出聊天的客户端的id）
    foreach($worker->connections as $c) {

        if($c!==$connection){
            $c->send(json_encode([
                'type'=>'logoutUser',
                'logoutUserId'=>$logoutUserId->uid,
            ]));
        }
        
    }

    // 删除保存的客户端信息
    for($i=0;$i<count($users);$i++) {
        if($users[$i]['id']==$connection->uid) {
            unset($users[$i]);
        }
    }

    $users = array_values($users);

    // 

};

// 运行
Worker::runAll();




/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2018/11/12
 * Time: 10:10
 */
// require_once '../Workerman-master/Autoloader.php';
// require('./vendor/autoload.php');
// use Firebase\JWT\JWT;
// use Workerman\Worker;

// //实例化worker
// $worker = new Worker('websocket://0.0.0.0:9999');
// //设置进程数
// $worker->count = 1;

// //定义数组保存所有的用户
// /*
// 一维数组，里面的数据格式：
// [
//     1 => 'tom',
//     2 => 'jack',
//     ....
// ]
// */
// $users = array();
// //定义数组保存用户的id和这个客户端的关系
// /*
// 一维数组，里面的数据格式：
// [
//     1 => $connection 对象,
//     2 => $connection 对象,
//     ....
// ]
// */
// $userConn = array();

// //客户端连接时调用
// $worker->onConnect = function ($connection){
// //    接收连接时的参数
//     $connection->onWebSocketConnect = function ($connection, $http_header) {

//         global $worker,$users,$userConn;

//         try
//         {
//             $key = "123abc";
//             $data = JWT::decode($_GET['token'], $key, array('HS256'));
//             $connection->uid = $data->id;
//             $connection->uname = $data->username;
//             $users[] = array('id'=>$data->id,'username'=>$data->username);
//             $userConn[$data->id] = $connection;
//             $connection->send(json_encode(array('type'=>'users','users'=>$users,'myuser'=>$data->username)));
//             foreach ($worker->connections as $c){
//                 if($c !== $connection)
//                 $c->send(json_encode(array(
//                     'type'=>'newUser',
//                     'newUser'=>array('id'=>$data->id,'username'=>$data->username)
//                 )));
//             }
//         }
//         catch(  \Firebase\JWT\ExpiredException $e)
//         {
//             $connection->close();
//         }
//         catch( \Exception $e)
//         {
//             $connection->close();
//         }

//     };
// };

// $worker->onMessage = function ($connection,$data){

//     $arr = explode(":",$data);
//     $code = $arr[0];
//     unset($arr[0]);
//     $arrData = implode(":",$arr);

//     //    根据id 找到对应客户端对象
//     global $userConn;
// //    var_dump($code);
//     $userConn[$code]->send(json_encode(array(
//         'send'=>$connection->uid,
//         'type'=>'message',
//         'to'=>$code,
//         'messsage'=>$arrData
//     )));

// };

// //当有人断开连接时调用这个函数
// $worker->onClose = function ($connection){

//     global $users,$worker;

// //    告诉所有用户 退出聊天的客户端的id
//     foreach($worker->connections as $c){
//         if($c!==$connection){
//             $c->send(json_encode(array(
//                 'type'=>'logoutUser',
//                 'logoutUserId'=>$connection->uid
//             )));
//         }
//     }
// //    删除保存的客户端信息
//     for($i=0;$i<count($users);$i++){
//         if($users[$i]['id']==$connection->uid){
//             unset($users[$i]);
//         }
//     }
//     $users = array_values($users);


// };

// Worker::runAll();
