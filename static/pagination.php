<?php if(isset($total) && $total>0):?>
<nav aria-label="Page navigation" class="nav_pagination">
  <ul class="pagination">
  <li>
    <?php if(isset($current_page) && $current_page>1):?>
      <a href="?page=<?php echo ($current_page-1).$search?>" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
      </a>
      <?php else:?>
      <a href="?page=<?php echo ($current_page).$search?>" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
      </a>
    <?php endif?>  
    </li>
    <?php for ($i = $begin; $i <= $end; $i++): ?>
    <li <?php echo $i === $current_page ? 'class="active"' : ''; ?>><a href="?page=<?php echo $i.$search?>"><?php echo $i; ?></a></li>
    <?php endfor ?>
    
    <li>
    <?php if(isset($current_page) && $current_page <$total_page):?>
      <a href="?page=<?php echo ($current_page+1).$search?>" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
      </a>
      <?php else:?>
      <a href="?page=<?php echo ($current_page).$search?>" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
      </a>
      <?php endif?>
    </li>
  </ul>
</nav>
<?php endif?>