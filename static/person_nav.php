<?php
$root_path = $_SERVER['DOCUMENT_ROOT'];
require_once($root_path.'/functions.php');
$user = blog_get_current_user();
$current_nav = isset($current_nav) ? $current_nav : '';
?>
<div class="person_nav">
  <div class="header hidden-xs">
    <img src="<?php echo $user['avatar']?>" alt="" class="avatar">
    <h3 class="underline"><?php echo $user['name']?></h3>
  </div>
  <ul>
    <li class="ellipsis person_nav_item<?php echo $current_nav == 'person' ? ' active' : ''?>"><a href="/person.php"><span class="fa fa-comment-o"></span> 我的评论</a></li>
    <li class="ellipsis person_nav_item<?php echo  $current_nav == 'person_message' ? ' active' : ''?>"><a href="/person_message.php"><span class="fa fa-bell-o"></span> 我的消息</a></li>
    <li class="ellipsis person_nav_item<?php echo  $current_nav == 'person_set' ? ' active' : ''?>"><a href="/person_set.php"><span class="fa fa-cog"></span> 账户设置</a></li>
  </ul>
</div>