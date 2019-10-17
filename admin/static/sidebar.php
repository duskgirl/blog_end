<?php
  $root_path = $_SERVER['DOCUMENT_ROOT'];
  require_once($root_path.'/admin/functions.php');
  blog_get_admin_user();
  $current_nav = isset($current_nav) ? $current_nav : '';
?>
<!-- 左侧边栏 -->
  <aside class="blog_admin_sidebar">
    <ul>
      <li class="person">
        <img src="/admin/img/default.png" alt="">
        <span>
          <?php if(isset($_SESSION['admin_login_user'])):?>
          <?php echo $_SESSION['admin_login_user']['name']?>
          <?php endif?>
        </span>
      </li>
      <li <?php echo $current_nav == 'index' ? 'class="active"':''?>><a href="/admin/index.php">首页</a></li>
      <li <?php echo $current_nav == 'category' ? 'class="active"':''?>><a href="/admin/category.php">分类管理</a></li>
      <li <?php echo $current_nav == 'article' ? 'class="active"':''?>><a href="/admin/article_mana.php">文章管理</a></li>
      <li <?php echo $current_nav == 'admin' ? 'class="active"':''?>><a href="/admin/administrator.php">管理员管理</a></li>
      <li <?php echo $current_nav == 'user' ? 'class="active"':''?>><a href="/admin/user.php">用户管理</a></li>
      <li <?php echo $current_nav == 'comment' ? 'class="active"':''?>><a href="/admin/comment.php">评论管理</a></li>
    </ul>
  </aside>