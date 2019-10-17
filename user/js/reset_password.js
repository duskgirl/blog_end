$(function() {
  $('.reset-password').bootstrapValidator({
    message: 'This value is not valid',
    feedbackIcons: {　　
      valid: 'glyphicon glyphicon-ok',
      invalid: 'glyphicon glyphicon-remove',
      validating: 'glyphicon glyphicon-refresh'
    },
    fields: {
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