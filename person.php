<?php
 $root_path = $_SERVER['DOCUMENT_ROOT'];
 require_once($root_path.'/functions.php');
$user = blog_get_current_user();
if($_SERVER['REQUEST_METHOD'] === 'GET'){
  // 必须先注册登陆才能进去
  if($user !== null){
    $user_id = $user['id'];
  } else {
    $_SESSION['url'] = '/person.php';
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
  <link rel="stylesheet" href="/lib/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="/css/topbar.css">
  <link rel="stylesheet" href="/css/sidebar.css">
  <link rel="stylesheet" href="/css/footer.css">
  <link rel="stylesheet" href="/css/person_nav.css">
  <link rel="stylesheet" href="/css/person.css">
  <link rel="stylesheet" href="/css/public.css">
</head>
<body>
  <?php include $root_path.'/static/topbar.php'?>
  <main class="blog_main container">
    <section class="mainbar">
    <?php $current_nav = 'person'?>
      <?php include $root_path.'/static/person_nav.php'?>
      <div class="person_detail" id="person">        
        <!-- 我的评论页面 -->
        <div class="person_comment">
          <h4 class="hidden-xs underline">我的评论</h4>
        </div>
      </div>
    </section>
  </main>
  <?php include $root_path.'/static/footer.php'?>
  <script src="/lib/jquery/jquery.min.js"></script>
  <script src="/lib/bootstrap/js/bootstrap.min.js"></script>
  <script src="/lib/artDialog-master/dialog.js"></script>
  <script src="/lib/art-template/template-web.js"></script>
  <script src="/js/topbar.js"></script>
  <script type="text/x-art-template" id="default_comment">
  {{if(total_num>0)}}
    {{each data item index}}
    <div class="comment_block">
      {{if(item['parent_id'] == null)}}
      <p class="comment_content">发表评论：<span>{{item['comment_content']}}</span></p>
      <p class="comment_link">
        <a href="{{item['article_path']}}?id={{item['id']}}"><span class="fa fa-link"></span>{{item['header']}}</a>
      </p>
      {{else}}
      <p class="comment_content">
        回复 <span>{{item['parent_name']}}</span>：
        <span class="content">{{item['comment_content']}}</span>
      </p>
      <div class="comment_link">
        <p><span>{{item['parent_name']}}：</span>{{item['parent_content']}}</p>
        <a href="{{item['article_path']}}?id={{item['id']}}"><span class="fa fa-link"></span>{{item['header']}}</a>
      </div>
      {{/if}}
      <p class="comment_interaction">
        <span class="fa fa-thumbs-o-up"></span><span>{{item['love']}}</span>
        <span class="fa fa-commenting-o"></span><span>{{item['children_love']}}</span>
        <span class="comment_time">{{item['comment_time']}}</span>
      </p>
    </div>
    {{/each}}
    {{if(total_num>2)}}
    <a href="javascript:;" class="more_comment">查看更多评论</a>
    {{/if}}
    {{else}}
      <p class="no_comment">您当前还未发表过评论！</p>
    {{/if}}
  </script>
  <script type="text/x-art-template" id="look_comment">
  {{if(length>0)}}
    {{each data item index}}
    <div class="comment_block">
      {{if(item['parent_id'] == null)}}
      <p class="comment_content">发表评论：<span>{{item['comment_content']}}</span></p>
      <p class="comment_link">
        <a href="{{item['article_path']}}?id={{item['id']}}"><span class="fa fa-link"></span>{{item['header']}}</a>
      </p>
      {{else}}
      <p class="comment_content">
        回复 <span>{{item['parent_name']}}</span>：
        <span class="content">{{item['comment_content']}}</span>
      </p>
      <div class="comment_link">
        <p><span>{{item['parent_name']}}：</span>{{item['parent_content']}}</p>
        <a href="{{item['article_path']}}?id={{item['id']}}"><span class="fa fa-link"></span>{{item['header']}}</a>
      </div>
      {{/if}}
      <p class="comment_interaction">
        <span class="fa fa-thumbs-o-up"></span><span>{{item['love']}}</span>
        <span class="fa fa-commenting-o"></span><span>{{item['children_love']}}</span>
        <span class="comment_time">{{item['comment_time']}}</span>
      </p>
    </div>
    {{/each}}
    {{if(length == 10)}}
    <a href="javascript:;" class="more_comment">查看更多评论</a>
    {{else}}
    <p class="no_comment">没有更多评论了！</p>
    {{/if}}
    {{else}}
      <p class="no_comment">没有更多评论了！</p>
    {{/if}}
  </script>
  <script>
    $(function(){
      var user_id = <?php echo !empty($user_id) ? $user_id : null ?>;
      var page = 1;
      $.ajax({
        url: '/api/person_comment.php',
        data: {
          page: page,
          user_id: user_id,
        },
        dataType: 'json',
        type: 'POST',
        success: function(data){
          if(data.success != true){
            dialog('警告',data.message);
          } else {
            var html = template('default_comment',{
              data: data.message.finish,
              total_num: data.message.person_total
            });
            $('.person_comment').append(html);
          }
          
        }
      })
      $('.person_detail').on('click','.more_comment',function(){
        $(this).hide();
        page++;
        event.preventDefault();
        $.ajax({
          url: '/api/person_comment.php',
          data: {
            page: page,
            user_id: user_id,
          },
          dataType: 'json',
          type: 'POST',
          success: function(data){
            if(data.success != true){
              dialog('警告',data.message);
            } else {
              var html = template('look_comment',{
                data: data.message.finish,
                length: data.message.finish.length
              });
              $('.person_comment').append(html);
            }
            
          }
        })
      })
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