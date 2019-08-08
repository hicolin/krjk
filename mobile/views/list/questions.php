<?php
use yii\helpers\Url;
use common\controllers\PublicController;
?>
<?php $this->beginBlock('header'); ?>
<!-- <head></head>中代码块 -->
<?php $this->endBlock(); ?>
<?php $this->beginContent('@app/views/layouts/header.php')?>
<?php $this->endContent();?>
<!--main-->
<div class="new_kou_main">

    <div class="nkm_con">
        <div class="nkm_con_head">
            <ul>

                    <li class="curr">
                        <a style="font-size: 0.7rem;">常见问题</a>
                    </li>

                <div class="clear"></div>
            </ul>
        </div>

        <div class="nkm_con_list ">
            <ul>
                <?php foreach ($model as $key => $value) { ?>
                <a href="<?=Url::toRoute(['list/detail','id'=>$value->art_id])?>">
                <li>
                    
                        
                        <p><?=PublicController::substr($value->title, 40)?></p>
                        <span><?=date('Y-m-d',$value->create_time)?>   </span>

                        <div class="clear"></div>
                    
                </li>
                </a>
                 <?php } ?>

            </ul>

        <div class="jiazai_more" style="display: none"><a href="javascript:;">加载更多</a></div>
        <div class="jiazai_nomore"><a href="javascript:;">没有更多了哦</a></div>

        </div>



    </div>
</div>
<!--main end-->


<script>
    //    适应不同屏幕
    /*window.onload=function(){
        document.documentElement.style.fontSize= document.documentElement.clientWidth/16+'px' ;
    };*/
    //搜索验证
    function search_con(){
        var str=$(".search_con").val();
        // var reg=/^[\w]{2,20}$/g;
        if(!str){
            layer.open({
                content: '请输入搜索关键词'
                ,skin: 'msg'
                ,time: 2000
            });
            return false;
        }
        else{return true;}
    }

    function sear_check() {
        if(search_con()){
            return true;
        }else{
            return false;
        }
    }

    function find_new(id){
       
        $.ajax({
            type:"GET",
            data:{"id":id},
            url:"<?=Url::toRoute([$this->context->id.'/permission','status'=>1])?>",
            success:function(data){
                if(data==100){
                      window.location.href="index.php?r=list/detail&id="+id+"";
                }else if(data==200){
                    layer.confirm('您还未登录', {
                        btn: ['确定'] //按钮
                    }, function(){
                         window.location.href="#";
                    }, function(e){
                        layer.close(e);
                        return false;
                    });
                }
                else if(data==300){
                      window.location.href="index.php?r=list/detail&id="+id+"";
                } else if(data==400){
                    layer.confirm('没有权限查看？您未开通会员资格！点击确定即可开通会员', {
                        btn: ['确定'] //按钮
                    }, function(){
                       window.location.href="<?=Url::toRoute('list/buy-agent')?>";
                    }, function(e){
                        layer.close(e);
                        return false;
                    });
                }
            }
        })
    }

    // js获取GET参数
    function getQueryVariable(variable)
    {
        var query = window.location.search.substring(1);
        var vars = query.split("&");
        for (var i=0;i<vars.length;i++) {
            var pair = vars[i].split("=");
            if(pair[0] == variable){return pair[1];}
        }
        return(false);
    }

    /*加载更多*/
    var page=0;
    var show = true;
    $(window).scroll(function(){
        var scrollTop = $(this).scrollTop();
        var scrollHeight = $(document).height();
        var windowHeight = $(this).height();
        if(scrollTop + windowHeight >= scrollHeight && show){
            $(".jiazai_more").show();
            Load();
        }
    });
    function Load() {
        show = false;
        var cat_id =10;
        setTimeout(function () {
            page++;
            $.ajax({
                url  : "<?= Url::toRoute(['list/questions-more'])?>",
                type : 'get',
                data : {'page':page,'cat_id':cat_id},
                dataType:'text',
                success:function(data){
                    if(data){
                        show = true;
                        $(".nkm_con_list ul").append(data);
                    }else{
                        show = false;
                        $(".jiazai_more").hide().next().show();
                    }
                }
            });
        },1000);
    }

</script>
<script>
    $(".nkm_con_head ul li a").each(function(){
        if ($(this)[0].href == String(window.location) && $(this).attr('href')!="") {
            $(this).parent('li').addClass("curr");
        }
    });
</script>

<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>
