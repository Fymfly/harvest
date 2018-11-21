<?php
// 为了使用 composer 下载的类包必须先引入自动加载文件

require('./vendor/autoload.php');

use Firebase\JWT\JWT;

$pdo = new \PDO('mysql:host=localhost;dbname=chat','root','');
$pdo->exec('SET NAMES utf8');

// 接收原始数据
$post = file_get_contents('php://input');

// json 转为数组
$_POST = json_decode($post, TRUE);

$stmt = $pdo->prepare('SELECT * FROM users WHERE username=? AND password=?');
$stmt->execute([

    $_POST['username'],
    md5($_POST['password']),
]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if($user) {

    $key = 'abcd1234';
    // $now = time();

    $data = array(
        'id' => $user['id'],      // 生成时间
        'username' => $user['username'],
    );

    // 为这个数据生成令牌
    $jwt = JWT::encode($data, $key);

    // 返回 JSON 数据
    echo json_encode([
        'code'=>'200',
        'jwt'=>$jwt,
    ]);

} else {

    // 返回 JSON 数据
    echo json_encode([
        'code'=>'403',
        'error'=>'用户名或者密码错误！',
    ]);
}

// $data = [
//     [
//         'id'=>1,
//         'username'=>'tom'
//     ],
//     [
//         'id'=>2,
//         'username'=>'jack'
//     ]
// ];

// // 登录成功 生成 令牌
// if($_GET['username'] == 'tom' && $_GET['password'] == '123456') {

    // $key = 'abcd1234';
    // $now = time();

    // $data = [
    //     'id' => 1,      // 生成时间
    //     'username' => 'tom',
    // ];

    // // 为这个数据生成令牌
    // echo JWT::encode($data, $key);

// }

// 生成的令牌
$token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1NDIxMDI2ODcsImV4cCI6MTU0MjEwNjI4NywibmFtZSI6InRvbSIsImFnZSI6MTB9.O8htb2HDxQjWkRI9wUouQE0vTEfuLLQ_3GjI-jZAzuY';




// require './vendor/autoload.php';
// use Firebase\JWT\JWT;

// //连接数据库
// $pdo = new PDO("mysql:host=127.0.0.1;dbname=chat","root","");
// $pdo->exec("SET NAMES utf8");

// /** 由于前端 axios 在发送 AJAX 时数据是以 JSON 格式提交的，
//  * 而PHP不能直接使用 $_POST 来接收 JSON/XML等格式的数据
//  * 要接收这种数据需要使用 file_get_contents('php://input')
//  */
// // 接收原始数据
// $post = file_get_contents('php://input');
// // 把JSON转成数组
// $_POST = json_decode($post, TRUE);

// //查询数据
// $stmt = $pdo->prepare("select * from users where username=? and password=?");
// $stmt->execute(array(
//    $_POST['username'],
//    md5($_POST['password'])
// ));
// $user = $stmt->fetch(PDO::FETCH_ASSOC);
// if($user){
//     $key = "123abc";
//     $data = array(
//       'id'=> $user['id'],
//       'username'=>$user['username']
//     );
// //    为这个数据生成令牌
//     $jwt = JWT::encode($data,$key);
// //    返回json数据
//     echo json_encode(array(
//        'code' => 200,
//         'jwt' => $jwt
//     ));
// }else{
//     echo json_encode(array(
//        'code' => 403,
//        'error' => '用户名或者密码错误!'
//     ));
// }