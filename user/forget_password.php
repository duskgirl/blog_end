<?php
// 载入配置文件
$root_path = $_SERVER['DOCUMENT_ROOT'];
require_once($root_path.'/functions.php');
// 校验邮箱唯一性
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  resetPassword();
}
function resetPassword(){
  if(empty($_POST['email'])){
    $GLOBALS['message'] = '请输入注册邮箱';
    return;
  }
  $email = $_POST['email'];
  // 要验证Email是否存在系统用户表中,如果有，
  // 则读取用户信息，将用户id、用户名和密码进行md5加密生成一个特别的字符串作为找回密码的验证码，然后构造URL;
  // 同时我们为了控制URL链接的时效性，将记录用户提交找回密码动作的操作时间，最后调用邮件发送类发送邮件到用户邮箱
  $sql = "select id,name,email,password from user where email = '{$email}' limit 1";
  $row = blog_select_one($sql);
  if(!$row) {
    $GLOBALS['message'] = '该邮箱尚未注册';
    return;
  }
  // 到该邮箱已注册，然后找回该邮箱的密码
  // 同时我们为了控制URL链接的时效性，将记录用户提交找回密码动作的操作时间，最后调用邮件发送类发送邮件到用户邮箱
  $id = $row['id'];
  $name = $row['name'];
  // 读取用户信息，将用户id、用户名和密码进行md5加密生成一个特别的字符串作为找回密码的验证码，然后构造URL;
  // 组合验证码，token:验证码
  $token = md5($id.$name.$row['password']);
  // 构造URL
  $url = ROOT."/user/reset_password.php?email={$email}&token={$token}";    
  // 更新数据发送时间,保证时效性
  $time_sql = "update user set modified_time = CURRENT_TIMESTAMP where id = {$id}";
  $time_res = blog_update($time_sql);
  if(!$time_res) {
    exit('数据更新失败');
  }
  // 收件人，标题，邮件内容
  $header = '大思考账户密码重置';
  $content = "您好，您正在重置大思考账户{$name}的密码<br /> 请点击下方链接修改密码:<br /><a href='{$url}'>{$url}<a><br />大思考";
  $result = sendmail($email,$header,$content);
  if($result == 1) {
    header('Location:/user/sendmail_success.php?resetpassword=1');
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
    <title>大思考-重置密码</title>
    <meta name="keywords" content="大思考,大思考博客,前端开发,前端开发博客" />
    <meta name="description" content="大思考博客是一个分享前端开发相关知识的博客网站" />
    <!-- CSS -->
    <link rel="stylesheet" href="/lib/font-awesome/css/font-awesome.min.css">
    <link href="/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/lib/bootstrapvalidator/css/bootstrapValidator.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/user/css/forget_password.css">
    <link rel="shortcut icon" href="favicon.ico">
    <!--[if lt IE 9]>
    <script src="/lib/html5shiv/html5shiv.min.js"></script>
    <script src="/lib/respond/respond.min.js"></script>
  <![endif]-->
  </head>
  <body>
    <div class="blog_forget_password">
      <div class="cover">
        <div class="container">
          <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3 content">
            <h3>重置密码</h3>
            <!-- 有错误信息时展示 -->
            <?php if (isset($message)): ?>
            <div class="alert alert-danger">
              <strong>错误！</strong> <?php echo $message; ?>
            </div>
            <?php endif ?>
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" class="forget_password" novalidate>
              <div class="form-group">
                <p class="name">邮&nbsp;&nbsp;箱：</p>
                <div class="error_container">
                  <input type="email" name="email" placeholder="请输入注册邮箱" class="form-email form-control">
                </div>
              </div>
              <input type="submit" value="提交" class="btn">
            </form>
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
  <script src="/user/js/forget_password.js"></script>
  </body>

  </html>