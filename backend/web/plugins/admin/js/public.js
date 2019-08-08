/**
 * Created by Administrator on 2017/6/29.
 */
function get_city(t){
    var parent_id = $(t).val();
    if(!parent_id > 0){
        return;
    }
    $.ajax({
        type : "GET",
        url  : "/admin.php?admin-orders/getregion",
        data:{'parent_id':parent_id},
        error: function(request) {
            alert("服务器繁忙, 请联系管理员!");
            return;
        },
        success: function(v) {

            v = '<option value="">城市</option>'+ v;
            $('#city').empty().html(v);
        }
    });
}