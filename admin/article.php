<?PHP
$root_path = $_SERVER['DOCUMENT_ROOT'];
require_once($root_path.'/admin/functions.php');
blog_get_admin_user();
// 获取文章分类
if($_SERVER['REQUEST_METHOD'] === 'GET') {
  getCategory();
}
function getCategory(){
  $sql = 'select id,name from category';
  blog_select_all($sql);
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
  getArticle();
}
function getArticle(){
  if(is_admin()){
    if(!$_POST['header']) {
      $GLOBALS['err_message'] = '请输入文章标题';
      return;
    }
    if(!$_POST['indroduction']) {
      $GLOBALS['err_message'] = '请输入文章简介';
      return;
    }
    if(!$_POST['keywords']) {
      $GLOBALS['err_message'] = '请输入文章关键词';
      return;
    }
    if(!$_POST['category']) {
      $GLOBALS['err_message'] = '请选择文章分类';
      return;
    }
    if(!$_POST['thumbnail']) {
      $GLOBALS['err_message'] = '请输入文章缩略图';
      return;
    }
    if(!$_POST['content']) {
      $GLOBALS['err_message'] = '请输入文章内容';
      return;
    }
    // 接收并校验到了数据
  $header = $_POST['header'];
  $introduction = $_POST['indroduction'];
  $keywords = $_POST['keywords'];
  $category = (int)$_POST['category'];
  $thumbnail = $_POST['thumbnail'];
  $content = $_POST['content'];

  // 持久化数据
  // 获取当前时间戳为文件名
  date_default_timezone_set('PRC');
  $time = time();
  $path = $_SERVER['DOCUMENT_ROOT'].'/article/';
  $path = $path.$time.'.php';
  $top = file_get_contents('article_top.php');
  $bottom = file_get_contents('article_bottom.php');
  $data = $top.$content.$bottom;
  file_put_contents($path,$data);
  // 数据库保存的路径是网址的根路径下的地址
  $content_path = '/article/'.$time.'.php';
  // 根据首页需要文章的标题($header),发表时间,作者名称，分类名称,缩略图,简介
  // 文章页面需求：标题,发表时间,作者名称，分类名称，关键词
  // 文章页头需求：页头的title,页头的关键词(就直接利用文章本身的标题和文章本身的关键词)

  // 连接数据库
  $sql = "insert into article (header,thumbnail,introduction,keywords,content,category_id) values ('{$header}','{$thumbnail}','{$introduction}','{$keywords}','{$content_path}',{$category})";
  $result = blog_update($sql);
  if(!$result){
    $GLOBALS['err_message'] = '保存到数据库失败';
    return;
  }
  $GLOBALS['success_message'] = '文章添加成功';
  // 获取数据执行结果 
  // 响应跳转
  header('location:/admin/article_mana.php');
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
  <title>大思考-后台添加文章</title>
  <meta name="keywords" content="大思考,大思考博客,前端开发,前端开发博客" />
  <meta name="description" content="大思考博客是一个分享前端开发相关知识的博客网站" />
  <link rel="stylesheet" href="/admin/lib/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="/admin/lib/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="/admin/css/article.css">


</head>

<body>
  <div class="container-fluid blog_article_add">
    <?php if(isset($err_message)): ?>
    <div class="alert alert-danger prompt_message  alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <strong>错误!</strong><?php echo $err_message?>
    </div>
    <?php endif ?>
    <?php if(isset($success_message)): ?>
    <div class="alert alert-success prompt_message  alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <strong>成功!</strong><?php echo $success_message?>
    </div>
    <?php endif ?>
    <h1>添加文章</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
      <h3>标题</h3>
      <input type="text" class="form-control" maxlength="30" placeholder="请输入文章标题" name="header">
      <h3>简介</h3>
      <input type="text" class="form-control" maxlength="90" placeholder="请输入文章简介" name="indroduction">
      <h3>关键词</h3>
      <input type="text" class="form-control" maxlength="50" placeholder="请输入文章关键词" name="keywords">

      <h3>请选择文章所属分类</h3>
      
      <select class="form-control" name="category">
        <?php if(isset($array)):?>
        <?php foreach($array as $key => $item):?>
        <option value="<?php echo $item['id']?>"><?php echo $item['name']?></option>
        <?php endforeach?>
        <?php endif?>
      <select>


      <h3>首页文章缩略图</h3>
      <input type="text" class="form-control" maxlength="50" placeholder="请输入文章缩略图" name="thumbnail">
      <textarea id="simplemde" name="content" >
        '<p class="text">In vehicula urna </p>
         <img src="/article/banner_1.jpg" alt="">
         <p class="text">天生的美女，从小到大就</p>
         <p class="text">天生的美女，从小到大就</p>
         <img src="/article/banner_2.jpg" alt="">
         <p class="text">In vehicula urna</p>'
      </textarea>
      <input type="submit" value="提交" class="btn" id="btn">
    </form>

  </div>
  <script src="/admin/lib/jquery/jquery.min.js"></script>
  <script src="/admin/lib/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>