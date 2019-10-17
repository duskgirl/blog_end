  <?php
  if($_SERVER['REQUEST_METHOD'] === 'GET') {
    getValue();
  }
  function getValue(){
    if(empty($_GET['register']) && empty($_GET['resetpassword']) && empty($_GET['time_out']) && empty($_GET['userstats'])){
      exit('缺少必要的参数');
    }
    if(isset($_GET['register']) || isset($_GET['time_out']) || isset($_GET['userstats'])){
      if(isset($_GET['register']) && $_GET['register'] == 1 || isset($_GET['time_out']) && $_GET['time_out'] == 1 || isset($_GET['userstats']) && $_GET['userstats'] == 1){
        $GLOBALS['active'] = "激活账户";
        return;
      }else {
        exit('参数错误');
      }
    }
    if(isset($_GET['resetpassword'])){
      if($_GET['resetpassword'] == 1) {
        $GLOBALS['resetpassword'] = "重置密码";
        return;
      } else {
        exit('参数错误');
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
    <title>大思考-邮件发送</title>
    <meta name="keywords" content="大思考,大思考博客,前端开发,前端开发博客" />
    <meta name="description" content="大思考博客是一个分享前端开发相关知识的博客网站" />
    <!-- CSS -->
    <link rel="stylesheet" href="/lib/font-awesome/css/font-awesome.min.css">
    <link href="/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/user/css/sendmail_success.css" rel="stylesheet">
    <link rel="shortcut icon" href="favicon.ico">
    <!--[if lt IE 9]>
    <script src="/lib/html5shiv/html5shiv.min.js"></script>
    <script src="/lib/respond/respond.min.js"></script>
  <![endif]-->
  </head>
  <body>
    <div class="blog_sendmail_success">
      <div class="cover">
        <div class="container">
          <p class="bggreen"><span class="fa fa-check-circle"></span></p>
          <?php if(isset($active)):?>
          <p class="green"><?php echo $active?></p>
          <?php if(isset($_GET['register'])):?>
          <p class="text">系统已将激活账户链接发送到您的邮箱,请及时登录您的邮箱激活账户!</p>
          <?php elseif(isset($_GET['time_out'])):?>
          <p class="text"><span class="red">该激活链接已过期,</span>系统已重新将激活账户链接发送至您的邮箱,请及时登录您的邮箱激活账户!</p>
          <?php elseif(isset($_GET['userstats'])):?>
          <p class="text"><span class="red">当前用户尚未激活,</span>系统已重新将激活账户链接发送至您的邮箱,请及时登录您的邮箱激活账户后再重新登录！</p>
          <?php endif?>
          <?php endif?>
          <?php if(isset($resetpassword)):?>
          <p class="green"><?php echo $resetpassword?></p>
          <p class="text">系统已将密码重置链接发送到您的邮箱,请及时登录您的邮箱重置密码</p>
          <?php endif?>
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
  </body>
  </html>