(function () {
/*
    将相同代码拆出来方便维护
 */
window.RongDemo = {
    common: function (WebIMWidget, config, $scope, $http) {
        WebIMWidget.init(config);

        $scope.setconversation = function (uid, uname) {
            if(uid) {
                $scope.targetId = String(uid);
                $scope.name = uname;
            }
            if (!!$scope.targetId) {
                WebIMWidget.setConversation(Number($scope.targetType), $scope.targetId, "用户：" + $scope.name);
                WebIMWidget.show();
            }
        };

        $scope.show = function() {
            WebIMWidget.show();
        };

        $scope.hidden = function() {
            WebIMWidget.hidden();
        };

        WebIMWidget.hidden();


        // 示例：获取 userinfo.json 中数据，根据 targetId 获取对应用户信息
        WebIMWidget.setUserInfoProvider(function(targetId,obj){
            $http({
                url:userListUrl
            }).success(function(rep){
                var user;
                rep.userlist.forEach(function(item){
                    if(item.id==targetId){
                        user=item;
                    }
                })
                if(user){
                    obj.onSuccess({id:user.id,name:user.name,portraitUri:user.portraitUri});
                }else{
                    obj.onSuccess({id:targetId,name:"用户列表"});
                }
            })
        });

        // 示例：获取 online.json 中数据，根据传入用户 id 数组获取对应在线状态
        // WebIMWidget.setOnlineStatusProvider(function(arr, obj) {
        //     $http({
        //         url: online
        //     }).success(function(rep) {
        //         obj.onSuccess(rep.data);
        //     })
        // });

        WebIMWidget.onClose=function(){
            console.log("已关闭");
        }

    }
}

})()