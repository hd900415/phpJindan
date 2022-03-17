var page = 1,
    order_by = "default",
    order_way = "",
    isLoading = false,
    hasMore = true,
    listId = "#goods_list",
    moreId = "#wx_loading",
    moreURL = document.location.href;

function load_more(url, data) {
    query_arg = data;
    if (isLoading || !hasMore) return;
    isLoading = true;
    if (data.p == 1) {
        $(listId).html("")
    }
    $(moreId).show();
    $.ajax({
        url: url,
        dataType: "json",
        data: data,
        type: "POST",
        error: function () {
            isLoading = false;
            //$(moreId).hide();
            //$.ui.dialog.tip({text:'加载失败,请重试',time:1.5*1000});
            $.ui.dialog.alert({
                tip: "网络不给力，加载失败！",
                btn: {
                    val: "点击重新加载",
                    handler: function (t) {
                        load_more(url, data)
                    }
                }
            });
        },
        success: function (res) {
            if (res.status) {
                page++;
                if (res.map.total == 0) {
                    $(listId).html(res.content);
                } else {
                    if (res.map.page == 1) {
                        $(listId).html(res.content);
                    } else {
                        $(res.content).appendTo($(listId));
                    }
                }
                if (res.map.page >= res.map.totalpage) {
                    hasMore = false;
                } else {
                    hasMore = true
                }
                $(moreId).hide();
                isLoading = false;
                var zy_load = new zooyoo_load(200, 200);
                zy_load.init()
                window.onscroll = function () {
                    zy_load.init();
                }
                //window.onresize = function(){zy_load.init();}
            }
        }
    });
}