<?php
$root_path = $_SERVER['DOCUMENT_ROOT'];
require_once($root_path.'/functions.php');
$user = blog_get_current_user();
if($_SERVER['REQUEST_METHOD'] === 'GET'){
  // 必须先注册登陆才能进去
  if($user == null){
    $_SESSION['url'] = '/person_set.php';
    header('Location: /user/login.php');
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
  <title>大思考-个人中心</title>
  <meta name="keywords" content="大思考,大思考博客,前端开发,前端开发博客" />
  <meta name="description" content="大思考博客是一个分享前端开发相关知识的博客网站" />
  <link rel="stylesheet" href="/lib/bootstrap/css/bootstrap.min.css">
  <link href="/lib/bootstrapvalidator/css/bootstrapValidator.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/lib/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="/css/topbar.css">
  <link rel="stylesheet" href="/css/sidebar.css">
  <link rel="stylesheet" href="/css/footer.css">
  <link rel="stylesheet" href="/css/person_nav.css">
  <link rel="stylesheet" href="/css/person_set.css">
  <link rel="stylesheet" href="/css/public.css">
</head>
<body>
<?php include $root_path.'/static/topbar.php'?>
  <main class="blog_main container">
    <section class="mainbar">
    <?php $current_nav = 'person_set'?>
    <?php include $root_path.'/static/person_nav.php'?>
      <div class="person_detail" id="person">        
        <div class="person_set">
          <h4 class="hidden-xs underline">基本资料</h4>
          <form class="modify">
            <div class="form-group">
              <p class="text">注册邮箱:</p> 
              <input type="email" class="form-control email" disabled name="email" value="<?php echo $user['email']?>">
            </div>
            <div class="form-group">
              <p class="text">用户名 *</p>
              <div class="err_container">
                <input type="text" class="form-control username" name="username" value="<?php echo $user['name']?>">
              </div>
            </div>
            <div class="form-group">
              <p class="text">密码 *</p>
              <div class="err_container">
                <input type="password" class="form-control password" name="password" /> 
              </div>
            </div>
            <div class="form-group">
              <p class="text">确认密码 *</p>
              <div class="err_container">
                <input type="password" class="form-control repassword" name="repassword" />
              </div>
            </div>
            <div class="form-group avatar">
              <p class="text">选择您的头像 *</span></p>
              <div id="wrapper">
                <div id="scroller">
                  <ul>
                    <li><input type="radio" name="avatar" class="selected" value="/img/avatar/avatar.jpg" /> <img class="lazy" data-src="/img/avatar/avatar.jpg" src="/img/loading.gif"></li>
                    <li><input type="radio" name="avatar" class="selected" value="/img/avatar/avatar1.jpg" /><img class="lazy" data-src="/img/avatar/avatar1.jpg" src="/img/loading.gif"></li>
                    <li><input type="radio" name="avatar" class="selected" value="/img/avatar/avatar2.jpg" /><img class="lazy" data-src="/img/avatar/avatar2.jpg" src="/img/loading.gif"></li>
                    <li><input type="radio" name="avatar" class="selected" value="/img/avatar/avatar3.jpg" /><img class="lazy" data-src="/img/avatar/avatar3.jpg" src="/img/loading.gif"></li>
                    <li><input type="radio" name="avatar" class="selected" value="/img/avatar/avatar4.jpg" /><img class="lazy" data-src="/img/avatar/avatar4.jpg" src="/img/loading.gif"></li>
                    <li><input type="radio" name="avatar" class="selected" value="/img/avatar/avatar5.jpg" /><img class="lazy" data-src="/img/avatar/avatar5.jpg" src="/img/loading.gif"></li>
                    <li><input type="radio" name="avatar" class="selected" value="/img/avatar/avatar6.jpg" /><img class="lazy" data-src="/img/avatar/avatar6.jpg" src="/img/loading.gif"></li>
                    <li><input type="radio" name="avatar" class="selected" value="/img/avatar/avatar7.jpg" /><img class="lazy" data-src="/img/avatar/avatar7.jpg" src="/img/loading.gif"></li>
                    <li><input type="radio" name="avatar" class="selected" value="/img/avatar/avatar8.jpg" /><img class="lazy" data-src="/img/avatar/avatar8.jpg" src="/img/loading.gif"></li>
                    <li><input type="radio" name="avatar" class="selected" value="/img/avatar/avatar9.jpg" /><img class="lazy" data-src="/img/avatar/avatar9.jpg" src="/img/loading.gif"></li>
                    <li><input type="radio" name="avatar" class="selected" value="/img/avatar/avatar10.jpg" /><img class="lazy" data-src="/img/avatar/avatar10.jpg" src="/img/loading.gif"></li>
                    <li><input type="radio" name="avatar" class="selected" value="/img/avatar/avatar11.jpg" /><img class="lazy" data-src="/img/avatar/avatar11.jpg" src="/img/loading.gif"></li>
                    <li><input type="radio" name="avatar" class="selected" value="/img/avatar/avatar12.jpg" /><img class="lazy" data-src="/img/avatar/avatar12.jpg" src="/img/loading.gif"></li>
                    <li><input type="radio" name="avatar" class="selected" value="/img/avatar/avatar13.jpg" /><img class="lazy" data-src="/img/avatar/avatar13.jpg" src="/img/loading.gif"></li>
                    <li><input type="radio" name="avatar" class="selected" value="/img/avatar/avatar14.jpg" /><img class="lazy" data-src="/img/avatar/avatar14.jpg" src="/img/loading.gif"></li>
                    <li><input type="radio" name="avatar" class="selected" value="/img/avatar/avatar15.jpg" /><img class="lazy" data-src="/img/avatar/avatar15.jpg" src="/img/loading.gif"></li>
                    <li><input type="radio" name="avatar" class="selected" value="/img/avatar/avatar16.jpg" /><img class="lazy" data-src="/img/avatar/avatar16.jpg" src="/img/loading.gif"></li>
                    <li><input type="radio" name="avatar" class="selected" value="/img/avatar/avatar17.jpg" /><img class="lazy" data-src="/img/avatar/avatar17.jpg" src="/img/loading.gif"></li>
                    <li><input type="radio" name="avatar" class="selected" value="/img/avatar/avatar18.jpg" /><img class="lazy" data-src="/img/avatar/avatar18.jpg" src="/img/loading.gif"></li>
                    <li><input type="radio" name="avatar" class="selected" value="/img/avatar/avatar19.jpg" /><img class="lazy" data-src="/img/avatar/avatar19.jpg" src="/img/loading.gif"></li>
                    <li><input type="radio" name="avatar" class="selected" value="/img/avatar/avatar20.jpg" /><img class="lazy" data-src="/img/avatar/avatar20.jpg" src="/img/loading.gif"></li>
                    <li><input type="radio" name="avatar" class="selected" value="/img/avatar/avatar21.jpg" /><img class="lazy" data-src="/img/avatar/avatar21.jpg" src="/img/loading.gif"></li>
                    <li><input type="radio" name="avatar" class="selected" value="/img/avatar/avatar22.jpg" /><img class="lazy" data-src="/img/avatar/avatar22.jpg" src="/img/loading.gif"></li>
                    <li><input type="radio" name="avatar" class="selected" value="/img/avatar/avatar23.jpg" /><img class="lazy" data-src="/img/avatar/avatar23.jpg" src="/img/loading.gif"></li>
                    <li><input type="radio" name="avatar" class="selected" value="/img/avatar/avatar24.jpg" /><img class="lazy" data-src="/img/avatar/avatar24.jpg" src="/img/loading.gif"></li>
                    <li><input type="radio" name="avatar" class="selected" value="/img/avatar/avatar25.jpg" /><img class="lazy" data-src="/img/avatar/avatar25.jpg" src="/img/loading.gif"></li>
                    <li><input type="radio" name="avatar" class="selected" value="/img/avatar/avatar26.jpg" /><img class="lazy" data-src="/img/avatar/avatar26.jpg" src="/img/loading.gif"></li>
                    <li><input type="radio" name="avatar" class="selected" value="/img/avatar/avatar27.jpg" /><img class="lazy" data-src="/img/avatar/avatar27.jpg" src="/img/loading.gif"></li>
                    <li><input type="radio" name="avatar" class="selected" value="/img/avatar/avatar28.jpg" /><img class="lazy" data-src="/img/avatar/avatar28.jpg" src="/img/loading.gif"></li>
                    <li><input type="radio" name="avatar" class="selected" value="/img/avatar/avatar29.jpg" /><img class="lazy" data-src="/img/avatar/avatar29.jpg" src="/img/loading.gif"></li>
                    <li><input type="radio" name="avatar" class="selected" value="/img/avatar/avatar30.jpg" /><img class="lazy" data-src="/img/avatar/avatar30.jpg" src="/img/loading.gif"></li>
                    <li><input type="radio" name="avatar" class="selected" value="/img/avatar/avatar31.jpg" /><img class="lazy" data-src="/img/avatar/avatar31.jpg" src="/img/loading.gif"></li>
                    <li><input type="radio" name="avatar" class="selected" value="/img/avatar/avatar32.jpg" /><img class="lazy" data-src="/img/avatar/avatar32.jpg" src="/img/loading.gif"></li>
                  </ul> 
                </div>
              </div>
            </div>
            <input type="submit" value="修改基本资料" class="btn modify_btn" />
          </form>
        </div>
      </div>
    </section>
  </main>
  <?php include $root_path.'/static/footer.php'?>
  <script src="/lib/jquery/jquery.min.js"></script>
  <script src="/lib/lazyload/lazyload.min.js"></script>
  <script src="/lib/bootstrap/js/bootstrap.min.js"></script>
  <script src="/lib/bootstrapvalidator/js/bootstrapValidator.min.js"></script>
  <script src="/lib/iscroll/iscroll-probe.js"></script>
  <script src="/lib/artDialog-master/dialog.js"></script>
  <script src="/js/person.js"></script>
  <script src="/js/topbar.js"></script>
  <script src="/js/person_set.js"></script>
  <script>
    $(function(){
      // 设置用户头像被选中
      var avatar_old = "<?php echo $user['avatar']?>";
      $('.selected').each(function(){
        if($(this).next().attr('data-src') == avatar_old) {
          $(this).prop('checked',true);
        }
      })
      // 点击修改提交，发送ajax请求
      $('.modify_btn').on('click', function() {
        // 用户邮箱必须校验；
        // 用户名
        // 密码
        // 确认密码
        // 头像
        // 保证用户不能修改邮箱
        event.preventDefault();
        var email = $('.email').val();
        var email_init = '<?php echo $user['email']?>';
        // 修改了用户邮箱
        if(email != email_init){
          warn('警告','暂不支持修改用户邮箱!');
          return;
        }
        var username = $('.username').val();
        var password = $('.password').val();
        var repassword = $('.repassword').val();
        if(password != repassword){
          warn('警告','两次密码输入不一致!');
          return;
        }
        var avatar = $('input:radio[name="avatar"]:checked').next().attr('data-src');
        if(username.length<1 || password.length<1 || avatar.length <1) {
          warn('警告','带星号的选项均为必填选项!');
          return;
        }
        $.ajax({
          url: '/api/information_modify.php',
          type: 'POST',
          dataType: 'json',
          data : {
            email: email,
            name: username,
            password: password,
            repassword: repassword,
            avatar: avatar
          },
          success: function(data){
            if(data.save){
              warn('恭喜',data.message);
            } else {
              warn('警告',data.message);
            }
          }
        })
      });
      function warn(title,content){
        var d = dialog({
    	    title: title,
          content: content,
          cancel: false,
	        ok: function () {},
          quickClose: true
        });
        d.show(document.getElementById('option-quickClose'));
        setTimeout(function () {
        	d.close().remove();
        }, 5000);
      }
    })
  </script>
</body>

</html>