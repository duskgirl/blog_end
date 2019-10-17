$(function() {
  $('.forget_password').bootstrapValidator({
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
            message: '该邮箱尚未注册',
            type: 'POST',
            data: {
              email: function() {
                return $.trim($('.register').find('.form-email').val())
              },
              unique: function() {
                return false
              }
            }
          }
        }
      }
    }
  })
})