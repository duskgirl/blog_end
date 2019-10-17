<?php
$root_path = $_SERVER['DOCUMENT_ROOT'];
require_once($root_path.'/functions.php');
// 保存用户修改的信息
header('Content-Type:application/json;setcharset=utf-8');
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  save_modify();
}
function save_modify(){
  if(empty($_POST['email'])){
    echo result('save','请正确操作修改资料!',false);
    return;
  }
  // 先查询该用户是否存在，如果不存在不能往后继续
  $email = $_POST['email'];
  $sql = "select name,password,avatar from user where email = '{$email}'";
  $result = blog_select_one($sql);
  // 该用户原来的信息
  $name_old = $result['name'];
  $password_old = $result['password'];
  $avatar_old = $result['avatar'];
  // 该用户不存在
  if(empty($result)){
    echo result('save','请正确操作修改资料!',false);
    return;
  }
  if(empty($_POST['name']) || empty($_POST['password']) || empty($_POST['repassword']) || empty($_POST['avatar'])){
    echo result('save','请正确操作修改资料!',false);
    return;
  }
  // 两个都不相等
  if($_POST['password'] != $_POST['repassword']){
    echo result('save','两次密码输入不一致',false);
    return;
  }
  // 保存用户修改的信息
  $name = $_POST['name'];
  $password = md5($_POST['password']);
  $avatar = $_POST['avatar'];
  // 用户名修改||密码修改||用户头像修改
  // 用户名修改 && 密码修改 || 用户头像修改 && 密码修改 || 头像 && 用户名修改 
  // 三者一起修改
  $modify_name =  $name_old != $name ? true : false;
  $modify_password = $password_old != $password ? true : false;
  $modify_avatar = $avatar_old != $avatar ? true : false;
  if(!$modify_name && !$modify_password && !$modify_avatar){
    echo result('save','修改的资料不能和以前一样哟!',false);
    return;
  }
  // 最开始要设置的设置一个变量
  $modify_sql = "";
  if($modify_name){
    $modify_sql .= "name='{$name}',";
  }elseif(!$modify_password && !$modify_avatar){
    $modify_sql .= "name='{$name}'";
  } 
  if($modify_password){
    $modify_sql .= "password='{$password}',";
  }elseif(!$modify_avatar){
    $modify_sql .= "password='{$password}'";
  }
  if($modify_avatar){
    $modify_sql .= "avatar='{$avatar}'";
  }
  $sql = "update user set {$modify_sql} where email = '{$email}'";
  $result = blog_update($sql);
  if(!$result){
    echo result('save','修改资料失败，请稍后重试',false);
    return;
  } else {
    // 同时修改session
    $session_sql = "select id,email,name,password,avatar,userstats from user where email = '{$email}' limit 1";
    $user = blog_select_one($session_sql);
    $_SESSION['current_login_user'] = $user;
    $success_result = result('save','修改资料成功！',true);
    echo $success_result;
  }
}
function result($title,$message,$value){
  if(empty($message)){
    $result = array(
      $title => $value
    );
  } else {
      $result = array(
        $title => $value,
        'message' => $message
      );
    }
  $result = json_encode($result);
  return $result;
}