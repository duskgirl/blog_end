<?php
$root_path = $_SERVER['DOCUMENT_ROOT'];
require_once($root_path.'/admin/functions.php');
blog_get_admin_user();
if($_SERVER['REQUEST_METHOD'] === 'GET') {
  // 删除操作
  if(!empty($_GET['id'])){
    delete_user();
  } 
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
  $total_sql = $sql = "select count('total') as totalRow from comment where content like '%{$search_value}%'";
  blog_get_page($total_sql);
  getuser();
} 
function getuser(){
  global $search_value;
  if($GLOBALS['total']>0){
    $skip = $GLOBALS['skip'];
    $per_list =  $GLOBALS['$per_list'];
    $sql = "select 
    id,
    name,
    email,
    avatar,
    userstats 
    from user 
    where name like '%{$search_value}%'
    order by id desc
    limit {$skip},{$per_list}";
    $GLOBALS['array_result'] = blog_select_all($sql);
  } 
}
function delete_user(){
  // 删除管理员
  // 先要获取是否是管理员权限
  if(is_admin()){
    if(empty($_GET['id'])){
      exit('请传入必要的参数');
    }
    $id = $_GET['id'];
    $sql = "delete from user where id={$id}";
    $result = blog_update($sql);
    if($result){
      $GLOBALS['success_message'] = '删除用户成功';
      header('Location:'.$_SERVER['HTTP_REFERER']);
    } else {
      $GLOBALS['err_message'] = '删除用户失败，请稍后重试';
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
  <title>大思考-后台用户管理</title>
  <meta name="keywords" content="大思考,大思考博客,前端开发,前端开发博客" />
  <meta name="description" content="大思考博客是一个分享前端开发相关知识的博客网站" />
  <link rel="stylesheet" href="/admin/lib/bootstrap/css/bootstrap.min.css" />
  <link rel="stylesheet" href="/admin/lib/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="/admin/css/article-mana.css">
  <link rel="stylesheet" href="/admin/css/topbar.css">
  <link rel="stylesheet" href="/admin/css/sidebar.css">
  <link rel="stylesheet" href="/admin/css/pagination.css">
  <link rel="stylesheet" href="/admin/css/user.css">
  <link rel="stylesheet" href="/admin/css/public.css">
  
</head>

<body>
  <!-- 顶部通栏 bolg的logo+后台管理系统左侧 右侧搜索框倒三角符号显示登陆者账户名以及退出 -->
  <div class="container-fluid">
  <?php include $root_path.'/admin/static/topbar.php'?>
    <div class="blog_admin_main">
    <?php $current_nav='user';?>
    <?php include $root_path.'/admin/static/sidebar.php'?>
    <section class="blog_admin_center">
      <ol class="breadcrumb">
        <li><a href="/admin/index.php">首页</a></li>
        <li class="active"><a href="/admin/user.php">用户管理</a></li>
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
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th>用户名</th>
            <th>邮箱</th>
            <th>头像</th>
            <th>状态</th>
            <th>操作</th>
          </tr>
          <!-- 目前就设置两种权限，一种是全权限，另一种是只有进入浏览的权限 -->
          <tbody>
            <?php if($total>0):?>
            <?php if(isset($array_result)):?>
            <?php foreach($array_result as $key => $item):?>
            <tr>
              <td><?php echo $item['name']?></td>
              <td><?php echo $item['email']?></td>
              <td><img src="<?php echo $item['avatar']?>"></td>
              <td>
              <?php if($item['userstats'] !=1):?>
              <?php echo '未激活'?>
              <?php else:?>
              <?php echo '已激活'?>
              <?php endif?>
              </td>
              <td>
                <a href="?id=<?php echo $item['id']?>" class="btn">删除</a>
              </td>
            </tr>
            <?php endforeach?>
            <?php endif?>
            <?php elseif($total==0):?>
            <tr class="nofound">
              <td colspan="5">抱歉！没有找到相关用户!</td>
            </tr>
            <?php endif?>
          </tbody>
        </thead>
      </table>
      <?php include $root_path.'/admin/static/pagination.php'?>
    </section>
    </div>
  </div>

  <script src="/admin/lib/jquery/jquery.min.js"></script>
  <script src="/admin/lib/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>