<?php
$root_path = $_SERVER['DOCUMENT_ROOT'];
require_once($root_path.'/admin/functions.php');
blog_get_admin_user();
// 添加分类
if($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['name'])) {
  addCategory();
}
// 添加分类
function addCategory(){
  if(is_admin()){
    if(empty($_GET['name'])){
      exit('请传入必要的参数');
    }
    $name = $_GET['name'];
    $sql = "insert into category (name) values ('{$name}')";
    $result = blog_update($sql);
    if(!$result){
      $GLOBALS['err_message'] = '添加分类失败';
    };
    $GLOBALS['success_message'] = '添加分类成功';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
  }
}
// 删除分类
if($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['id'])){
  deleteCategory();
}
function deleteCategory(){
  if(is_admin()){
    if(empty($_GET['id'])){
      exit('请传入必要的参数');
    }
    $id = $_GET['id'];
    $connect = blog_connect();
    mysqli_query($connect,"SET foreign_key_checks = 0");
    $sql = "delete from category where id = {$id}";
    $result = blog_update($sql);
    if(!$result){
      $GLOBALS['delete_err_message'] = '删除分类失败';
    };
    $GLOBALS['delete_success_message'] = '删除分类成功';
    mysqli_query($connect,"SET foreign_key_checks = 1");
    header('Location: ' . $_SERVER['HTTP_REFERER']);
  }
}
// 文章页面渲染
// $search应该包含：页码以及id?
// $search => ? $id = ? & $page = ?;
if($_SERVER['REQUEST_METHOD'] === 'GET') {
  $search = '';
  $search_value = '';
  // $search => &search='';
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
  }
  getCategory();
}
function getCategory(){
  global $search_value;  
  $sql = "select id,name from category where name like '%{$search_value}%' order by id desc";
  $result = blog_select_all($sql);
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
  <title>大思考-后台分类</title>
  <meta name="keywords" content="大思考,大思考博客,前端开发,前端开发博客" />
  <meta name="description" content="大思考博客是一个分享前端开发相关知识的博客网站" />
  <link rel="stylesheet" href="/admin/lib/bootstrap/css/bootstrap.min.css" />
  <link rel="stylesheet" href="/admin/lib/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="/admin/css/topbar.css">
  <link rel="stylesheet" href="/admin/css/sidebar.css">
  <link rel="stylesheet" href="/admin/css/category.css">
  <link rel="stylesheet" href="/admin/css/public.css">
</head>
<body>
  <div class="container-fluid">
  <?php include $root_path.'/admin/static/topbar.php'?>
    <div class="blog_admin_main">
      <!-- 左侧边栏 -->
      <?php $current_nav='category';?>
      <?php include $root_path.'/admin/static/sidebar.php'?>
        <section class="blog_admin_center">
          <form action="<?php echo $_SERVER['PHP_SELF']?>" method="get" class="add_category">
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
            <h3>添加文章分类</h3>
            <div class="input-group">
              <span class="input-group-addon">分类名称</span>
              <input type="text" class="form-control" name="name" placeholder="请输入文章分类名称">
              <span class="input-group-btn">
                <input class="btn btn-default" type="submit" value="添加分类">
              </span>
            </div>
          </form>
          <?php if(isset($delete_err_message)): ?>
            <div class="alert alert-danger prompt_message  alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <strong>错误!</strong><?php echo $delete_err_message?>
            </div>
            <?php endif ?>
            <?php if(isset($delete_success_message)): ?>
            <div class="alert alert-success prompt_message  alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <strong>成功!</strong><?php echo $delete_success_message?>
            </div>
          <?php endif ?>
          <h3>分类详情</h3>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th>分类名称</th>
                <th>操作</th>
              </tr>
              <tbody>
                <?php if(isset($array)):?>
                <?php foreach($array as $key => $item):?>
                <tr>
                  <td><?php echo $item['name']?></td>
                  <td><a href="?id=<?php echo $item['id']?>" class="btn">删除</a>
                 </td>
                </tr>
                <?php endforeach?>
                <?php else:?>
                <tr class="nofound">
                  <td colspan="2">抱歉！没有找到相关分类!</td>
                </tr>
                <?php endif?>
              </tbody>
            </thead>
          </table>
        </section>
    </div>
  </div>
  <script src="/admin/lib/jquery/jquery.min.js"></script>
  <script src="/admin/lib/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>