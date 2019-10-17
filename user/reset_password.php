<?php
// 载入配置文件
$root_path = $_SERVER['DOCUMENT_ROOT'];
require_once($root_path.'/functions.php');
// 渲染页面
if($_SERVER['REQUEST_METHOD'] === 'GET'){
  getPage();
}
function getPage(){
  if(empty($_GET['email']) || empty($_GET['token'])){
    exit('缺少必要的参数!');
  }
  $email = $_GET['email'];
  $token = $_GET['token'];
  $_SESSION['email'] = $email;
  $sql = "select id,name,email,password,modified_time from user where email = '{$email}' limit 1";
  $row = blog_select_one($sql);
  if(!$row){
    exit('无效的链接');
  }
  // 查询用户存在
  $mt = md5($row['id'].$row['name'].$row['password']);
  // 目前是验证密令
  // 密令不符合,也可能是已经修改了密码
  if($mt != $token) {
    header('Location:/user/vertify.php?resetpassword=1');
  }
  // 密令符合
  if(time()-strtotime($row['modified_time']) > 1*60*60){
    exit('该链接已过期!');
  }
  $GLOBALS['user'] = $row;
}
// 获取用户提交的数据：post
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  resetPassword();
}
function resetPassword(){
  if(empty($_POST['email']) && empty($_SESSION['email'])){
    exit('缺少必要参数');
  }
  if(empty($_POST['password'])){
    $GLOBALS['message'] = "密码不能为空";
    return;
  }
  if(empty($_POST['repassword'])){
    $GLOBALS['message'] = "确认密码不能为空";
    return;
  }
  if($_POST['password'] !== $_POST['repassword']){
    $GLOBALS['message'] = "两次输入的密码不一致，请重新输入";
    return;
  }
  
  $email = empty($_POST['email']) ? $_SESSION['email'] : $_POST['email'];
  $password = md5($_POST['password']);
  // 新密码不能和旧密码一致,先查询
  $sql = "select password from user where email = '{$email}' limit 1";
  $row = blog_select_one($sql);
  if($row['password'] === $password) {
    $GLOBALS['message'] = "新密码不能和原密码保持一致";
    return;
  }
  // 新密码和旧密码不一致了，才更改数据库
  $reset_password_sql = "update user set password = '{$password}' where email = '{$email}' limit 1";
  $reset_password_result = blog_update($reset_password_sql);
  if(!$reset_password_result){
    $GLOBALS['message'] = '重置密码失败,请稍后重试';
    return;
  }
  $GLOBALS['success_message'] = '重置密码成功！';
  unset($_SESSION['email']);
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
    <title>大思考-重置密码</title>
    <meta name="keywords" content="大思考,大思考博客,前端开发,前端开发博客" />
    <meta name="description" content="大思考博客是一个分享前端开发相关知识的博客网站" />
    <!-- CSS -->
    <link rel="stylesheet" href="/lib/font-awesome/css/font-awesome.min.css">
    <link href="/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/lib/bootstrapvalidator/css/bootstrapValidator.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/user/css/reset_password.css">
    <link rel="shortcut icon" href="favicon.ico">
    <!--[if lt IE 9]>
    <script src="lib/html5shiv/html5shiv.min.js"></script>
    <script src="lib/respond/respond.min.js"></script>
  <![endif]-->
  </head>
  <body>
    <div class="blog_reset_password">
      <div class="cover">
        <div class="container">
          <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3 content">
            <h3>重置密码</h3>
            <?php if(isset($user)):?>
            <p class="reset_password_text">您正在重置大思考账户<span class="username"><?php echo $user['name']?></span>的密码</p>
            <?php endif?>
            <!-- 有错误信息时展示 -->
            <?php if(isset($message)): ?>
            <div class="alert alert-danger">
              <strong>错误！</strong> <?php echo $message; ?>
            </div>
            <?php endif ?>
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" novalidate class="reset-password">
            <?php if(isset($user)):?>
              <input type="email" name="email" placeholder="请输入您的邮箱" class="hidden" value="<?php echo $user['email']?>"> 
            <?php endif?>
              <div class="form-group">
                <p class="name">新密码：</p>
                <div class="error_container">
                  <input type="password" name="password" placeholder="请输入您的新密码" class="form-password form-control">
                </div>
              </div>
              <div class="form-group">
                <p class="name">确认密码：</p>
                <div class="error_container">
                  <input type="password" name="repassword" placeholder="请再次输入以确认您的密码" class="form-repassword form-control">
                </div>
              </div>
              <input type="submit" class="btn" value="提交">
            </form>
            <?php if(isset($success_message)): ?>
            <div class="alert alert-success success">
              <?php echo $success_message; ?><strong><a href="/user/login.php"> 点此登录</a></strong> 
            </div>
            <?php endif ?>
            <p class="problem_text">若遇到问题，请邮件联系:<br />duskgirl@126.com</p>
          </div>
        </div>
      </div>
    </div>
    <!-- Javascript -->
    <script src="/lib/jquery/jquery.min.js"></script>
    <script src="/lib/bootstrap/js/bootstrap.min.js"></script>
    <!-- 轮播图插件 -->
    <!-- <script src="/lib/backstretch/jquery.backstretch.min.js"></script> -->
    <!--[if lt IE 10]>
    <script src="/lib/placeholder/jquery.placeholder.min.js"></script>
  <![endif]-->
  <script src="/lib/bootstrapvalidator/js/bootstrapValidator.min.js"></script>
  <script src="/user/js/reset_password.js"></script>
  </body>

  </html>