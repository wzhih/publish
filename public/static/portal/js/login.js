/**
 * Created by Administrator on 2017/6/16 0016.
 */
$(function () {
  $('.user .input-group').append('<em></em>');
  $('.author_area').append('<em></em>');
  
  /** 验证码初始化 **/
  verCode('.verCodeImg');
  
  /*
   * 复选框
   */
  $('.plus_checkbox input[type=checkbox]').bind('click', function () {
    if ($(this).is(':checked')) {
      $(this).next('div').removeClass('active').append('<i class="icon iconfont icon-check"></i>');
    } else {
      $(this).next('div').empty();
    }
  });
  $('.plus_checkbox input[type=checkbox]:checked').next('div').removeClass('active').append('<i class="icon iconfont' +
    ' icon-check"></i>');
  
  $('.get_password').on('click', function () {
    $('.dialog input').val('');
    $('.input-group').removeClass('warning');
    $('.input-group em').text('');
    $('.dialog').show();
    $('.verCodeImg').click();
  });
  $('.dialog .closed').on('click', function () {
    $('.dialog').hide();
    $('.verCodeImg').click();
  });
});

/** 验证 **/
/** 手机号码验证 **/
function phoneVer(param) {
  var myreg = /^(13[0-9]|15[012356789]|17[013678]|18[0-9]|14[57])[0-9]{8}$/;
  var $this = $(param).parents('.input-group');
  if ($(param).val() == '') {
    $this.find('em').text('手机号码不能为空！');
    $this.addClass('warning');
    return false;
  } else if (!myreg.test($(param).val())) {
    $this.find('em').text('请输入有效手机号码！');
    $this.addClass('warning');
    return false;
  } else {
    $this.find('em').text('');
    $this.removeClass('warning');
    return true;
  }
}

/** 密码长度 **/
function passwordLen(param) {
  if (param.length == 0) {
    return '请输入密码！'
  } else if (param.length < 6 || param.length > 20) {
    return '密码为6-20位！';
  } else {
    return true;
  }
}

/** 密码验证 **/
function passwordVer(param) {
  var $this = $(param).parents('.input-group');
  var thisFlag = passwordLen($(param).val());
  if (thisFlag != true) {
    $this.find('em').text(thisFlag);
    $this.addClass('warning');
    return false;
  } else {
    $this.find('em').text('');
    $this.removeClass('warning');
    return true;
  }
}

/** 确认密码 **/
function passwordVerAgain(param) {
  var $this = $(param).parents('.input-group');
  var thisFlag = passwordLen($(param).val());
  var password = $(param).parents('.input-group').prev('.input-group').find('input[type=password]').val();
  if (thisFlag != true) {
    $this.find('em').text(thisFlag);
    $this.addClass('warning');
    return false;
  } else if ($(param).val() != password) {
    $this.find('em').text('两次密码不同！');
    $this.addClass('warning');
    return false;
  } else {
    $this.find('em').text('');
    $this.removeClass('warning');
    return true;
  }
}

//图片验证码
function verCode(imgName) {
  $(imgName).attr('src', '/index/login/getchaptcha?v=' + Math.random());
}


/** 验证码是否为空 **/
function verNull(param) {
  if ($(param).val() == '') {
    $(param).parents('.input-group').find('em').text('验证码不能为空');
    $(param).parents('.input-group').addClass('warning');
    return false;
  } else {
    $(param).parents('.input-group').find('em').text('');
    $(param).parents('.input-group').removeClass('warning');
    return true;
  }
}

/** 手机动态码是否为空 **/
function codeNull(param) {
  if ($(param).val() == '') {
    $(param).parents('.input-group').find('em').text('动态码不能为空');
    $(param).parents('.input-group').addClass('warning');
    return false;
  } else {
    $(param).parents('.input-group').find('em').text('');
    $(param).parents('.input-group').removeClass('warning');
    return true;
  }
}


/** 动态码 **/
function phoneCode(param) {
  var time = 60;
  if ($(param).text().trim() == '获取动态码') {
    $(param).attr("disabled", true).removeClass('codeActive').addClass('codeClick');
    $(param).text(time + 's后重新获取');
    var timer = setInterval(function () {
      if (time > 1) {
        time--;
        $(param).text(time + 's后重新获取');
      } else {
        clearInterval(timer);
        $(param).text('获取动态码').attr("disabled", false).removeClass('codeClick').addClass('codeActive');
      }
    }, 1000);
  }
}

/** 注册协议 **/
function labelCheck() {
  var _flag = $('#regLabel').is(':checked');
  if(!_flag){
    $('#regLabel').next('div').addClass('active').empty();
  }
  return _flag;
}

//作者投稿页面验证

// 手机号码验证
function authorVer(param) {
  var myreg = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
  var $this = $(param).parents('.author_area');
  if ($(param).val() == '') {
    $this.find('em').text('手机号码不能为空！');
    $this.addClass('warning');
    return false;
  } else if (!myreg.test($(param).val())) {
    $this.find('em').text('请输入有效手机号码！');
    $this.addClass('warning');
    return false;
  } else {
    $this.find('em').text('');
    $this.removeClass('warning');
    return true;
  }
}

//E-mail地址验证
function emailVer(param) {
  var reg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;
  var text = $(param).val();
  if (!reg.test(text)) {
    $(param).parents('.author_area').find('em').text('请输入正确的E-mail地址！');
    $(param).parents('.author_area').addClass('warning');
    return false;
  }else{
    $(param).parents('.author_area').find('em').text('');
    $(param).parents('.author_area').removeClass('warning');
    return true;
  }
}

//输入内容是否为空判断
function areaNull(param) {
  if ($(param).val() == '') {
    $(param).parents('.author_area').find('em').text($(param).attr('placeholder'));
    $(param).parents('.author_area').addClass('warning');
    return false;
  } else {
    $(param).parents('.author_area').find('em').text('');
    $(param).parents('.author_area').removeClass('warning');
    return true;
  }
}