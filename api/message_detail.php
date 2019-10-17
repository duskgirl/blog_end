<?php
$root_path = $_SERVER['DOCUMENT_ROOT'];
require_once($root_path.'/functions.php');
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  get_message_detail();  
}
function get_message_detail(){
  if(empty($_POST['id'])){
    echo result('success','获取消息失败，请稍后重试！',false);
    return;
  }
  $id = $_POST['id'];
  $sql = "select 
  user.name as name,
  message.comment_content,
  message.type,
  read_status
  from message 
  inner join user on message.send_id = user.id
  where message.id={$id} limit 1";
  $result = blog_select_one($sql);
  if(empty($result)) {
    echo result('success','获取消息失败，请稍后重试！',false);
    return;
  }
  if($result['read_status'] == 1) {
    $read_status_sql = "update message set read_status=2 where id={$id}";
    blog_update($read_status_sql);
  }
  echo result('success',$result,true);
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