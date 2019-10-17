<?php
$root_path = $_SERVER['DOCUMENT_ROOT'];
require_once($root_path.'/functions.php');
if($_SERVER['REQUEST_METHOD'] === 'GET'){
  // 这样可以获取网页的来源地址
  blog_from();
  getviewnumber();
}
function getArticle(){
  if(empty($_GET['id'])){
    exit('缺少必要参数');
  }
  $id = $_GET['id'];
  $current_sql = "select 
  a.id,
  a.header,
  a.author,
  a.keywords,
  a.pubtime,
  a.content,
  a.viewnumber,
  c.name 
  from article as a 
  inner join category as c 
  on a.category_id = c.id 
  where a.id={$id} limit 1";
  $GLOBALS['current_row'] = blog_select_one($current_sql);
  // 获取上一条
  $prev_sql = "select 
  id,header,content 
  from article 
  where id=(select min(id) from article where id>{$id})";
  $GLOBALS['prev_row'] = blog_select_one($prev_sql);
  // 获取下一条
  $next_sql = "select 
  id,header,content 
  from article 
  where id=(select max(id) from article where id<{$id})";
  $GLOBALS['next_row'] = blog_select_one($next_sql);
}
function getviewnumber(){
  if(empty($_GET['id'])){
    exit('缺少必要参数');
  }
  $id = $_GET['id'];
  $sql = "select viewnumber from article where id={$id} limit 1";
  $viewnumber = blog_select_one($sql)['viewnumber'];
  getArticle();
  $viewnumber += 1;
  $sql_add = "update article set viewnumber = {$viewnumber} where id={$id}";
  $add_result = blog_update($sql_add);
  if($add_result<=0){
    $GLOBALS['err_message'] = '更新查看次数数据失败';
  }
}
?>
<!DOCTYPE html>
<html lang="en">
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
  <meta name="description" content="大思考博客是一个分享前端开发相关知识的博客网站" />
  <meta name="keywords" content="<?php echo $current_row['keywords']?>">
  <title><?php echo $current_row['header'] ?></title>
  <link rel="stylesheet" href="/lib/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="/lib/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="/css/topbar.css">
  <link rel="stylesheet" href="/css/sidebar.css">
  <link rel="stylesheet" href="/css/footer.css">
  <link rel="stylesheet" href="/article/css/article.css">
  <link rel="stylesheet" href="/article/css/comment.css">
  <link rel="stylesheet" href="/css/public.css">
</head>
<body>
<?php include $root_path.'/static/topbar.php'?>
  <main class="blog_main container">
    <section class="mainbar">
      <div class="content">
        <h3><?php echo $current_row['header']?></h3>
        <p class="time_author ellipsis">
          <span class="fa fa-calendar-check-o"></span><span> <?php echo $current_row['pubtime']?> </span>
          <span class="fa fa-user-o"></span><span> <?php echo $current_row['author']?> </span>
          <span class="fa fa-folder-open-o"></span><span> <?php echo $current_row['name']?> </span>
          <span class="fa fa-eye"></span><span> <?php echo $current_row['viewnumber']?> </span>
        </p>