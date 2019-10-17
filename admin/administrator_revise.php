<?php
$root_path = $_SERVER['DOCUMENT_ROOT'];
require_once($root_path.'/admin/functions.php');
blog_get_admin_user();
// 渲染表单
// 修改数据
if($_SERVER['REQUEST_METHOD'] === 'GET') {
  getRevisePage();
  $_SESSION['revise_user'] = $result;
}
function getRevisePage(){
  if(empty($_GET['id'])){
    exit('请传入必要参数!');
  }
  $id = $_GET['id'];
  // 渲染
  $sql = "select id,name,password,permission from adminuser where id={$id}";
  $GLOBALS['result'] = blog_select_one($sql);
  if(!$GLOBALS['result']) {
    $GLOBALS['err_message'] = '查询数据失败';
    return;
  }
}

// 只有管理员才能修改账户
if($_SERVER['REQUEST_METHOD'] === 'POST') {
  revise();
}
function revise(){
  if(is_admin()){
    if(empty($_POST['id'])){
      exit('请传入必要参数');
    }
    if($_POST['id'] != $_SESSION['revise_user']['id']){
      exit('必要参数错误');
    }
    if(empty($_POST['name'])){
      $GLOBALS['err_message'] = '用户名不能为空';
      return;
    }
    if(empty($_POST['password'])){
      $GLOBALS['err_message'] = '密码不能为空';
      return;
    }
    if(empty($_POST['repassword'])){
      $GLOBALS['err_message'] = '确认密码不能为空';
      return;
    }
    if($_POST['repassword'] != $_POST['password']){
      $GLOBALS['err_message'] = '两次输入密码不一致，请重新输入';
      return;
    }
    if($_POST['permission'] != 0 && $_POST['permission'] != 1 ){
      $GLOBALS['err_message'] = '权限设置有误，请重新设置';
      return;
    }
    if($_POST['password'] == $_SESSION['revise_user']['password']){
      $password = $_SESSION['revise_user']['password'];
    } else {
      $password = md5($_POST['password']);
    }
    $id = $_SESSION['revise_user']['id'];
    $name = $_POST['name'];
    $permission = $_POST['permission'];
    // 如果所有的信息和原来的一样，直接跳转到管理员管理页面
    if($name == $_SESSION['revise_user']['name'] && $password ==  $_SESSION['revise_user']['password'] && $permission ==  $_SESSION['revise_user']['permission']){
      header('Location:/admin/administrator.php');
    }
    $sql = "update adminuser set name='{$name}',password='{$password}',permission={$permission} where id={$id}";
    $result = blog_update($sql);
    if(!$result){
      $GLOBALS['err_message'] = '修改管理员信息失败，请稍后重试!';
    } else {
      $GLOBALS['success_message'] = '修改管理员信息成功!';
      unset($_SESSION['revise_user']);
      header('Location:/admin/administrator.php');
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="renderer" content="webkit" />
  <meta name="force-renderer" content="webkit" />
  <meta http-equiv="X-UA-Compatible" content="IE=Edge chrome=1" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0, shrink-to-fit=no" />
  <meta name="apple-mobile-web-app-title" content="大思考博客" />
  <meta http-equiv="Cache-Control" content="no-siteapp" />
  <meta name="referrer" content="always">
  <meta name="format-detection" content="telephone=no,email=no,adress=no">
  <title>大思考-后台管理员账户修改</title>
  <meta name="keywords" content="大思考,大思考博客,前端开发,前端开发博客" />
  <meta name="description" content="大思考博客是一个分享前端开发相关知识的博客网站" />
  <link rel="stylesheet" href="./lib/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="./lib/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="./css/administrator_revise.css">
</head>
<body>
  <div class="container-fluid blog_admin_revise">
    <?php if(isset($err_message)): ?>
    <div class="alert alert-danger prompt_message  alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <strong>错误!</strong><?php echo $err_message?>
    </div>
    <?php endif ?>
    <?php if(isset($success_message)): ?>
    <div class="alert alert-success prompt_message  alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <strong>成功!</strong><?php echo $success_message?>
    </div>
    <?php endif ?>
    <h1>修改管理员账户</h1>
    <?php if(isset($result)):?>
    <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
      <input type="hidden" class="form-control " name="id" value="<?php echo $result['id']?>">
      <h3>用户名</h3>
      <input type="text" class="form-control" placeholder="请输入用户名" name="name" value="<?php echo $result['name']?>">
      <h3>密码</h3>
      <input type="password" class="form-control" placeholder="请输入密码" name="password" value="<?php echo $result['password']?>">
      <h3>确认密码</h3>
      <input type="password" class="form-control" placeholder="请重复输入密码" name="repassword"  value="<?php echo $result['password']?>">
      <h3>权限</h3>
      <select class="form-control" name="permission">
        <?php if($result['permission'] == 0):?>
        <option value="0" selected>访问权限</option>
        <option value="1">管理员权限</option>
        <?php else:?>
        <option value="0">访问权限</option>
        <option value="1" selected>管理员权限</option>
        <?php endif?>
      <select>
      <input type="submit" value="提交" class="btn" id="btn">
    </form>
    <?php elseif(!empty($_POST)):?>
    <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
      <input type="hidden" class="form-control " name="id" value="<?php echo $_POST['id']?>">
      <h3>用户名</h3>
      <input type="text" class="form-control" placeholder="请输入用户名" name="name" value="<?php echo $_POST['name']?>">
      <h3>密码</h3>
      <input type="password" class="form-control" placeholder="请输入密码" name="password" value="<?php echo $_POST['password']?>">
      <h3>确认密码</h3>
      <input type="password" class="form-control" placeholder="请重复输入密码" name="repassword"  value="<?php echo $_POST['password']?>">
      <h3>权限</h3>
      <select class="form-control" name="permission">
        <?php if($_POST['permission'] == 0):?>
        <option value="0" selected>访问权限</option>
        <option value="1">管理员权限</option>
        <?php else:?>
        <option value="0">访问权限</option>
        <option value="1" selected>管理员权限</option>
        <?php endif?>
      <select>
      <input type="submit" value="提交" class="btn" id="btn">
    </form>
    <?php endif?>
  </div>
  <script src="/admin/lib/jquery/jquery.min.js"></script>
  <script src="/admin/lib/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>