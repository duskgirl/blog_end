
<?php
// id: id,:文章id
// user_id: user_id,：用户id
// content: content,:评论内容
// parent_id: parent_id：父评论id
header('Content-Type: application/json;charset=utf-8');
$root_path = $_SERVER['DOCUMENT_ROOT'];
require_once($root_path.'/functions.php');
if(empty($_GET['id']) || empty($_GET['user_id']) || empty($_GET['content'])){
  exit('缺少必要的参数');
}
if($_SERVER['REQUEST_METHOD'] === 'GET'){
  comment_commit();
}

function comment_commit(){
  if(empty($_GET['id'])){
    $result = array(
      'danger' => true,
      'message' => '提交评论错误,请稍后重试!'
    );
    $result = json_encode($result);
    return $result;
  }
  // 未登陆的用户
  if(empty($_GET['user_id']) || $_GET['user_id'] == 0) {
    $result = array(
      'danger' => true,
      'message' => '您还未登陆，请先登录后再发表评论'
    );
    $result = json_encode($result);
    return $result;
  }
  // 评论内容处理
  // 不能为空，然后还不能超过100个字符数
  if(empty($_GET['content'])){
    $result = array(
      'danger' => true,
      'message' => '评论内容不能为空'
    );
    $result = json_encode($result);
    return $result;
  }
}
$article_id = $_GET['id'];
$user_id = $_GET['user_id'];
$content = $_GET['content'];
if(mb_strlen($content)>100){
  $content = mb_substr($content,0,100);
  var_dump($content);
} else {
  $content = $_GET['content'];
}
if(empty($_GET['parent_id'])){
  $parent_id = null;
  $sql = "insert into comment (article_id,user_id,content) values({$article_id},{$user_id},'{$content}')";
} else {
  $parent_id = $_GET['parent_id'];
  $sql = "insert into comment (article_id,user_id,content,parent_id) values({$article_id},{$user_id},'{$content}',{$parent_id})";
}
$result = blog_update($sql);
if(!$result) {
  $result = array(
    'danger' => true,
    'message' => '提交评论失败,请稍后重试!'
  );
  $result = json_encode($result);
  return $result;
} else {
  $result = array(
    'success' => true,
    'message' => '提交评论成功,请等待管理员审核通过后展示!'
  );
  $result = json_encode($result);
  return $result;
}