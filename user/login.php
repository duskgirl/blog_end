<?php
// 载入配置文件
$root_path = $_SERVER['DOCUMENT_ROOT'];
require_once($root_path.'/functions.php');
if(empty($_SESSION['url'])){
  $path = '/index.php';
} else {
  $path = $_SESSION['url'];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  login();
}
// 增加一个功能要是用户激活了才能登陆，否则不能登陆
function login () {
  // 1. 接收并校验
  // 2. 持久化
  // 3. 响应
  global $path;
  if (empty($_POST['username'])) {
    $GLOBALS['message'] = '请填写用户名';
    return;
  }
  if (empty($_POST['password'])) {
    $GLOBALS['message'] = '请填写密码';
    return;
  }
  $username = $_POST['username'];
  $password = $_POST['password'];
  $sql = "select id,email,name,password,avatar,userstats from user where name = '{$username}' limit 1";
  $user = blog_select_one($sql);
  if (!$user) {
    $GLOBALS['message'] = '当前用户尚未注册';
    return;
  }
  // 检测用户是否激活账户
  // 未激活就只有直接发送链接到页面去
  // 只要发送邮件都要更新时间，因为保证链接的有效期
  if($user['userstats'] != 1) {
    // 修改时间
    $modified_time_sql = "update user set modified_time=current_timestamp where name='{$username}'";
    $result = blog_update($modified_time_sql);
    // 更改时间失败
    if(!$result){
      exit('更新数据失败');
    }
    // 组合验证码，token:验证码(要包含用户状态)
    $email = $user['email'];
    $username = $user['name'];
    $password = $user['password'];
    $userstats = $user['userstats'];
    $token = md5($email.$username.$password.$userstats);
    // 构造URL让用户激活账户
    // 除了这个时候可以发送链接到用户邮箱，登录的时候也可以发送类似的链接到邮箱
    $url = ROOT."/user/activeacount.php?email={$email}&token={$token}";   
     // 收件人，标题，邮件内容
    $header = '大思考账户激活';
    $content = "您好，感谢您在大思考注册账户！<br /> 请点击下方链接进行激活账户:<br /><a href='{$url}'>{$url}<a><br />大思考";
    $result = sendmail($email,$header,$content);
    if($result == 1) {
      // 传递参数:表示该用户尚未激活
      // 数据库为1是表示已经激活，这里传递1只是为了方便服务端好接收数据，这里是表示未激活的
      header('Location:/user/sendmail_success.php?userstats=1');
    } else {
      $GLOBALS['message'] = $result;
    }
    $GLOBALS['message'] = '当前用户尚未激活,系统已重新将激活账户链接发送到您的邮箱,请及时登录您的邮箱激活账户!';
    return;
  }
  // 一般密码是加密存储的
  if ($user['password'] !== md5($password)) {
    // 密码不正确
    $GLOBALS['message'] = '用户名与密码不匹配';
    return;
  }
  // 为了后续可以直接获取当前登录用户的信息，这里直接将用户信息放到 session 中
  $_SESSION['current_login_user'] = $user;
  // 一切OK 可以跳转到原来的页面或者是首页
  header('Location: ' . $path);
}
// 退出登陆功能
if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'logout'){
  unset($_SESSION['current_login_user']);
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
    <title>大思考-登陆</title>
    <meta name="keywords" content="大思考,大思考博客,前端开发,前端开发博客" />
    <meta name="description" content="大思考博客是一个分享前端开发相关知识的博客网站" />
    <!-- CSS -->
    <link rel="stylesheet" href="/lib/font-awesome/css/font-awesome.min.css">
    <link href="/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/lib/bootstrapvalidator/css/bootstrapValidator.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/user/css/login.css">
    <link rel="shortcut icon" href="favicon.ico">
    <!--[if lt IE 9]>
    <script src="/lib/html5shiv/html5shiv.min.js"></script>
    <script src="/lib/respond/respond.min.js"></script>
  <![endif]-->
  </head>
  <body>
    <div class="blog_login">
      <div class="cover">
        <div class="container">
          <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3 content">
            <h3>大思考登陆</h3>
            <!-- 有错误信息时展示 -->
            <?php if (isset($message)): ?>
            <div class="alert alert-danger">
              <strong>错误！</strong> <?php echo $message; ?>
            </div>
            <?php endif ?>
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" class="login" novalidate>
              <div class="form-group">
                <p class="name">用户名：</p>
                <div class="error_container">
                  <input type="text" name="username" placeholder="请输入您的用户名" class="form-username form-control">
                </div>  
              </div>
              <div class="form-group">
                <p class="name">密&nbsp;&nbsp;码：</p>
                <div class="error_container">
                  <input type="password" name="password" placeholder="请输入您的密码" class="form-password form-control">
                </div>
              </div>
              <input type="submit" value="登录" class="btn">
            </form>
            <p class="clearfix">
              <span class="skip_register pull-left">没有账户,<a href="/user/register.php">立即注册<span class="fa fa-angle-double-right"></span></a></span>
              <span class="forget_password pull-right"><a href="/user/forget_password.php">忘记密码</a></span>
            </p>
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
  <script src="/user/js/login.js"></script>

  </body>

  </html>