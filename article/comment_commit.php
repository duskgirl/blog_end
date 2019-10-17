<?php
// id: id,:文章id
// user_id: user_id,：用户id
// content: content,:评论内容
// parent_id: parent_id：父评论id
header('Content-Type: application/json;charset=utf-8');
$root_path = $_SERVER['DOCUMENT_ROOT'];
require_once($root_path.'/functions.php');
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  comment_commit();
}

function comment_commit(){
  if(empty($_POST['id'])){
    $result = array(
      'danger' => true,
      'message' => '提交评论错误,请稍后重试!'
    );
    $result = json_encode($result);
    echo $result;
    return;
  }
  // 未登陆的用户
  if(empty($_POST['user_id']) || $_POST['user_id'] == 0) {
    $result = array(
      'danger' => true,
      'message' => '您还未登陆，请先登录后再发表评论'
    );
    $result = json_encode($result);
    echo $result;
    return;
  }
  // 评论内容处理
  // 不能为空，然后还不能超过100个字符数
  if(empty($_POST['content'])){
    $result = array(
      'danger' => true,
      'message' => '评论内容不能为空'
    );
    $result = json_encode($result);
    echo $result;
    return;
  }
  // 判断评论的字数，如果超过100截取前100个字符
  $article_id = $_POST['id'];
  $user_id = $_POST['user_id'];
  $content = $_POST['content'];
  if(mb_strlen($content)){
    $content = mb_substr($content,0,100);
  } else {
    $content = $_POST['content'];
  }
  // 数据持久化
  if(empty($_POST['parent_id'])){
    $parent_id = null;
    $sql = "insert into comment (article_id,user_id,content) values({$article_id},{$user_id},'{$content}')";
  } else {
    $parent_id = $_POST['parent_id'];
    $sql = "insert into comment (article_id,user_id,content,parent_id) values({$article_id},{$user_id},'{$content}',{$parent_id})";
  }
  $result = blog_update($sql);
  if(!$result) {
    $result = array(
      'danger' => true,
      'message' => '提交评论失败,请稍后重试!'
    );
    $result = json_encode($result);
    echo $result;
    return;
  } else {
    $result = array(
      'success' => true,
      'message' => '提交评论成功,请等待管理员审核通过后展示!'
    );
    $result = json_encode($result);
    echo $result;
  }
}