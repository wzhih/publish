<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Url;

return [
    // +----------------------------------------------------------------------
    // | 应用设置
    // +----------------------------------------------------------------------

    // 应用命名空间
    'app_namespace'          => 'app',
    // 应用调试模式
    'app_debug'              => true,
    // 应用Trace
    'app_trace'              => false,
    // 应用模式状态
    'app_status'             => '',
    // 是否支持多模块
    'app_multi_module'       => true,
    // 入口自动绑定模块
    'auto_bind_module'       => false,
    // 注册的根命名空间
    'root_namespace'         => [],
    // 扩展函数文件
    'extra_file_list'        => [THINK_PATH . 'helper' . EXT],
    // 默认输出类型
    'default_return_type'    => 'html',
    // 默认AJAX 数据返回格式,可选json xml ...
    'default_ajax_return'    => 'json',
    // 默认JSONP格式返回的处理方法
    'default_jsonp_handler'  => 'jsonpReturn',
    // 默认JSONP处理方法
    'var_jsonp_handler'      => 'callback',
    // 默认时区
    'default_timezone'       => 'Asia/Shanghai',
    // 是否开启多语言
    'lang_switch_on'         => false,
    // 默认全局过滤方法 用逗号分隔多个
    'default_filter'         => '',
    // 默认语言
    'default_lang'           => 'zh-cn',
    // 应用类库后缀
    'class_suffix'           => false,
    // 控制器类后缀
    'controller_suffix'      => false,

    // +----------------------------------------------------------------------
    // | 模块设置
    // +----------------------------------------------------------------------

    // 默认模块名
    'default_module'         => 'index',
    // 禁止访问模块
    'deny_module_list'       => ['common'],
    // 默认控制器名
    'default_controller'     => 'Index',
    // 默认操作名
    'default_action'         => 'index',
    // 默认验证器
    'default_validate'       => '',
    // 默认的空控制器名
    'empty_controller'       => 'Error',
    // 操作方法后缀
    'action_suffix'          => '',
    // 自动搜索控制器
    'controller_auto_search' => false,

    // +----------------------------------------------------------------------
    // | URL设置
    // +----------------------------------------------------------------------

    // PATHINFO变量名 用于兼容模式
    'var_pathinfo'           => 's',
    // 兼容PATH_INFO获取
    'pathinfo_fetch'         => ['ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL'],
    // pathinfo分隔符
    'pathinfo_depr'          => '/',
    // URL伪静态后缀
    'url_html_suffix'        => 'html',
    // URL普通方式参数 用于自动生成
    'url_common_param'       => false,
    // URL参数方式 0 按名称成对解析 1 按顺序解析
    'url_param_type'         => 0,
    // 是否开启路由
    'url_route_on'           => true,
    // 路由使用完整匹配
    'route_complete_match'   => false,
    // 路由配置文件（支持配置多个）
    'route_config_file'      => ['route'],
    // 是否强制使用路由
    'url_route_must'         => false,
    // 域名部署
    'url_domain_deploy'      => false,
    // 域名根，如thinkphp.cn
    'url_domain_root'        => '',
    // 是否自动转换URL中的控制器和操作名
    'url_convert'            => true,
    // 默认的访问控制器层
    'url_controller_layer'   => 'controller',
    // 表单请求类型伪装变量
    'var_method'             => '_method',
    // 表单ajax伪装变量
    'var_ajax'               => '_ajax',
    // 表单pjax伪装变量
    'var_pjax'               => '_pjax',
    // 是否开启请求缓存 true自动缓存 支持设置请求缓存规则
    'request_cache'          => false,
    // 请求缓存有效期
    'request_cache_expire'   => null,
    // 全局请求缓存排除规则
    'request_cache_except'   => [],

    // +----------------------------------------------------------------------
    // | 模板设置
    // +----------------------------------------------------------------------

    'template'               => [
        // 模板引擎类型 支持 php think 支持扩展
        'type'         => 'Think',
        // 模板路径
        'view_path'    => '',
        // 模板后缀
        'view_suffix'  => 'html',
        // 模板文件名分隔符
        'view_depr'    => DS,
        // 模板引擎普通标签开始标记
        'tpl_begin'    => '{',
        // 模板引擎普通标签结束标记
        'tpl_end'      => '}',
        // 标签库标签开始标记
        'taglib_begin' => '{',
        // 标签库标签结束标记
        'taglib_end'   => '}',
    ],

    // 视图输出字符串内容替换
    'view_replace_str'       => [
//        '__PUBLIC__'=>'/publish/public/',
        '__PUBLIC__'=>'/',
    ],
    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'  => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',
    'dispatch_error_tmpl'    => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',

    // +----------------------------------------------------------------------
    // | 异常及错误设置
    // +----------------------------------------------------------------------

    // 异常页面的模板文件
    'exception_tmpl'         => THINK_PATH . 'tpl' . DS . 'think_exception.tpl',

    // 错误显示信息,非调试模式有效
    'error_message'          => '页面错误！请稍后再试～',
    // 显示错误信息
    'show_error_msg'         => false,
    // 异常处理handle类 留空使用 \think\exception\Handle
    'exception_handle'       => '',

    // +----------------------------------------------------------------------
    // | 日志设置
    // +----------------------------------------------------------------------

    'log'                    => [
        // 日志记录方式，内置 file socket 支持扩展
        'type'  => 'File',
        // 日志保存目录
        'path'  => LOG_PATH,
        // 日志记录级别
        'level' => [],
    ],

    // +----------------------------------------------------------------------
    // | Trace设置 开启 app_trace 后 有效
    // +----------------------------------------------------------------------
    'trace'                  => [
        // 内置Html Console 支持扩展
        'type' => 'Html',
    ],

    // +----------------------------------------------------------------------
    // | 缓存设置
    // +----------------------------------------------------------------------

    'cache'                  => [
        // 驱动方式
        'type'   => 'File',
        // 缓存保存目录
        'path'   => CACHE_PATH,
        // 缓存前缀
        'prefix' => '',
        // 缓存有效期 0表示永久缓存
        'expire' => 0,
    ],

    // +----------------------------------------------------------------------
    // | 会话设置
    // +----------------------------------------------------------------------

    'session'                => [
        'id'             => '',
        // SESSION_ID的提交变量,解决flash上传跨域
        'var_session_id' => '',
        // SESSION 前缀
        'prefix'         => 'think',
        // 驱动方式 支持redis memcache memcached
        'type'           => '',
        // 是否自动开启 SESSION
        'auto_start'     => true,
    ],

    // +----------------------------------------------------------------------
    // | Cookie设置
    // +----------------------------------------------------------------------
    'cookie'                 => [
        // cookie 名称前缀
        'prefix'    => '',
        // cookie 保存时间
        'expire'    => 0,
        // cookie 保存路径
        'path'      => '/',
        // cookie 有效域名
        'domain'    => '',
        //  cookie 启用安全传输
        'secure'    => false,
        // httponly设置
        'httponly'  => '',
        // 是否使用 setcookie
        'setcookie' => true,
    ],

    //分页配置
    'paginate'               => [
        'type'      => 'bootstrap',
        'var_page'  => 'page',
        'list_rows' => 15,
    ],

    //融云
    'APP_KEY' => 'bmdehs6pb1fxs',
    'APP_SECRET' => 'N1dQuczgFS',

    //阿里云短信
    'AccessKeyID' => 'LTAIobhHocXhN0Cw',
    'AccessKeySecret' => 'LzY422JoVFYA4uXnpyBzbSLvLEEFpJ',

    //阿里云OSS
    'OSSKeyID' => 'LTAIbfeDHJ2HLIBN',
    'OSSKeySecret' => 'usdIxrKme3Iff29shlXmc7lOgHtznM',
    'Endpoint' => 'oss-cn-shenzhen.aliyuncs.com',
    'Bucket' => 'hr-publish',

    //支付宝
    'alipay'                 => [
        //应用ID,您的APPID。
        'app_id' => "2016082000295788",

        //商户私钥
        'merchant_private_key' => "MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCHrQuUjePPE0tnG9Kx3TlQ/mqep8CJhU5LGv3hlUJOVqlQp3004fy4cMdgmyk7uQ/zM1fgdlbBQQJV62Vvy4OOd46QyrDhB8em71ecIMFtseynCtMtdefWL2f9ubaH8qzbGpf+IEO4ijYqr9a5ePVXdQRh3/N1pxQ68GJ521+ldzJ/L5XR5laXadJRReQmNBvgFug6N1QLhr2jdGGRtdyuZRdJLjzcFobBHa0giB2REeQvOkkatmexUk+G8pHa+3F1zkP9wUgKnkk7HbG6Mj5jWTGIEZgZpA3KKH3mnJk2zB2qimckgWcascrm6Qoz908MrV+CktMiGdSa4mSESe/BAgMBAAECggEANYLSpmywBOQfQTOADhaqnH87ngebsKGbF5Q1vdsYo70aWm00vL7E5hnVCQ0pXhzHZaxCZI1H+kChozGMeGNUJ8SPvhuzV42x+O09KJ7iq1kZdWiXkb6HCbr40OGGVGgqNOBwQhKHoykf5AYmMlC6sxu79r5VT3nuSLC2pkkXmDKsiurhZ31o25AN4J7jZDZlcFBJJ6WWr/ktTV21sCRPc2LY1CT03rnQQpwL/QZ1RkSFptSpl7dS1e7Mqnihs53qBdxgHlbXgRKGQQKOCEHDFXZRdw0E+CFesiX+STBvVdETztn7tcKAFDL30LXzDxNNkctLcvp1Zr2oyVrkJIcX4QKBgQDnVCZr9lCneGu9BOueaAT0wtM1huXSZUg0RnnKKYqmff1wfLzHULvLJz1aX2BpoLmfs49r4nVYP2ZxtmmGe72drg0eA69Aty2IqKOIvWYGhgpYdXwQBir+kWEBuJics7PrahvlFaywnG0kG5TaTJIAE+De+L6rFDcbT8X4xTur/wKBgQCWJVY2v8kuhGan3x+Ffor3giZeFLUePKD4YdyINy7wi/mOU2XhlLeGJ5SXR6rjlwg0NQxlI6UGwbE1bt5WIBjZXk/dsKHbWlBGCzgrQeP0cgNt5aygqpXpZKCoKW7JWGJXfdvSzF9kGrjYhAqzidak43oMZpdNXF/qbWHacJVkPwKBgQDMRCRBRRlnKBCKsiOUlul5b+es5ldd941Qi8LTXudNqQb4I01W+tU4yIeGm5245/HBMX89lpRjvmgplReNZwrCh4SRslM4ZAfTGoZ9IjFLJWVRQzyvsaKZc6ojKOupw4zmHaZQHVmGAjrlnW1Nbjul0efJPACxdIJMnZ6E0zSj5QKBgC00yKRrUhNjYdUhZMg4tXaYBR1GdtHHx1+Bd3i7xLJAMr8bdQ1aTXdi62Yw+c7UZm/xmO3KsaE7KDPCUcb0mwa3UqyYxeLZalnsftjnWc77lPS9tiAZvFEtLwHyl5yfs9xL1Ke/SOlG8mieOOqdkbbLlq+tI9jy+x4GGPQ/+XrZAoGATq19pwbvCSUZXqHEWkbR8If4meNsDC1GEDKpfwXFAsRg8u9zJOaOEOJz/iX06qDxokWoo8a0mnryT1BxMgf06S2tf7jd+rGk699f8/q2UJG422j0/lxCchyQiMwY6PpIC2ZMFSOl2FZ9PT5ZYx5h3+RNt2AnUtBghIhuhpDJ4eM=",

        //异步通知地址
        'notify_url' => Url::build('index/Payment/notify_url'),

        //同步跳转
        'return_url' => Url::build('index/index/User/orderList'),

        //编码格式
        'charset' => "UTF-8",

        //签名方式
        'sign_type'=>"RSA2",

        //支付宝网关
        'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",

        //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
        'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA1N8nTAoojV2bfWMeshQ7zGx7T0vaYLcFixbFUlH3vJNigjQnxkn4NqlCoA0RLXQLOxWC6LFg57IGj5FUrc92iC+KmuVHdmSH3chaVDt2vZyqR2QA15UUIebXDwbHsihn4sNVx3sED8E0oXfVa+xtHmE3fOt/25ZKUhfoFMT1IwWJJ5F1D8YUxbZZ7g9SiF8vKaEsDmdY2ZZ5ZdJy4vGlHU8tVjBYNGHx4O/AtNSDsmRJpxvZfphA5OfrQt9Uto1R0ZDjI2X5lZfiV0kN7jJkKFCJeI68ngnLAjWiETXS9Rov8VItjFExZUY+ix2Kxwj6jPDmirtLjD0hNP3bJaHWzQIDAQAB",
    ]
];
