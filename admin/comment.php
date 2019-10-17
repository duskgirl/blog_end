<?php
$root_path = $_SERVER['DOCUMENT_ROOT'];
require_once($root_path.'/admin/functions.php');
blog_get_admin_user();
if($_SERVER['REQUEST_METHOD'] === 'GET') {
  if(!empty($_GET['id'])){
    if(!empty($_GET['approved'])){
      approved_comment();
    } elseif(!empty($_GET['rejected'])){
      rejected_comment();
    } else {
      exit('缺少必要的参数');
    }
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
  getComment();
}
function getComment(){
  global $search_value;
  if($GLOBALS['total']>0){
    $skip = $GLOBALS['skip'];
    $per_list =  $GLOBALS['$per_list'];
    $sql = "select 
    c.id,
    u.name,
    a.id as a_id,
    a.header,
    a.content as path,
    c.content as content,
    c.comment_time,
    c.audit_status 
    from comment as c 
    inner join user as u on c.user_id = u.id 
    inner join article as a on c.article_id = a.id 
    where c.content like '%{$search_value}%'
    order by c.id desc
    limit {$skip},{$per_list}";
    $GLOBALS['array_result'] = blog_select_all($sql);
  }
}
// 审核通过
function approved_comment(){
  if(is_admin()){
    $GLOBALS['err_message']=null;
    if(empty($_GET['id'])){
      exit('缺少必要的参数');
    }
    if($_GET['approved'] != 'yes'){
      exit('参数错误');
    }
    // 评论id
    $id = $_GET['id'];
    // 根据评论id查询相关信息
    $comment_detail_sql = "select user_id,parent_id,content from comment where id={$id} limit 1";
    $comment_detail = blog_select_one($comment_detail_sql);
    if(!$comment_detail){
      $GLOBALS['err_message'] = '审核批准失败，请稍后重试';
      return;
    }
    $comment_content = $comment_detail['content'];
    // parent_id指的是评论id
    $parent_id = $comment_detail['parent_id'];
    // 审核的通过第一步成功
    // 审核的通过第二步则需查找parent_id
    if(!empty($parent_id)){
      // 对父评论的子评论个数处理
      $parent_sql = "update comment set children_num=children_num+1 where id={$parent_id}";
      $parent = blog_update($parent_sql);
      if(!$parent){
        $GLOBALS['err_message'] = '审核批准失败，请稍后重试';
        return;
      }
      // 根据parent_id找到receive_id
      $receive_sql = "select user_id from comment where id={$parent_id} limit 1";
      $receive_result = blog_select_one($receive_sql);
      // receive_id应指向父评论id里面的用户id
      $receive_id = $receive_result['user_id'];
      $send_id = $comment_detail['user_id'];
      $message_sql = "insert into 
      message (send_id,receive_id,comment_id,comment_content,message,type) 
      values ({$send_id},{$receive_id},{$id},'{$comment_content}','有人悄悄回复了您的评论！',3)";
      $message_result = blog_update($message_sql);
      if(!$message_result){
        $GLOBALS['err_message'] = '审核批准失败，请稍后重试!';
        return;
      }
    }
    // 无论有无父评论,都应插入审核成功这条数据
    $receive_id = $comment_detail['user_id'];
    $message_sql = "insert into 
    message (receive_id,comment_id,comment_content) 
    values ({$receive_id},{$id},'{$comment_content}')";
    $message_result = blog_update($message_sql);
    if(!$message_result){
      $GLOBALS['err_message'] = '审核批准失败，请稍后重试';
      return;
    }
     // 修改评论表里面的audit_status为审核通过为数字1
    // 并且查询他的parent_id如果parent_id为null则不用改变任何
    // 否则则将他的parent_id的数量增加
    $sql = "update comment set audit_status=1 where id = {$id} limit 1";
    $result = blog_update($sql);
    if(!$result){
      $GLOBALS['err_message'] = '审核批准失败，请稍后重试';
      return;
    }
    header('Location:'.$_SERVER['HTTP_REFERER']);
  }
}
// 审核拒绝,直接删除该条信息
function rejected_comment(){
  if(is_admin()){
    $GLOBALS['err_message']=null;
    if(empty($_GET['id'])){
      exit('缺少必要的参数');
    }
    if($_GET['rejected'] != 'yes'){
      exit('参数错误');
    }
    $id = $_GET['id'];
    // 根据评论id查询相关信息
    $comment_detail_sql = "select user_id,parent_id,content from comment where id={$id}";
    $comment_detail = blog_select_one($comment_detail_sql);
    if(!$comment_detail){
      $GLOBALS['err_message'] = '审核批准失败，请稍后重试';
      return;
    }
    $comment_content = $comment_detail['content'];
    // 通知消息审核评论失败
    $receive_id = $comment_detail['user_id'];
    $message = '您有一条评论未通过管理员审核啦！';
    $message_sql = "insert into 
    message (receive_id,comment_content,message,type) 
    values ({$receive_id},'{$comment_content}','{$message}',2)";
    $message_result = blog_update($message_sql);
    if(!$message_result){
      $GLOBALS['err_message'] = '审核批准失败，请稍后重试';
      return;
    }
    $sql = "delete from comment where id = {$id}";
    $result = blog_update($sql);
    if(!$result){
      $GLOBALS['err_message'] = '审核失败，请稍后重试';
      return;
    }
    header('Location:'.$_SERVER['HTTP_REFERER']);
  }
}
?>
<!DOCTYPE html>
<html>
<head lang="en">
  <meta charset="UTF-8">
  <meta name="renderer" content="webkit" />
  <meta name="force-renderer" content="webkit" />
  <meta http-equiv="X-UA-Compatible" content="IE=Edge chrome=1" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0, shrink-to-fit=no" />
  <meta name="apple-mobile-web-app-title" content="大思考博客" />
  <meta http-equiv="Cache-Control" content="no-siteapp" />
  <meta name="referrer" content="always">
  <meta name="format-detection" content="telephone=no,email=no,adress=no">
  <title>大思考-后台评论管理</title>
  <meta name="keywords" content="大思考,大思考博客,前端开发,前端开发博客" />
  <meta name="description" content="大思考博客是一个分享前端开发相关知识的博客网站" />
  <link rel="stylesheet" href="/admin/lib/bootstrap/css/bootstrap.min.css" />
  <link rel="stylesheet" href="/admin/lib/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="/admin/css/topbar.css">
  <link rel="stylesheet" href="/admin/css/sidebar.css">
  <link rel="stylesheet" href="/admin/css/pagination.css">
  <link rel="stylesheet" href="/admin/css/comment.css">
  <link rel="stylesheet" href="/admin/css/public.css">
</head>
<body>
  <div class="container-fluid">
  <?php include $root_path.'/admin/static/topbar.php'?>
    <div class="blog_admin_main">
    <?php $current_nav='comment';?>
    <?php include $root_path.'/admin/static/sidebar.php'?>
    <section class="blog_admin_center">
      <ol class="breadcrumb">
        <li><a href="/admin/index.php">首页</a></li>
        <li class="active"><a href="/admin/comment.php">评论管理</a></li>
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
            <th>文章名称</th>
            <th>评论者</th>
            <th>评论内容</th>
            <th>评论时间</th>
            <th>审核</th>
          </tr>
          <tbody>
            <?php if($total>0):?>
            <?php if(isset($array_result)):?>
            <?php foreach($array_result as $key => $item):?>
            <tr>
              <td class="ellipsis">
                <a href="<?php echo $item['path']?>?id=<?php echo $item['a_id']?>"><?php echo $item['header'] ?></a>
              </td>
              <td><?php echo $item['name']?></td>
              <td><?php echo $item['content']?></td>
              <td><?php echo $item['comment_time']?></td>
              <?php if($item['audit_status'] == 0):?>
              <td>
                <a href="?id=<?php echo $item['id']?>&approved=yes" class="btn">批准</a>
                <a href="?id=<?php echo $item['id']?>&rejected=yes" class="btn">拒绝</a>
              </td>
              <?php elseif($item['audit_status'] == 1):?>
              <td class="<?php echo 'approved'?>">审核通过</td>
              <?php endif?>
            </tr>
            <?php endforeach?>
            <?php endif?>
            <?php elseif($total === 0):?>
            <tr class="nofound">
              <td colspan="5">抱歉！没有找到相关评论!</td>
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