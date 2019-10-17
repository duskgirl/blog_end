$(function() {
  $('.register').bootstrapValidator({
    message: 'This value is not valid',
    feedbackIcons: {　　
      valid: 'glyphicon glyphicon-ok',
      invalid: 'glyphicon glyphicon-remove',
      validating: 'glyphicon glyphicon-refresh'
    },
    fields: {
      email: {
        verbose: false,
        threshold: 2,
        validators: {
          notEmpty: {
            message: '邮箱地址不能为空'
          },
          emailAddress: {
            message: '邮箱地址格式有误'
          },
          remote: {
            url: '/user/checkUnique.php',
            message: '当前邮箱已存在，请直接去<a href="/user/login.php">登录</a>吧',
            delay: 2000,
            type: 'POST',
            data: function() {
              return {
                email: $.trim($('.register').find('.form-email').val())
              }
            }
          }
        }
      },
      username: {
        verbose: false,
        threshold: 2,
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
          },
          remote: {
            url: '/user/checkUnique.php',
            message: '当前用户名已被注册，请换个用户名试试吧',
            delay: 2000,
            type: 'POST',
            data: function() {
              return {
                username: $.trim($('.register').find('.form-username').val())
              }
            }
          }
        }
      },
      password: {
        message: '密码验证失败',
        threshold: 2,
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
        validators: {
          notEmpty: {
            message: '确认密码不能为空',
          },
          identical: {
            field: 'password',
            message: '两次输入的密码不一致'
          }
        }
      }
    }
  })
})