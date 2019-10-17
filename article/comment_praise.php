<?php
$root_path = $_SERVER['DOCUMENT_ROOT'];
require_once($root_path.'/functions.php');
header('Content-Type: application/json;charset=utf-8');
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  praise();
}
function praise(){
  if(empty($_POST['user_id'])){
    $result = result('success','请先注册登陆',false);
    echo $result;
    return;
  }
  $post_id = $_POST['user_id'];
  if(empty($_POST['comment_id'])){
    $result = result('success','请正常操作',false);
    echo $result;
    return;
  }
  $comment_id = $_POST['comment_id'];
  // 判断用户是点赞还是取消点赞
  // 根据传递过来的值在评论表找是否有这个用户向这个评论点赞的记录，如果有
  // 则取消点赞
  // 如果没有则是点赞行为
  $is_love_sql = "select post_id,comment_id from praise where post_id={$post_id} and comment_id={$comment_id}";
  $is_love_row = blog_select_one($is_love_sql);
  // 有记录，取消点赞
  if($is_love_row){
    // 先删除记录，并且love的数量也发生变化
    // 取消点赞
    $cancel_love_sql = "delete from praise where post_id={$post_id} and comment_id={$comment_id}";
    $cancel_love_row = blog_update($cancel_love_sql);
    // 取消点赞失败
    if(!$cancel_love_row){
      $result = result('cancel','取消点赞失败,请稍后重试',false);
      echo $result;
      return;
    }
    $cancel_num_sql = "update comment set love=love-1 where id={$comment_id}";
    $cancel_num_row = blog_update($cancel_num_sql);
    // 取消点赞数量减少失败
    if(!$cancel_num_row){
      $result = result('cancel','取消点赞失败，请稍后重试',false);
      echo $result;
      return;
    }
    // 取消点赞数量减少成功
    $result = result('cancel',null,true);
    echo $result;
    return;
  }
  // 当前用户未向该评论点赞
  // 根据评论id找到接收用户的id
  $receive_id_sql = "select user_id from comment where id={$comment_id}";
  $receive_id_row = blog_select_one($receive_id_sql);
  if(!$receive_id_row){
    $result = result('success','点赞失败，请稍后重试',false);
    echo $result;
    return;
  }
  $receive_id = $receive_id_row['user_id'];
  // 还要更新评论表赞的数量
  $praise_sql = "insert into praise (post_id,comment_id,receive_id) values ({$post_id},{$comment_id},$receive_id)";
  $result = blog_update($praise_sql);
  if(!$result){
    $result = result('success','点赞失败，请稍后重试',false);
    echo $result;
    return;
  } else {
    $praise_num_sql = "update comment set love=love+1 where id={$comment_id}";
    $praise_num_row = blog_update($praise_num_sql);
    if(!$praise_num_row){
      // 点赞成功第二步失败
      $result = result('success','点赞失败，请稍后重试',false);
      echo $result;
      return;
    }
    $result = result('success',null,true);
    echo $result;
  }
}
function result($title,$message,$value){
  if(empty($message)){
    $result = array(
      $title => $value
    );
  } else {
      $result = array(
        $title => $value,
        'message' => $message
      );
    }
  $result = json_encode($result);
  return $result;
}