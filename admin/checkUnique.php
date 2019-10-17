<?php
$root_path = $_SERVER['DOCUMENT_ROOT'];
require_once($root_path.'/admin/functions.php');
// 传过来参数：邮箱，用户名，密码，unique;
// 还有unique参数，如果没传unique就表示是register页面的查询，是检测邮箱和用户名的唯一性
// 如果有unique参数，就表示是login页面的查询，是检测用户名是否注册
// 如果是用户名和密码一起传递过来就表示是查询密码和用户名是否匹配
// 如果是邮箱和unique一起传递过来就表示是查询邮箱是否存在，是忘记密码页面发送过来的
if(empty($_POST['username']) && empty($_POST['password'])){
  exit('缺少必要参数');
}
if($_SERVER['REQUEST_METHOD'] === 'POST') {
  check();
}
function check(){
  // 检测用户名是否注册,登陆界面的查询
  if(isset($_POST['username']) && isset($_POST['unique']) && $_POST['unique'] == 'false') {
    $username = $_POST['username'];
    $username_sql = "select name from adminuser where name = '{$username}' limit 1";
    $row =blog_select_one($username_sql);
    $isAvailable = true;
    if($row){
      // 这里表示用户存在
      echo json_encode(array('valid' => $isAvailable));
    } else {
      // 查询到不存在
      // 用户未注册
      $isAvailable = false;
      echo json_encode(array('valid' => $isAvailable));
    }
  }
  // 校验密码与用户名是否匹配
  if(isset($_POST['password']) && isset($_POST['username']) && empty($_POST['unique'])){
    $password = md5($_POST['password']);
    $username = $_POST['username'];
    $match_sql = "select name,password from adminuser where name = '{$username}' limit 1";
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
