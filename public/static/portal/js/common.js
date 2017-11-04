/**
 * Created by Administrator on 2017/6/22 0022.
 */
/*加载图书列表*/
function getBookList(contentner,array){//contentner:容器   bookTagId:图书tagId
    console.log(JSON.stringify(array));
    $.post(getBooks,array,function(data) {
        var loading = '<div class="demo-spin-col"><span class="demo-spin-icon-load"></span><p>数据加载中，请稍候…</p></div>';
        $(contentner).empty();
        $(contentner).append(loading);
        if(data.status){
            var datas = data.data;
            if(datas.length>0){
                var ele="";
                for(var i= 0;i<datas.length;i++){
                    ele+='<li class="item" title="'+datas[i].name+'">';
                    if(i+1>7){
                        ele+='<a href='+ getBook +'?bookId='+datas[i].id+' target="_blank" style="background:url('+ _public +'static/portal/img/bg/bg'+((i+1)%7)+'.png)">';
                    }else{
                        ele+='<a href='+ getBook +'?bookId='+datas[i].id+' target="_blank" style="background:url('+ _public +'static/portal/img/bg/bg'+(i+1)+'.png)">';
                    }

                    ele+='<img src="'+datas[i].cover+'" class="book-img-shadow"/></a>';
                    ele+='<p><a target="_blank"  href='+ getBook +'?bookId='+datas[i].id+'>'+datas[i].name+'</a></p><p><a href='+ getBook +'?bookId='+datas[i].id+'">￥'+datas[i].price+'</a></p></li>';

                }
                $(contentner).empty();
                $(contentner).append(ele);
            }
        }else{
            alert(data.msg);
        }

    });
}

