var demo = angular.module("demo", ["RongWebIMWidget"]);

demo.controller("main", ["$scope", "WebIMWidget", "$http", function($scope, WebIMWidget, $http) {

    $scope.targetType = 1; //1：私聊 更多会话类型查看http://www.rongcloud.cn/docs/api/js/global.html#ConversationType
    // $scope.targetId = userId;

    //注意实际应用中 appkey 、 token 使用自己从融云服务器注册的。
    var config = {
        appkey: appk,
        token: token,
        displayConversationList: true,
        style:{
            width:450,
            positionFixed:true,
            left:0,

        },
        onSuccess: function(id) {
            $scope.user = id;
            console.log('连接成功：' + id);
        },
        onError: function(error) {
            console.log('连接失败：' + error);
        }
    };
    RongDemo.common(WebIMWidget, config, $scope, $http);

}]);

