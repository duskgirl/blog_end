<?php
$root_path = $_SERVER['DOCUMENT_ROOT'];
require_once($root_path.'/functions.php');
header('Content-Type:application/json;setcharset=utf-8');
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  person_comment();
}
function person_comment(){
  if(empty($_POST['user_id'])){
    $result = result('success','缺少必要参数','false');
    echo $result;
    return;
  }
  if(empty($_POST['page'])){
    $result = result('success','缺少必要参数','false');
    echo $result;
    return;
  }
  $user_id = $_POST['user_id'];
  $page = (int)$_POST['page'];
  
  if($page == 1) {
    $skip = 0;
    $size = 2;
  } else if($page == 2) {
    $skip = 2;
    $size = 10;
  } else{
    $size = 10;
    $skip = ($page-2)*$size + 2;
  }
  // 获取的总评论数
  $num_sql = "select count(user_id) as person_total from comment where user_id = {$user_id}";
  $num = blog_select_one($num_sql);
  $person_total = $num['person_total'];

  $sql = "select 
  c.content as comment_content,
  c.comment_time,
  c.parent_id,
  c.love,
  c.children_num,
  a.header,
  a.content as article_path,
  a.id 
  from comment as c
  inner join article as a
  on c.article_id = a.id
  where c.user_id = {$user_id}
  order by c.comment_time desc
  limit $skip,$size";
  $result = blog_select_all($sql);
  $finish = [];
  if(!empty($result)){
    foreach($result as $key=>$item){
      if(empty($item['person_total'])){
        if(!empty($item['parent_id'])) {
          $parent_id = (int)$item['parent_id'];
          $parent_sql = "select 
          u.name as parent_name,
          c.content as parent_content
          from comment as c
          inner join user as u
          on c.user_id = u.id
          where c.id = {$parent_id}";
          $parent= blog_select_one($parent_sql);
          $item['parent_name'] = $parent['parent_name'];
          $item['parent_content'] = $parent['parent_content'];
          $finish[] = $item; 
        } else {
          $finish[] = $item;
        }
      }
    }
  }
  $result['person_total'] = $person_total;
  $result['finish'] = $finish;
  $result = result('success',$result,true);
  echo $result;
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