<?php
  $root_path = $_SERVER['DOCUMENT_ROOT'];
  require_once($root_path.'/functions.php');
  // 获取热门文章,按照被查看的次数排序,限制三条,需要数据header,id,content
  $new_result = [];
  $hot_result = [];
  $hot_sql = 'select 
  a.id,
  header,
  content,
  viewnumber 
  from article as a 
  inner join category as c 
  on a.category_id = c.id 
  order by viewnumber desc 
  limit 3';
  // 获取最新发布文章,按照文章id从大到小排序,限制三条,需要数据header,id,content
  $hot = blog_select_all($hot_sql);
  $new_sql = 'select 
  a.id,
  header,
  content from article as a 
  inner join category as c 
  on a.category_id = c.id 
  order by a.id desc 
  limit 3';
  $new = blog_select_all($new_sql); 
  foreach($new as $key => $item){
    if(!isset($item['viewnumber'])){
      $new_result[] = $item;
    } elseif(!isset($item['thumbnail'])) {
      $hot_result[] = $item;
    }
  }
?>
  <section class="sidebar hidden-xs">
    <form action="/index.php" method="get">
      <input type="search" class="form-control" placeholder="键入关键字搜索" name="search" />
      <input type="submit" value="搜索" class="btn btn-default" />
    </form>
    <div class="hot">
      <h3 class="underline">热门文章</h3>
      <ul>
        <?php if(!empty($hot_result)):?>
        <?php foreach($hot_result as $key => $item):?>
        <li><a href="<?php echo $item['content']?>?id=<?php echo $item['id']?>"><?php echo $item['header']?></a></li>
        <?php endforeach?>
        <?php endif?>
      </ul>
    </div>
    <div class="new">
      <h3 class="underline">最新发布</h3>
      <ul>
        <?php if(!empty($new_result)):?>
        <?php foreach($new_result as $key => $item):?>
        <li><a href="<?php echo $item['content']?>?id=<?php echo $item['id']?>"><?php echo $item['header']?></a></li>
        <?php endforeach?>
        <?php endif?>
      </ul>
    </div>
  </section>