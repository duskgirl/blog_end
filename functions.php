<?php
// 载入配置文件
require_once 'config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once 'lib/PHPMailer-master/Exception.php';
require_once 'lib/PHPMailer-master/PHPMailer.php';
require_once 'lib/PHPMailer-master/SMTP.php';
date_default_timezone_set('PRC');
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
  $row = mysqli_fetch_array($query);
  return $row;
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
  return empty($GLOBALS['array']) ?  null: $GLOBALS['array'];
}
// 增删改
function blog_update($sql){
  $connect = blog_connect();
  $query = mysqli_query($connect,$sql);
  if(!$query) {
    exit('数据查询失败');
  }
  // false数据更新失败
  if(mysqli_affected_rows($connect)<1){
    return false;
  }
  // true数据更新成功
  return true;
}
function blog_get_current_user(){
  if(empty($_SESSION['current_login_user'])) {
   return null;
  } else {
    return $_SESSION['current_login_user'];
  }
}
// 网站访问量统计
function blog_visit(){
  if (getenv("HTTP_CLIENT_IP") &&strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")){
    $ip = getenv("HTTP_CLIENT_IP");
  } else if (getenv("HTTP_X_FORWARDED_FOR")&&strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")){
    $ip = getenv("HTTP_X_FORWARDED_FOR");
  } else if (getenv("REMOTE_ADDR") &&strcasecmp(getenv("REMOTE_ADDR"), "unknown")){
    $ip = getenv("REMOTE_ADDR");
  } else if (isset($_SERVER['REMOTE_ADDR'])&& $_SERVER['REMOTE_ADDR']&&strcasecmp($_SERVER['REMOTE_ADDR'],"unknown")){
    $ip = $_SERVER['REMOTE_ADDR'];
  } else {
    $ip = "unknown";
  }
  // 获取当前日期
  $created = date('Y-m-d');
  $select_sql = "select ip,created from visit where ip='{$ip}' and created='{$created}' limit 1";
  $result = blog_select_one($select_sql);
  // 不存在该ip，那就插入数据
  if(!$result){
    $sql = "insert into visit (ip,created) values ('{$ip}','{$created}')";
    blog_update($sql);
  }
}
// 给用户发送邮箱
function sendmail($email,$header,$content){
  $mail = new PHPMailer(true);
  try {
  //Server settings:服务器配置
  $mail->CharSet = 'UTF-8';
  $mail->SMTPDebug = 2;                                       // Enable verbose debug output
  $mail->isSMTP();                                            // Set mailer to use SMTP
  $mail->Host       = 'smtp.126.com';  // Specify main and backup SMTP servers
  $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
  $mail->Username   = 'duskgirl@126.com';                     // SMTP username
  $mail->Password   = 'dusk1993';                               // SMTP password
  $mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption, `ssl` also accepted
  $mail->Port       = 465;                                    // TCP port to connect to
  
  $mail->setFrom('duskgirl@126.com', '大思考');
  $mail->addAddress($email, $email);     // Add a recipient

  $mail->addReplyTo('duskgirl@126.com', '大思考');
  
  $mail->isHTML(true);                                  // Set email format to HTML
  // 这里是邮件标题
  $mail->Subject = $header;
  // 这里是邮件内容
  $mail->Body    = $content;
  // 如果邮件客户端不支持HTML则显示此内容
  $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

  $mail->send();
  // 邮件发送成功,我自行设置返回1，表示成功
  // return 'Message has been sent';
  return 1;
  } catch (Exception $e) {
  // 邮件发送失败
    // return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    return "发送邮件失败: {$mail->ErrorInfo}";
  }
}
// 获取网页来源地址
function blog_from(){
  $user = blog_get_current_user();
  if($user == null){
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
  }
}
// 获取分页数据
function blog_get_page($sql){
  // 设置一个条件保证这里查询到的总条数和页面管理查询到的总条数是一样的，因为分类的删除导致有些文章分类id没有了是查询不到的
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