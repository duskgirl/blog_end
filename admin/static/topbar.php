<div class="blog_admin_topbar">
  <h1 class="col-xs-4"></h1>
  <div class="col-xs-8 nav_right">
    <form action="<?php echo $_SERVER['PHP_SELF']?>" method="get">
      <div class="input-group">
        <input type="search" class="form-control" placeholder="键入关键字搜索" name="search" />
        <span class="input-group-btn">
          <input class="btn btn-default" type="submit" value="搜索">
        </span>
      </div>
    </form>
    <div class="logout"><a href="/admin/login.php?action=logout">退出登陆</a></div>
  </div>
</div>