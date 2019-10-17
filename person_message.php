<?php
 $root_path = $_SERVER['DOCUMENT_ROOT'];
 require_once($root_path.'/functions.php');
$user = blog_get_current_user();
if($_SERVER['REQUEST_METHOD'] === 'GET'){
  // 必须先注册登陆才能进去
  if($user == null){
    $_SESSION['url'] = '/person_message.php';
    header('Location: /user/login.php');
  }
  // 搜索
  $user_id = $user['id'];
  $where = "receive_id={$user_id}";
  $search = '';
  if(!empty($_GET['id'])){
    delete_message();
  }
  $total_sql = "select 
  count('id') as totalRow 
  from message as m 
  inner join user as u on m.send_id = u.id
  where {$where}";
  blog_get_page($total_sql);
  get_person_message();
}
function delete_message(){
  if(empty($_GET['id'])){
    $GLOBALS['warn_message'] = 1;
    return;
  }
  $id = $_GET['id'];
  $sql = "delete from message where id in ({$id})";
  $result = blog_update($sql);
  if(!$result){
    $GLOBALS['warn_message'] = 1;
    return;
  }
  header('Location:'.$_SERVER['HTTP_REFERER']);
}
function get_person_message(){
  // 连接数据库；
  // 查询数据；
  // 响应
  global $where;
  if($GLOBALS['total']>0){
    $skip = $GLOBALS['skip'];
    $per_list =  $GLOBALS['$per_list'];
    $sql = "select id,
    message,
    send_time,
    read_status 
    from message 
    where $where order by id desc limit {$skip},{$per_list}";
    $GLOBALS['result'] = blog_select_all($sql);
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="renderer" content="webkit" />
  <meta name="force-renderer" content="webkit" />
  <meta http-equiv="X-UA-Compatible" content="IE=Edge chrome=1" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0, shrink-to-fit=no" />
  <meta name="apple-mobile-web-app-title" content="大思考博客" />
  <meta http-equiv="Cache-Control" content="no-siteapp" />
  <meta name="referrer" content="always">
  <meta name="format-detection" content="telephone=no,email=no,adress=no">
  <title>大思考-个人中心</title>
  <meta name="keywords" content="大思考,大思考博客,前端开发,前端开发博客" />
  <meta name="description" content="大思考博客是一个分享前端开发相关知识的博客网站" />
  <link rel="stylesheet" href="/lib/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="/lib/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="/css/topbar.css">
  <link rel="stylesheet" href="/css/sidebar.css">
  <link rel="stylesheet" href="/css/footer.css">
  <link rel="stylesheet" href="/css/person_nav.css">
  <link rel="stylesheet" href="/css/person_message.css">
  <link rel="stylesheet" href="/css/public.css">
  <link rel="stylesheet" href="/css/pagination.css">
</head>
<body>
<?php include $root_path.'/static/topbar.php'?>
  <main class="blog_main container">
    <section class="mainbar">
    <?php $current_nav = 'person_message'?>
    <?php include $root_path.'/static/person_nav.php'?>
      <div class="person_detail" id="person">        
        <!-- 我的评论页面 -->
        <div class="person_message">
          <h4 class="underline hidden-xs">我的消息</h4>
          <?php if(isset($total)):?>
          <div class="total">
            <a href="<?php echo $_SERVER['PHP_SELF']?>" class="btn btn-default delete_message" disabled>删除</a>
            <p>共有<strong><?php echo $total?></strong>条消息</p>
          </div> 
          <?php endif?>
          <table class="table table-hover table-striped table_message">
            <thead>
              <tr>
                <th class="check"><input type="checkbox" class="check_total" /></th>
                <th class="message">主题</th>
                <th class="time">时间</th>
              </tr>
            </thead>
            <tbody>
              <?php if(!empty($total)):?>
              <?php if(!empty($result)):?>
              <?php foreach($result as $key => $item):?>
              <tr <?php echo $item['read_status'] == 1 ? '' : 'class="read"' ?> id="<?php echo $item['id']?>">
                <td><input type="checkbox" class="check_item" /></td>
                <td class="message ellipsis">
                  <a href="javascript:;" class="read_message">
                    <span class="fa fa-envelope email"></span>
                    <?php echo $item['message']?>
                  </a>
                </td>
                <td class="time ellipsis"><?php echo $item['send_time']?></td>
              </tr>
              <?php endforeach?>
              <?php endif?>
              <?php else:?>
              <tr class="no_message">
                <td colspan="3">您当前还没有消息喔！</td>  
              </tr>
              <?php endif ?>
            </tbody>
          </table>
      </div>
      <?php include $root_path.'/static/pagination.php'?>
    </section>
  </main>
  <?php include $root_path.'/static/footer.php'?>
  <script src="/lib/jquery/jquery.min.js"></script>
  <script src="/lib/bootstrap/js/bootstrap.min.js"></script>
  <script src="/lib/artDialog-master/dialog.js"></script>
  <script src="/lib/art-template/template-web.js"></script>
  <script src="/js/person_message.js"></script>
  <script src="/js/topbar.js"></script>
  <script>
  $(function(){
    var warn_message = <?php echo !empty($warn_message) ? 1 : 2 ?>;
    var is_delete_success = <?php echo !empty($warn_message) ? 1 : 2 ?>;
    if(warn_message == 1)  {
      // 删除成功
      if(is_delete_success == 1){
        title = '抱歉';
        content = '消息删除失败,请稍后重试!';
      }
      warn(title,content);
    }
    function warn(title, content) {
    var d = dialog({
      title: title,
      content: content,
      cancel: false,
      ok: function() {},
      quickClose: true
    });
    d.show(document.getElementById('option-quickClose'));
    setTimeout(function() {
      d.close().remove();
    }, 30000);
  };
  })
  </script>
</body>
</html>