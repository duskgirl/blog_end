<?php
// 载入配置文件
$root_path = $_SERVER['DOCUMENT_ROOT'];
require_once $root_path.'/admin/config.php';
session_start();
// 连接数据库
function blog_connect(){
  $connect = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
  if(!$connect){
    exit('连接数据库失败');
  }
  mysqli_set_charset($connect,'utf8');
  return $connect;
}
// 数据查询唯一
function blog_select_one($sql){
  $connect = blog_connect();
  $query = mysqli_query($connect,$sql);
  if(!$query) {
    exit('数据查询失败');
  }
  $GLOBALS['row'] = mysqli_fetch_array($query);
  return isset($GLOBALS['row']) ? $GLOBALS['row'] : null;
}
// 数据查询所有
function blog_select_all($sql){
  $connect = blog_connect();
  $query = mysqli_query($connect,$sql);
  if(!$query) {
    exit('数据查询失败');
  }
  while($row = mysqli_fetch_array($query)){
    $GLOBALS['array'][] = $row;
  }
  return isset($GLOBALS['array']) ? $GLOBALS['array'] : null;
}
// 增删改
function blog_update($sql){
  $connect = blog_connect();
  $query = mysqli_query($connect,$sql);
  if(!$query) {
    exit('数据查询失败');
  }
  // false更新失败
  if(mysqli_affected_rows($connect)<1){
    return false;
  }
  // true更新成功
  return true;
}

// 获取当前登陆用户信息，如果没有获取到则直接跳转到登录页面
function blog_get_admin_user(){
  if(empty($_SESSION['admin_login_user'])) {
    header('Location:/admin/login.php');
  } else {
    return $_SESSION['admin_login_user'];
  }
}
// 获取当前登陆用户是否是管理员权限
function is_admin(){
  blog_get_admin_user();
  if(!empty($_SESSION['admin_login_user'])){
    if($_SESSION['admin_login_user']['permission'] != 1) {
      $GLOBALS['err_message'] = '当前用户权限不足！操作失败';
      return false;
    } else {
      return true;
    }
  }
}
// 获取分页数据
function blog_get_page($sql){
  $total_row = blog_select_one($sql);
  // 数据总条数
  $total = (int)$total_row['totalRow'];
  // 每页显示的条数
  $per_list = 4;
  // 总页数
  $total_page = (int)ceil($total/$per_list);
  // 当前默认为第一页
  $current_page = empty($_GET['page']) ? 1: (int)$_GET['page'];
  if($current_page>$total_page && $total_page>0){
    header("Location:?page={$total_page}");
  }
  if($current_page<1){
    header('Location:?page=1');
  }
  // 跳过多少行；
  $skip = ($current_page-1)*$per_list;
  // 可见的页码个数
  $visible_page = 5;
  // 当前页码左右可见的页码；
  $visible_var =  ($visible_page-1)/2;
  // 开始页码
  $begin = $current_page - $visible_var;
  // 结束页码
  $end = $begin + $visible_page-1;
  // 可能出现$begin和$end不合理情况
  if($begin<1){
    $begin = 1;
    $end = $begin + $visible_page-1;
  }
  if($end > $total_page){
    $end = $total_page;
    $begin = $end - $visible_page+1;
    if($begin <1 ){
      $begin = 1;
    }
  }
  $GLOBALS['current_page'] = $current_page;
  $GLOBALS['begin'] = $begin;
  $GLOBALS['end'] = $end;
  $GLOBALS['total_page'] = $total_page;
  $GLOBALS['total'] = $total;
  $GLOBALS['skip'] = $skip;
  $GLOBALS['$per_list'] = $per_list;
}