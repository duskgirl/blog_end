<?php
$root_path = $_SERVER['DOCUMENT_ROOT'];
require_once($root_path.'/admin/functions.php');
blog_get_admin_user();
// 设置时间
date_default_timezone_set('PRC');
if($_SERVER['REQUEST_METHOD'] === 'GET') {
  total();
}

function total(){
  // 研究下这里做什么图表好看,以及后台常用什么图表
  // http://www.cssmoban.com/cssthemes/houtaimoban/(后台模板网站)
  // 一般就是统计最近一周新注册用户
  //获取待审核评论数
  $comment_sql = 'select 
  count(audit_status) as wait_check 
  from comment where 
  audit_status = 0' ;
  $result_comment = blog_select_all($comment_sql);
  // 获取新注册用户
  $user_sql = 'select 
  count(userstats) as new_register
  from user where
  userstats = 1 and DATE_SUB(CURDATE(), INTERVAL 6 DAY) <= created';
  $result_user = blog_select_all($user_sql); 
  foreach($result_user as $key => $item){
    if(isset($item['wait_check'])){
      $GLOBALS['result'][] = $item;
    }
    if(isset($item['new_register'])){
      $GLOBALS['result'][] = $item;
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
  <title>大思考-后台首页</title>
  <meta name="keywords" content="大思考,大思考博客,前端开发,前端开发博客" />
  <meta name="description" content="大思考博客是一个分享前端开发相关知识的博客网站" />
  <link rel="stylesheet" href="/admin/lib/bootstrap/css/bootstrap.min.css" />
  <link rel="stylesheet" href="/admin/lib/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="/admin/css/topbar.css">
  <link rel="stylesheet" href="/admin/css/sidebar.css">
  <link rel="stylesheet" href="/admin/css/index.css">
</head>

<body>
  <!-- 顶部通栏 bolg的logo+后台管理系统左侧 右侧搜索框倒三角符号显示登陆者账户名以及退出 -->
  <div class="container-fluid">
    <?php include $root_path.'/admin/static/topbar.php'?>
    <div class="blog_admin_main">
      <!-- 以下是左边侧栏 -->
      <?php $current_nav='index';?>
      <?php include $root_path.'/admin/static/sidebar.php'?>
      <section class="blog_admin_center">
        <!-- 区域滚动 -->
        <div id="wrapper">
          <div id="scroller">
            <ul>
              <h3>站点内容统计</h3>
              <?php if(isset($result)):?>
              <?php foreach($result as $key => $item):?>
              <?php if(isset($item['wait_check'])):?>
              <li class="comment pull-left">
                <a href="/admin/comment.php?audit_status=0">
                <span class="fa fa-comment-o"></span>待审核评论：
                <span class="wait_check"><?php echo $item['wait_check']?></span>
                </a>
              </li>
              <?php endif?>
              <?php if(isset($item['new_register'])):?>
              <li class="user pull-left">
                <a href="/admin/user.php?new_register=week">
                  <span class="fa fa-user-plus"></span>新注册用户：
                  <span class="new_user"><?php echo $item['new_register']?></span>
                </a>
              </li>
              <?php endif?>
              <?php endforeach?>
              <?php endif?>
              <!-- php获取当前时间，显示时间即可 -->
              <li class="current_time pull-left">当前时间：<?php echo date('Y/m/d');?></li>
              <li>
                <!-- 放置网站浏览量 -->
                <!-- 使用echarts -->
                <div id="bar" class="clearfix"></div>
              </li>
            </ul>
          </div>
        </div>
      </section>
    </div>
  </div>
  <script src="/admin/lib/jquery/jquery.min.js"></script>
  <script src="/admin/lib/bootstrap/js/bootstrap.min.js"></script>
  
  <script src="/admin/lib/iscroll/iscroll-probe.js"></script>
  <!-- 图表 -->
  <script src="/admin/lib/echarts/dist/echarts.min.js"></script>
  <script src="/admin/js/index.js"></script>
</body>

</html>