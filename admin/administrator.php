<?php
$root_path = $_SERVER['DOCUMENT_ROOT'];
require_once($root_path.'/admin/functions.php');
blog_get_admin_user();
if($_SERVER['REQUEST_METHOD'] === 'GET'){
  // 删除
  if(!empty($_GET['id'])){
    delete_admin();
  }
  // 查询
  $sql = 'select id,name,permission from adminuser';
  $result = blog_select_all($sql);
  if(!$result) {
    $GLOBALS['err_message'] = '查询数据失败';
  }
}
function delete_admin(){
  // 删除管理员
  // 先获取是否是管理员权限
  if(is_admin()){
    if(empty($_GET['id'])){
      exit('请传入必要的参数');
    }
    $id = $_GET['id'];
    $sql = "delete from adminuser where id={$id}";
    $result = blog_update($sql);
    if($result){
      $GLOBALS['success_message'] = '删除管理员成功';
      header('Location:'.$_SERVER['HTTP_REFERER']);
    } else {
      $GLOBALS['err_message'] = '删除管理员失败，请稍后重试';
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
  <title>大思考-后台管理员户管理</title>
  <meta name="keywords" content="大思考,大思考博客,前端开发,前端开发博客" />
  <meta name="description" content="大思考博客是一个分享前端开发相关知识的博客网站" />
  <link rel="stylesheet" href="/admin/lib/bootstrap/css/bootstrap.min.css" />
  <link rel="stylesheet" href="/admin/lib/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="/admin/css/topbar.css">
  <link rel="stylesheet" href="/admin/css/sidebar.css">
  <link rel="stylesheet" href="/admin/css/administrator.css">
  
</head>

<body>
  <div class="container-fluid">
  <?php include $root_path.'/admin/static/topbar.php'?>
    <div class="blog_admin_main">
      <?php $current_nav='admin';?>
      <?php include $root_path.'/admin/static/sidebar.php'?>
    <section class="blog_admin_center">
      <ol class="breadcrumb">
        <li><a href="/admin/index.php">首页</a></li>
        <li class="active"><a href="/admin/aadministrator.php">管理员管理</a></li>
      </ol>
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
      <a class="btn" href="/admin/administrator_add.php">添加管理员</a>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th>用户名</th>
            <th>权限</th>
            <th>操作</th>
          </tr>
          <tbody>
            <?php if(isset($array)):?>
            <?php foreach($array as $key => $item):?>
              <tr>
                <td><?php echo $item['name']?></td>
                <td>
                  <?php if($item['permission'] == 1):?>
                  <?php echo '管理员权限'?>
                  <?php else:?>
                  <?php echo '访问者权限'?>
                  <?php endif?>
                </td>
                <td>
                  <a href="?id=<?php echo $item['id']?>" class="btn">删除</a>
                  <a href="/admin/administrator_revise.php?id=<?php echo $item['id']?>" class="btn">修改</a>
                </td>
              </tr>
            <?php endforeach?>
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