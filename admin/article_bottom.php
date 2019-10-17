</div>
  <div class="point">
    <?php if(isset($prev_row)):?>
    <a class="arrow_left" href="<?php echo $prev_row['content']?>?id=<?php echo $prev_row['id']?>">
      <span class="fa fa-long-arrow-left arrow"></span>
      <span class="text"><?php echo $prev_row['header']?></span>
    </a>
    <?php else:?>
    <a class="arrow_left" href="<?php echo $current_row['content']?>?id=<?php echo $current_row['id']?>">
      <span class="fa fa-long-arrow-left arrow"></span>
      <span class="text"><?php echo $current_row['header']?></span>
    </a>
    <?php endif?>
    <?php if(isset($next_row)):?>
    <a class="arrow_right" href="<?php echo $next_row['content']?>?id=<?php echo $next_row['id']?>">
      <span class="text"><?php echo $next_row['header']?></span>
      <span class="fa fa-long-arrow-right arrow"></span>
    </a>
    <?php else: ?>
    <a class="arrow_right" href="<?php echo $current_row['content']?>?id=<?php echo $current_row['id']?>">
      <span class="text"><?php echo $current_row['header']?></span>
      <span class="fa fa-long-arrow-right arrow"></span>
    </a>
      <?php endif?>
  </div>
    <?php include  $root_path.'/article/comment.php'?>
    </section>
    <?php include $root_path.'/static/sidebar.php'?>
  </main>
  <!-- 脚本 -->
  <?php include $root_path.'/static/footer.php'?>
  <script src="/lib/jquery/jquery.min.js"></script>
  <script src="/lib/bootstrap/js/bootstrap.min.js"></script>
  <script src="/js/topbar.js"></script>
</body>
</html>