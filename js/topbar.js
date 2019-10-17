$(function() {
  // 菜单栏弹出
  $('.nav_toggle').click(function() {
    $(this).hide();
    $('.blog_nav_content').stop().animate({
      left: 0,
    }, 800)
  });
  // 菜单栏关闭
  $('.blog_nav_close').click(function() {
      $('.blog_nav_content').stop().animate({
        left: '-200px',
      }, 800, function() {
        $('.nav_toggle').show();
      })
    })
    // 登录后个人中心下拉框的显示和隐藏
  $('.is_login').hover(function() {
    $('.person_menu').show();
  }, function() {
    $('.person_menu').hide();
  })
})