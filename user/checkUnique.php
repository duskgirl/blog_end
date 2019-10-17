<?php
$root_path = $_SERVER['DOCUMENT_ROOT'];
require_once($root_path.'/functions.php');
// 可能传过来的参数：邮箱，用户名，密码，unique;
// 还有unique参数，如果没传unique就表示是register页面的查询，是检测邮箱和用户名的唯一性
// 如果有unique参数，就表示是login页面的查询，是检测用户名是否注册
// 如果是用户名和密码一起传递过来就表示是查询密码和用户名是否匹配
// 如果是邮箱和unique一起传递过来就表示是查询邮箱是否存在，是忘记密码页面发送过来的
if(empty($_POST['email']) && empty($_POST['username']) && empty($_POST['password'])){
  exit('缺少必要参数');
}
if($_SERVER['REQUEST_METHOD'] === 'POST') {
  check();
}
function check(){
  // 校验邮箱唯一性
  if(isset($_POST['email']) && empty($_POST['unique']) && empty($_POST['username']) && empty($_POST['password'])){
    $email = $_POST['email'];
    $email_sql = "select email from user where email = '{$email}' limit 1";
    $row = blog_select_one($email_sql);
    $isAvailable = true;
    if(!$row){
    // 查询不存在
      echo json_encode(array('valid' => $isAvailable));
    } else {
    // 查询存在
      $isAvailable = false;
      echo json_encode(array('valid' => $isAvailable));
    }
  }
  // 校验用户名唯一性
  if(isset($_POST['username']) && empty($_POST['unique']) && empty($_POST['password']) && empty($_POST['email'])){
    $username = $_POST['username'];
    $username_sql = "select name from user where name = '{$username}' limit 1";
    $row =blog_select_one($username_sql);
    $isAvailable = true;
    if(!$row){
      // 这里表示查询到不存在
      echo json_encode(array('valid' => $isAvailable));
    } else {
    // 这里表示查询到存在
      $isAvailable = false;
      echo json_encode(array('valid' => $isAvailable));
    }
  }
  // 校验邮箱唯一性：不存在返回false，存在返回true
  if(isset($_POST['email']) && isset($_POST['unique']) && $_POST['unique'] == 'false' && empty($_POST['username']) && empty($_POST['password'])){
    $email = $_POST['email'];
    $email_sql = "select email from user where email = '{$email}' limit 1";
    $row = blog_select_one($email_sql);
    $isAvailable = true;
    if($row){
    // 这里表示查询到存在
      echo json_encode(array('valid' => $isAvailable));
    } else {
    // 这里表示查询到不存在
      $isAvailable = false;
      echo json_encode(array('valid' => $isAvailable));
    }
  }
  // 检测用户名是否注册,登陆界面的查询
  if(isset($_POST['username']) && isset($_POST['unique']) && $_POST['unique'] == 'false') {
    $username = $_POST['username'];
    $username_sql = "select name from user where name = '{$username}' limit 1";
    $row =blog_select_one($username_sql);
    $isAvailable = true;
    if($row){
      // 用户存在，那么就可以继续登陆
      echo json_encode(array('valid' => $isAvailable));
    } else {
      // 查询到不存在
      // 用户未注册
      $isAvailable = false;
      echo json_encode(array('valid' => $isAvailable));
    }
  }
  // 校验密码与用户名是否匹配
  if(isset($_POST['password']) && isset($_POST['username']) && empty($_POST['unique']) && empty($_POST['email'])){
    $password = md5($_POST['password']);
    $username = $_POST['username'];
    $match_sql = "select name,password from user where name = '{$username}' limit 1";
    $row = blog_select_one($match_sql);
    $isAvailable = true;
    if($row['password'] == $password){
      // 用户密码匹配，初步验证通过,没有任何提示信息
      echo json_encode(array('valid' => $isAvailable));
    } else {
      // 用户密码不匹配，不通过
      $isAvailable = false;
      echo json_encode(array('valid' => $isAvailable));
    }
  }
}