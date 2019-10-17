$(function() {
  $('.person_nav_item').on('click', function() {
    $url = $(this).find(a).attr('href');
    $('#person').load(url + '#person>*');
    return false;
  });
  // 头像处做区域滚动
  // 区域滚动:iScroll.js;
  var myScroll;
  myScroll = new IScroll('#wrapper', {
    probeType: 2,
    mouseWheel: true
  });
  // 设置用户信息初校验
  $('.modify').bootstrapValidator({
    message: 'This value is not valid',
    feedbackIcons: {　　
      valid: 'glyphicon glyphicon-ok',
      invalid: 'glyphicon glyphicon-remove',
      validating: 'glyphicon glyphicon-refresh'
    },
    fields: {
      username: {
        verbose: false,
        message: '用户名验证失败',
        validators: {
          notEmpty: {
            message: '用户名不能为空'
          },
          stringLength: {
            min: 2,
            max: 10,
            message: '用户名长度必须在2到10位之间'
          },
          regxp: {
            regexp: /^[a-zA-Z0-9_]+$/,
            message: '用户名只能包含大写，小写，数字和下划线'
          }
        }
      },
      password: {
        verbose: false,
        message: '密码验证失败',
        validators: {
          notEmpty: {
            message: '密码不能为空',
          },
          stringLength: {
            min: 6,
            max: 10,
            message: '密码长度必须在6到10位之间'
          },
          regxp: {
            regexp: /^[a-zA-Z0-9_]+$/,
            message: '密码只能包含大写，小写，数字和下划线'
          }
        }
      },
      repassword: {
        verbose: false,
        validators: {
          notEmpty: {
            message: '确认密码不能为空',
          },
          stringLength: {
            min: 6,
            max: 10,
            message: '密码长度必须在6到10位之间'
          },
          identical: {
            field: 'password',
            message: '两次输入的密码不一致'
          }
        }
      }
    }
  });
  $('.selected').on('click', function() {
    $(this).parents('ul').find('img').removeClass('active');
    $(this).parents('li').find('img').addClass('active');
  });
})