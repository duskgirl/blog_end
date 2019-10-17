<?php
$root_path = $_SERVER['DOCUMENT_ROOT'];
require_once($root_path.'/admin/functions.php');
blog_get_admin_user();
header('Content-type:application/json;charset=utf-8');
if($_SERVER['REQUEST_METHOD'] == 'GET'){
  visit();
}
function visit(){
  $sql = "select count(ip) as num,created from visit where to_days(now())-to_days(created)<=7 GROUP BY created";
  $result = json_encode(blog_select_all($sql));
  echo $result;
}