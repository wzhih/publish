; (function($, window, undefined) {
  
  var _id_base = Math.ceil(Math.random() * 1000000); //id

  var StarRate = function($ele, opt) {
    this.$ele = $ele;//jq选中的元素
    this.defaults = {//默认配置
      size: 5,//总星数
      onEvent: true,//是否可点击
      activeSize: 5,//激活的星星数目
      starSize: 16,//设置星星的大小，单位rem，和字体单位一样
      activeColor: '#F8B551',//激活的颜色
      normalColor: '#ccc'//正常态颜色
    };
    this.options = $.extend({}, this.defaults, opt);//全部配置
    this.id = $ele.attr('id');
    this.rate = this.options.activeSize;
    this.init();
  };

  StarRate.prototype.init = function() {
    var me = this;
    

    
    //添加元素
    var htmlActive = '<div class="rateXXSAQ iconfont is-showXXSAQ">&#xe730;</div>';//点亮的星星
    var htmlNormal = '<div class="rateXXSAQ iconfont">&#xe730;</div>';//没有点亮的星星
    var $docFrag = $(document.createDocumentFragment());
    for (var i = 0; i < me.options.activeSize; i++) {
      var $itemA = $(htmlActive).css('color',me.options.activeColor);
      $docFrag.append($itemA);
    }
    for(var j = me.options.activeSize; j < me.options.size; j++) {
      var $itemN = $(htmlNormal).css('color', me.options.normalColor);
      $docFrag.append($itemN);
    }
    me.$ele.append($docFrag);
    
    //设置初始样式
    me.initCss();
    
    //添加事件
    if(me.options.onEvent) {
      me.initEvent();   
    }
    
  };
  
  StarRate.prototype.initCss = function () {
    var me = this;
    var $ele = me.$ele;
    $ele.attr('id', me.id); // 设置id
    $ele.addClass('rate-boxXXSAQ');
    $ele.addClass('clear-fix');
    $ele.css({
      width: (me.options.starSize * me.options.size) + 'rem',
      height: me.options.starSize + 'rem',
      lineHeight: me.options.starSize + 'rem'// 浮动问题
    });
    $ele.find('.rateXXSAQ').css({
      fontSize: me.options.starSize + 'rem',      
    });
   
    
  };

  StarRate.prototype.getRate = function() {
    var me = this;
    return me.rate;
  }
  
  StarRate.prototype.initEvent = function() {
    var me = this;
    console.log($('#' + me.id + ' .rate-boxXXSAQ .rateXXSAQ'));
    $('#' + me.id + ' .rateXXSAQ').on('click', function() {
      //如果元素是可见的
      if ($(this).is('.is-showXXSAQ')) {
        //元素是最后一个
        if (!$(this).next().length) {
          $(this).removeClass('is-showXXSAQ');
          $(this).css('color', me.options.normalColor);
          me.rate--;
          return;
        }
        if ($(this).next().is('.is-showXXSAQ')) {
          //元素不是最高分，减去大于该元素的元素
          for (var $item = $(this).next(); $item.length; $item = $item.next()) {
            $item.removeClass('is-showXXSAQ');
            $item.css('color', me.options.normalColor);
            me.rate--;
          }

        } else {
          //元素是最高分,减去该元素
          $(this).removeClass('is-showXXSAQ');
          $(this).css('color', me.options.normalColor);
          me.rate--;
        }
      } else {
        //元素是不可见的
        for (var $item1 = $(this); $item1.length; $item1 = $item1.prev()) {
          $item1.addClass('is-showXXSAQ');
          $item1.css('color', me.options.activeColor);
          me.rate++;
        }
      }
    });
  };






  $.fn.starRate = function(options) {
    var me = this;
    return new StarRate(me, options);
    //不支持链式调用
  };

})(jQuery, window);