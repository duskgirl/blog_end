<?php
$root_path = $_SERVER['DOCUMENT_ROOT'];
require_once($root_path.'/functions.php');
if($_SERVER['REQUEST_METHOD'] === 'GET'){
  blog_visit();
  blog_from();
  // 搜索
  $where = '1=1';
  $search = '';
  if(!empty($_GET['search'])){
    $search .= '&search='.$_GET['search'];
    $search_value = $_GET['search'];
    $search_value = trim($search_value);
    $search_value_num = mb_strlen($search_value);
    $search_result = '';
    for($i=0;$i<$search_value_num;$i++){
      $search_result .= mb_substr($search_value,$i,1) . '%'; 
    }
    $search_value = $search_result;
    $where .= " and header like '%{$search_value}%'";
  }
  if(!empty($_GET['category'])){
    $category = $_GET['category'];
    $where.=" and c.name = '{$category}'";
  }
  $total_sql = "select 
  count('total') as totalRow 
  from article as a 
  inner join category as c 
  on a.category_id = c.id 
  where {$where}";
  blog_get_page($total_sql);
  getIndex();
}
function getIndex(){
  // 连接数据库；
  // 查询数据；
  // 响应
  global $where;
  if($GLOBALS['total']>0){
    $skip = $GLOBALS['skip'];
    $per_list =  $GLOBALS['$per_list'];
    $sql = "select 
    a.id,
    c.name,
    header,
    pubtime,
    author,
    thumbnail,
    introduction,
    content,
    viewnumber 
    from article as a 
    inner join category as c 
    on a.category_id = c.id 
    where {$where} order by id desc limit {$skip},{$per_list}";
    $GLOBALS['result'] = blog_select_all($sql);
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
  <title>大思考首页</title>
  <meta name="keywords" content="大思考,大思考博客,前端开发,前端开发博客" />
  <meta name="description" content="大思考博客是一个分享前端开发相关知识的博客网站" />
  <link rel="stylesheet" href="/lib/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="/lib/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="/css/topbar.css">
  <link rel="stylesheet" href="/css/index.css">
  <link rel="stylesheet" href="/css/footer.css">
  <link rel="stylesheet" href="/css/sidebar.css">
  <link rel="stylesheet" href="/css/pagination.css">
  <link rel="stylesheet" href="/css/public.css">
</head>
<body>
  <?php include $root_path.'/static/topbar.php'?>
  <div id="carousel-example-generic" class="carousel slide banner" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
      <li data-target="#carousel-example-generic" data-slide-to="1"></li>
      <li data-target="#carousel-example-generic" data-slide-to="2"></li>
    </ol>
    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
      <div class="item active">
        <img src="/img/banner_1.jpg">
      </div>
      <div class="item">
        <img src="/img/banner_2.jpg">
      </div>
      <div class="item">
        <img src="/img/banner_3.jpg">
      </div>
    </div>
    <!-- Controls -->
    <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
  <main class="blog_main container">
    <section class="mainbar">
      <?php if(!empty($result)): ?>
      <?php foreach($result as $key => $item):?>
      <div class="article_introduction">
        <!-- 缩略图 -->
        <div class="thumbnail" style="background-image:url(<?php echo $item['thumbnail']?>)"></div>
        <div class="right">
          <a class="header ellipsis" href="<?php echo $item['content']?>?id=<?php echo $item['id']?>">
            <?php echo $item['header']?>
          </a>
          <p class="article_detail ellipsis">
            <span class="fa fa-calendar-check-o"></span><span> <?php echo $item['pubtime']?> </span>
            <span class="fa fa-user-o"></span><span> <?php echo $item['author']?> </span>
            <span class="fa fa-folder-open-o "></span><span> <?php echo $item['name']?> </span>
            <span class="fa fa-eye"></span><span> <?php echo $item['viewnumber']?> </span>
          </p>
          <p class="introduction hidden-xs ellipsis"><?php echo $item['introduction']?></p>
        </div>
      </div>
      <?php endforeach ?>
      <?php else:?>
      <div>
      <h4 class="nofound">抱歉！没有找到相关文章！</h4>
      </div>
      <?php endif?>
      <?php include $root_path.'/static/pagination.php'?>
    </section>
    <?php include $root_path.'/static/sidebar.php'?>
  </main>
  <?php include $root_path.'/static/footer.php'?>
  <script src="/lib/jquery/jquery.min.js"></script>
  <script src="/lib/bootstrap/js/bootstrap.min.js"></script>
  <script src="/js/topbar.js"></script>
</body>
</html>