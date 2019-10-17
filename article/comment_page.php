<?php
header('Content-Type: application/json;charset=utf-8');
$root_path = $_SERVER['DOCUMENT_ROOT'];
require_once($root_path.'/functions.php');
if((empty($_POST['id'])||empty($_POST['page']))&&(empty($_POST['comment_id'])||empty($_POST['page']))){
  exit('缺少必要的参数!');
}
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  get_page();
}
function get_page(){
  if(!empty($_POST['id'])&&!empty($_POST['page'])){
    $aid = $_POST['id'];
    // 请求更多父评论的时候，父评论的page在增加，子评论的page永远为1,且size永远为2
    $page = $_POST['page'];
    if($page>=2){
      $size = 10;
      $skip = 2+$size*($page-2);
    } else {
      $size = 2;
      $skip = $size*($page-1);
    }
    $children_page = 1;
    $children_size = 2;
    $children_skip = $children_size*($children_page-1);
    // 不可能单独请求父元素评论，父评论和子评论是要同时返回数据的
    // 这里查询到的是这两条的主评论
    $parent_sql = "select
    a.id as aid,
    u.avatar,
	  u.name,
    c.id,
    c.comment_time,
    c.content,
    c.parent_id,
    c.love,
    c.children_num
    from comment as c
    inner join user as u on c.user_id = u.id
    inner join article as a on c.article_id = a.id
    where c.audit_status = 1 and a.id = {$aid} and c.parent_id is null
    order by c.love desc
    limit $skip,$size";
    $parent = blog_select_all($parent_sql);
    if(!$parent) {
      $parent = null;
      $children = null;
      $num = null;
      $result = array(
        "parent" => $parent,
        "children" => $children,
        "num" => null
      );
      $result = json_encode($result);
      echo $result;
      return;
    }
    // 查询父评论总数量
    $parent_num_sql = "select 
    count(c.id) as parent_num
    from comment as c
    inner join article as a on c.article_id = a.id
    where c.audit_status = 1 and a.id = {$aid} and c.parent_id is null";
    $parent_num = blog_select_all($parent_num_sql);
    if(!$parent_num){
      $parent = null;
      $children = null;
      $num = null;
      $result = array(
        "parent" => $parent,
        "children" => $children,
        "num" => $num
      );
      $result = json_encode($result);
      echo $result;
      return;
    }
    foreach($parent_num as $key => $item){
      if(isset($item['parent_num']) && !empty($item['parent_num'])) {
        $num = $item['parent_num'];
      }
    }
    // $result是前两条父评论
    $children = array();
    foreach($parent as $key => $item){
      // 这里查询子评论
      $children_sql = "select
	      u.name,
        c.id,
        c.comment_time,
        c.content,
        c.parent_id,
        c.love,
        c.children_num
        from comment as c
        inner join user as u on c.user_id = u.id
        where c.audit_status = 1 and c.parent_id = {$item['id']}
        order by c.love desc
        limit $children_skip,$children_size";
      // 获取查找到的子评论
      $child = blog_select_all($children_sql);
      if(!$child){
        $children = null;
        $result = array(
          "parent" => $parent,
          "children" => $children,
          "num" => $num
        );
        $result = json_encode($result);
        echo $result;
        return;
      }
      foreach($child as $key => $item){
        if(!empty($item['parent_id'])){
          if(!in_array($item,$children)){
            $children[] = $item;
          }
        }
      }
    }
    $result = array(
      "parent" => $parent,
      "children" => $children,
      "num" => $num
    );
    $result = json_encode($result);
    echo $result;
  }
  if(!empty($_POST['comment_id'])&&!empty($_POST['page'])){
    $comment_id = $_POST['comment_id'];
    $page = $_POST['page'];
    if($page>=2){
      $size = 10;
      $skip = 2+$size*($page-2);
    } else {
      $size = 2;
      $skip = $size*($page-1);
    }
    // 获取到对应父id下的子评论
    $child_sql = "select 
      u.name,
      c.id,
      c.comment_time,
      c.content,
      c.parent_id,
      c.love,
      c.children_num
      from comment as c
      inner join user as u on c.user_id = u.id
      where c.audit_status = 1 and c.parent_id = {$comment_id}
      order by c.love desc
      limit $skip,$size";
    $children = blog_select_all($child_sql);
    if(!$children) {
      $children = null;
    } else {
      $children = $children;
    }
    $result = array(
      "children" => $children
    );
    $result = json_encode($result);
    echo $result;
  } 
}