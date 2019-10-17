// 点击回复，弹出回复框

$(function() {
  $('.comment-detail').on('click', '.reply-switch', function() {
    if ($(this).html() == '<span class="fa fa-comment-o"></span> 回复') {
      $('.reply-switch').html('<span class="fa fa-comment-o"></span> 回复');
      $('.reply-switch').css('color', '#adadad');
      $('.reply_form').hide();
      $(this).html('<span class="fa fa-comment-o"></span> 收起');
      $(this).css('color', '#17a2b8');
      // console.log($(this).parent().find('.reply_form'));
      $(this).parent().find('.reply_form').show();
    } else {
      $(this).html('<span class="fa fa-comment-o"></span> 回复');
      $(this).parent().find('.reply_form').hide();
    }
  })

  // 限制评论栏文本框输入字符数100个字符 
  // 所有的填写评论的文本框都限制字符数
  $('.comment').on('change', '.form-comment', function() {
    textCounter($(this), 100);
  })
  $('.comment').on('keyup', '.form-comment', function() {
    textCounter($(this), 100);
  })

  function textCounter(element, max) {
    var message = element.val();
    if (message.length > max) {
      var message = message.substring(0, max);
      element.val(message);
    } else {
      element.val(message);
    }
  }
  // 验证用户是否登陆
  // 填写评论内容或者是提交评论之前，还有提交评论之后都要获取该用户是否登陆
  // 发表评论的按钮用户登录后才能让其可点击，否则是不可点击的
  $('.form-comment').on('focus', function() {
    if ($(this).val() == '来说几句吧....') {
      $(this).val('');
    }
  })
  $('.form-comment').on('blur', function() {
    if ($(this).val() == '') {
      $(this).val('来说几句吧....');
    }
  })
})