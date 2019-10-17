<?php
// 载入配置文件
$root_path = $_SERVER['DOCUMENT_ROOT'];
require_once($root_path.'/functions.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  register();
}
function register () {
  // 1. 接收并校验
  // 2. 持久化
  // 3. 响应
  global $root_path;
  if (empty($_POST['email'])) {
    $GLOBALS['message'] = '请输入您的邮箱';
    return;
  }
  if (empty($_POST['username'])) {
    $GLOBALS['message'] = '请输入您的用户名';
    return;
  }
  if (empty($_POST['password'])) {
    $GLOBALS['message'] = '请输入您的密码';
    return;
  }
  if (empty($_POST['repassword'])) {
    $GLOBALS['message'] = '请输入您的确认密码';
    return;
  }
  if($_POST['password'] !== $_POST['repassword']) {
    $GLOBALS['message'] = '两次输入的密码不一致';
    return;
  }
  $email = $_POST['email'];
  $username = $_POST['username'];
  $password = md5($_POST['password']);
  $sql = "insert into user (email,name,password) values ('{$email}','{$username}','{$password}')";
  $insert_result = blog_update($sql);
  if (!$insert_result) {
    $GLOBALS['message'] = '注册失败，请重试！';
    return;
  }
  // 组合验证码，token:验证码
  $userstats = 0;
  $token = md5($email.$username.$password.$userstats);
  // 构造URL让用户激活账户
  // 除了这个时候可以发送链接到用户邮箱，登录的时候也可以发送类似的链接到邮箱
  $url = ROOT."/user/activeacount.php?email={$email}&token={$token}";    
   // 收件人，标题，邮件内容
   $header = '大思考账户激活';
   $content = "您好，感谢您在大思考注册账户！<br /> 请点击下方链接进行激活账户:<br /><a href='{$url}'>{$url}<a><br />大思考";
   $result = sendmail($email,$header,$content);
   if($result == 1) {
     header('Location:/user/sendmail_success.php?register=1');
   } else {
     $GLOBALS['message'] = $result;
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
    <title>大思考-注册</title>
    <meta name="keywords" content="大思考,大思考博客,前端开发,前端开发博客" />
    <meta name="description" content="大思考博客是一个分享前端开发相关知识的博客网站" />
    <!-- CSS -->
    <link rel="stylesheet" href="/lib/font-awesome/css/font-awesome.min.css">
    <link href="/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/lib/bootstrapvalidator/css/bootstrapValidator.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/user/css/register.css">
    <link rel="shortcut icon" href="favicon.ico">
    <!--[if lt IE 9]>
    <script src="/lib/html5shiv/html5shiv.min.js"></script>
    <script src="/lib/respond/respond.min.js"></script>
  <![endif]-->
  </head>
  <body>
    <div class="blog_register">
      <div class="cover">
        <div class="container">
          <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3 content">
            <h3>大思考注册</h3>
            <!-- 有错误信息时展示 -->
            <?php if(isset($message)): ?>
            <div class="alert alert-danger">
              <strong>错误！</strong> <?php echo $message; ?>
            </div>
            <?php endif ?>
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" novalidate class="register">
              <div class="form-group">
                <p class="name">邮箱：</p>
                <div class="error_container">
                  <input type="email" name="email" placeholder="需要通过邮箱激活账户" class="form-email form-control">
                </div>
              </div>
              <div class="form-group">
                <p class="name">用户名：</p>
                <div class="error_container">
                  <input type="text" name="username" placeholder="请输入您的用户名" class="form-username form-control">
                </div>
              </div>
              <div class="form-group">
                <p class="name">密码：</p>
                <div class="error_container">
                  <input type="password" name="password" placeholder="请输入您的密码" class="form-password form-control">
                </div>
              </div>
              <div class="form-group">
                <p class="name">确认密码：</p>
                <div class="error_container">
                  <input type="password" name="repassword" placeholder="请输入确认密码" class="form-repassword form-control">
                </div>
              </div>
              <input type="submit" class="btn" value="注册">
            </form>
            <?php if(isset($success_message)): ?>
            <div class="alert alert-success success">
              <?php echo $success_message; ?><strong><a href="/user/login.php"> 点此登录</a></strong> 
            </div>
            <?php endif ?>
            <p class="skip_login">已有账户,<a href="/user/login.php">直接登录<span class="fa fa-angle-double-right"></span></a></p>
          </div>
        </div>
      </div>
    </div>
    <!-- Javascript -->
    <script src="/lib/jquery/jquery.min.js"></script>
    <script src="../lib/bootstrap/js/bootstrap.min.js"></script>
    <!-- 轮播图插件 -->
    <!-- <script src="/lib/backstretch/jquery.backstretch.min.js"></script> -->
    <!--[if lt IE 10]>
    <script src="/lib/placeholder/jquery.placeholder.min.js"></script>
  <![endif]-->

  <!-- <script src="./js/register.js"></script> -->
  <script src="/lib/bootstrapvalidator/js/bootstrapValidator.min.js"></script>
  <script src="/user/js/register.js"></script>
  </body>

  </html>