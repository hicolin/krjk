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
    <div class="nkm_top">
        <div class="nkm_top_left fl">
            <i class="iconfont icon-laba"></i>
        </div>
        <div class="nkm_top_right fl">
            <div id="scrollDiv" class="scrollDiv">
                <ul>
                    <?php foreach ($top_news as $value): ?>
                        
                 
                    <a href="<?=Url::toRoute(['list/detail','id'=>$value->art_id])?>">
                        <li>
                            <span><?=PublicController::substr($value->title, 20)?></span>
                        </li>
                    </a>

                    <?php endforeach ?>
                </ul>
            </div>
            <script type="text/javascript">   function AutoScroll(obj){   $(obj).find("ul:first").animate({   marginTop:"-1.5rem"   },500,function(){   $(this).css({marginTop:"0rem"}).find("li:first").appendTo(this);   });   }   $(document).ready(function(){   setInterval('AutoScroll("#scrollDiv")',3000);   });   </script>
        </div>
        <div class="clear"></div>
    </div>
    <div class="nkm_search">
        <form method="get" action="<?=Url::toRoute(['list/keywords'])?>" onsubmit="return sear_check()">
            <input type="text" name="keywords"  value="<?=isset($keywords)?$keywords:''?>"  class="search_con fl"  placeholder="请输入关键词查询">
            <input type="hidden" name="r" value="list/keywords">
            <input type="hidden" name='cat_id' value="<?=isset($_GET['cat_id'])?$_GET['cat_id']:'4'?>">
            <button type="submit" class="fl"><i class="iconfont icon-search1"></i></button>
            <div class="clear"></div>
        </form>
    </div>
    <div class="nkm_con">
        <div class="nkm_con_head">
            <ul>
                <?php
                foreach ($cat_list as $list) { ?>
                    <li class="<?php if(isset($_GET['cat_id'])){ if($list->id==$_GET['cat_id']){ echo'curr'; }else{ echo''; }} ?>">
                        <a  href="<?=Url::toRoute(['list/news','cat_id'=>$list->id])?>"><?=$list->name?></a>
                    </li>

                <?php }
                ?>
                <div class="clear"></div>
            </ul>
        </div>

        <div class="nkm_con_list ">
            <ul>
                <?php foreach ($new as $key => $value) { ?>
                <li>
                    <a onclick="find_new('<?=$value->art_id?>')">
                        <!--<img src="<?/*= Url::base() */?>/mobile/web/images/kouzi.png" class="fl"/>-->
                        <img src="<?= $value->img ?>" class="fl"/>
                        <p><?=PublicController::substr($value->title, 40)?></p>
                        <span><?=date('Y-m-d',$value->create_time)?>   </span>

                        <div class="clear"></div>
                    </a>
                </li>
                 <?php } ?>
            </ul>

        <div class="jiazai_more" style="display: none"><a href="javascript:;">加载更多</a></div>
        <div class="jiazai_nomore"><a href="javascript:;">没有更多了哦</a></div>

        </div>



        <?php if($new){?>
        <div class="nkm_con_nosear hiddendiv">
            <img src="<?= Url::base() ?>/mobile/web/images/nosear.png"/><br/>
            <span>暂时没有你搜索的数据！</span>
        </div>

       <?php }else{?>

        <div class="nkm_con_nosear ">
            <img src="<?= Url::base() ?>/mobile/web/images/nosear.png"/><br/>
            <span>暂时没有你搜索的数据！</span>
        </div>

       <?php } ?>



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
        var cat_id = getQueryVariable("cat_id");
        setTimeout(function () {
            page++;
            $.ajax({
                url  : "<?= Url::toRoute(['list/news-more'])?>",
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
