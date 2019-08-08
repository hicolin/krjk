<?php
use yii\helpers\Url;
use common\controllers\PublicController;

?>
<?php $this->beginBlock('header'); ?>
<link rel="stylesheet" href="<?= Url::base()?>/mobile/web/plugins/swiper/css/swiper.min.css">
<style>
    .host{padding: 10px}
    .host p{margin-top:20px;line-height: 25px }
    .host .emphasize{font-weight: bold;color:red}
    .titleBox{display: flex;justify-content: space-between;width: calc(100% - 1rem);margin:1rem auto 0.6rem auto;align-items: center;}
    .product-container h2{padding:0 5px;border-left: 4px solid #1E9FFF;font-size: 16px;}
    .btnCopyP{padding: 4px 10px;background: rgba(51, 170, 255,0.6);border-radius: 10px;color: #fff;font-size: 12px;}
    .index_main_con{border-top:1px solid #eee; }
    .prom-all{background-color: rgb(59, 173, 255);position: fixed;right: .5rem;bottom:6rem;width: 3rem;height: 3rem;
        border-radius: 50%;vertical-align: center;padding: .7rem;text-align: center;box-shadow: 0 0 10px 0 rgb(59, 173, 255);}
    .prom-text{display: block;color:#fff;font-weight: bold;line-height: 0.8rem;font-size: .65rem}
    .prom-all a:hover,a:visited{color:#fff}
    .swiper-wrapper a img{width: 100%;height: 100%}

    .btnCreat{background-color: rgb(59, 173, 255);position: fixed;right: 0.1rem;bottom:10rem;width: 3rem;height: 3rem;z-index: 2;
        border-radius: 50%;vertical-align: center;padding: .7rem;text-align: center;box-shadow: 0 0 10px 0 rgb(59, 173, 255);display: none;color:#fff;font-weight: bold;line-height: 0.8rem;font-size: .65rem}
    .links_lay .layui-layer-btn a{width: 40%;}
    #lay_links{padding: 10px;}
    .linksTitle{text-align: center;padding: 10px 0;font-size: 14px;color: #333;}
    .linksInner{font-size: 14px;line-height: 22px;color: #999;}
</style>
<script src="<?= Url::base()?>/mobile/web/plugins/swiper/js/swiper.min.js"></script>
<?php $this->endBlock(); ?>

<style type="text/css">
    .announcecc img{width:100%;}
</style>
<!--menu-->
<div class="menu">
    <ul>
        <li>
<!--            <a href="--><?//= Url::toRoute(['index/wantloan'])?><!--">-->
<!--                <img src="--><?//= Url::base() ?><!--/mobile/web/images/it1.png"/>-->
<!--                <span>全部产品</span>-->
<!--            </a>-->
            <a href="<?= PublicController::getSysInfo(39) ?>">
                <img src="<?= Url::base() ?>/mobile/web/images/it1.png?v=20190426"/>
                <span>最新资讯</span>
            </a>
        </li>
        <li>
         <?php  if(Yii::$app->session['user_id']){ ?>
            <a onclick="customer('<?= $user_info->grade ?>')">
                <img src="<?= Url::base() ?>/mobile/web/images/it2.png"/>
                <span>客户信息</span>
            </a>
            <?php }else{ ?>
            <a onclick="customer1()">
                <img src="<?= Url::base() ?>/mobile/web/images/it2.png"/>
                <span>客户信息</span>
            </a>
        <?php } ?>
        </li>
        <li>
            <?php  if(Yii::$app->session['user_id']){ ?>
                <a href="<?= Url::toRoute('member/rank-list') ?>">
                    <img src="<?= Url::base() ?>/mobile/web/images/it3.png"/>
                    <span>排行榜</span>
                </a>
            <?php }else{ ?>
                <a onclick="join1()">
                    <img src="<?= Url::base() ?>/mobile/web/images/it3.png"/>
                    <span>排行榜</span>
                </a>
             <?php } ?>
        </li>
        <li>
            <a href="<?= Url::toRoute('member/loan-report') ?>">
                <img src="<?= Url::base() ?>/mobile/web/images/it4.png?v=20190418"/>
                <span>下款报备</span>
            </a>
       </li>
        <div class="clear"></div>
    </ul>
</div>

<!--banner start-->
<div style="margin-top:.3rem">
    <div class="swiper-container" style="height: 7rem;width: 100%">
        <div class="swiper-wrapper">
            <?php foreach ($banners as $list):?>
                <div class="swiper-slide"><a href="<?= $list['link']?>"><img src="<?= $list['img']?>" alt=""></a></div>
            <?php endforeach;?>
        </div>
        <div class="swiper-pagination"></div>
    </div>
</div>
<!--banner end-->

<div class="index_announce" style="font-size: 0.65rem;padding: 0.5rem;background: #FFF;margin-top: 0.3rem;">
    <p><span>最新公告:</span>
        <span class='ansv_title'>
            <a href="<?= Url::toRoute(['index/announce-detail','id'=>$announce['art_id']])?>" style="color: #666">
                <?php   // 公告长度处理
                if(isset($announce['title'])){
                    if(strlen($announce['title'])>16){
                        echo mb_substr($announce['title'],0,16,'utf-8').'...';
                    }else{
                        echo $announce['title'];
                    }
                }
                ?>
            </a>
        </span>
        <a href="<?=Url::toRoute('index/announce')?>" style='float:right;color: #666'>更多</a>
    </p>
</div>

<!--main-->
<div class="index_main">
    <div class="index_main_top">
        <ul>
            <li>
                <a href="<?= Url::toRoute(['index/index', 'style' => 2]) ?>">最新口子[<?= $reportNum ?>]</a>
            </li>
            <li>
                <a href="<?= Url::toRoute(['index/index', 'style' => 1]) ?>">大额产品[<?= $dealNum ?>]</a>
            </li>
            <div class="clear"></div>
        </ul>
    </div>
    <div class="product-container">
        <?php foreach ($data as $key=>$category):?>
         <div class="productsBox">
             <div class="titleBox">
                 <h2><?= $key?></h2>
                 <p class="btnCopyP">复制链接</p>
             </div>

             <div class="index_main_con">
                 <ul>
                     <?php foreach ($category as $value): ?>
                         <li
                             <?php if (Yii::$app->session['user_id']) :?>
                             data-item="<?= $value['title'] . ' ' . $value['shortUrl'] ?>"
                         <?php endif; ?>>
                             <!--左上角的选中icon-->
                             <i class="iconfont icon-xuanzhong2 iconYes iconCheck"
                                style="display: none;"
                                data-status="1"
                                 <?php if (Yii::$app->session['user_id']) :?>
                                     data-item="<?= $value['title'] . ' ' . $value['shortUrl'] ?>"
                                 <?php endif; ?>
                             >
                             </i>
                             <i class="iconfont icon-xuanzhong3 iconNo iconCheck"
                                data-status="0"
                                 <?php if (Yii::$app->session['user_id']) :?>
                                     data-item="<?= $value['title'] . ' ' . $value['shortUrl'] ?>"
                                 <?php endif; ?>
                             >
                             </i>


                             <?php  if(Yii::$app->session['user_id']){ ?>
                             <a onclick="join('<?= $user_info['agent'] ?>','<?= $value['id'] ?>','<?= $value['grade'] ?>','<?= $user_info['grade'] ?>')">
                                 <?php }else{ ?>
                                 <a onclick="join1()">
                                     <?php } ?>
                                     <img class="lazyload" data-original="<?= $value['logo'] ?>"/>
                                     <h1><?= PublicController::substr($value['title'], 6) ?></h1>
                                     <p><span style="color:rgb(255, 109, 8)"><?=$value['fy_info']?></span> <span style="color: rgb(198, 72, 213)"><?=$value['js_method']?></span></p>
                                     <p><?= PublicController::substr($value['title_info'], 10) ?></p>
                                 </a>
                             </a>

                             <!--新增两标签-->
                             <div class="add_label add_label01"><?= $value['label_one'] ?></div>
                             <div class="add_label add_label02"><?= $value['label_two'] ?></div>
                         </li>
                     <?php endforeach ?>
                 </ul>

             </div>

         </div>

        <?php endforeach;?>

    </div>

    <!--一键推广-->
    <div class="prom-all" id="prom-all" style="z-index: 666">
            <a class="prom-text btnPromote" href="javascript:;" data-kind="0" >
                一键推广
            </a>
    </div>

    <div class="btnCreat" onclick="creatLinks()">
        生成链接
    </div>

    <div style='display:none' id='announceBox'>
        <div class='announcecc' style="font-size:.5rem;padding:0.5rem;">
            <?php echo $announce['detail'];?>
        </div>
    </div>
</div>

<!--放链接的盒子-->
<div class="linksBox" style="display: none;">
    <p class="linksTitle">批量推广链接</p>
    <div class="linksInner copy-text" data-clipboard-text="">

    </div>
</div>

<!--main end-->
<div style="height: 3rem"></div>

<?php $this->beginBlock('footer'); ?>
<script>
    var showAnnounce="<?php echo $announce ? 1 : 0 ;?>";
    var announceAlreadyShow="<?php echo $announceAlreadyShow ;?>";
    var showAnnounceTitle = "<?php echo $announce['title'] ;?>";
    if(announceAlreadyShow!=1){
        layer.open({
            type: 1,
            shade: 0.5,
            title:'【公告】'+showAnnounceTitle,
            content: $('#announceBox')
        });
    }

    function join(id, pid,grade,usergrade) {
        if(grade > usergrade)
        {
            layer.confirm('没有权限查看？您的会员权限资格不够！点击确定即可升级', {
                btn: ['确定'] //按钮
            }, function () {
                window.location.href = "<?=Url::toRoute('list/buy-agent')?>";
                return false;
            }, function (e) {
                layer.close(e);
                return false;
            });
            return false;
        } else {
            window.location.href = '<?= Url::to(['member/join-agent']) ?>' + '?id=' + pid;
        }
    }

    function join1() {
        var url = "<?=Url::toRoute('index/login')?>";
        layer.confirm('您还未登陆，点击确定前去登陆', {
            btn: ['确定'] //按钮
        }, function () {
            window.location.href =url;
        }, function (e) {
            layer.close(e);
            return false;
        });
    }

    function customer(id) {
        if (id == 0) {
            layer.confirm('没有权限查看？您未开通会员资格！点击确定即可开通会员', {
                btn: ['确定'] //按钮
            }, function () {
                window.location.href = "<?=Url::toRoute('list/buy-agent')?>";
            }, function (e) {
                layer.close(e);
                return false;
            });
        } else {
            window.location.href = "<?=Url::toRoute('member/customer-list')?>";
        }

    }

    function customer1() {
        layer.confirm('您还未登陆，点击确定前去登陆', {
            btn: ['确定'] //按钮
        }, function () {
            window.location.href = "<?=Url::toRoute('index/login')?>";
        }, function (e) {
            layer.close(e);
            return false;
        });
    }

    // js获取GET参数
    function getQueryVariable(variable) {
        var query = window.location.search.substring(1);
        var vars = query.split("&");
        for (var i = 0; i < vars.length; i++) {
            var pair = vars[i].split("=");
            if (pair[0] == variable) {
                return pair[1];
            }
        }
        return (false);
    }


    var conArry = [];

    function creatLinks() {
        for(var i=0;i<conArry.length;i++){
            var _string = "<P>"+ conArry[i] + "\r\n" +"</P>";
            $(".linksInner").append(_string);
        }
        var _html = $(".linksBox").html();
        var _text = $.trim($(".linksInner").text());
        console.log(_text);
        $('.copy-text').attr('data-clipboard-text', _text);
        var index = layer.load(2);
        setTimeout(function () {
            layer.close(index);
        },500);
        setTimeout(function () {
            layer.open({
                type: 1,
                title:false,
                closeBtn:0,
                skin:'links_lay',
                area:['70%','60%'],
                id:'lay_links',
                btn:['复制','关闭'],
                btnAlign:'c',
                content: _html, //这里content是一个普通的String
                yes:function () {
                    $('.copy-text').click();
                        location.reload();

                }
            })
        },1000);
        conArry = [];
        $(".btnCreat").hide();
        $(".iconCheck").hide();
        $(".btnPromote").html("一键推广");
        $("#prom-all").css({"background":"rgb(59, 173, 255)","color":"#fff"})
        $(".btnPromote").attr("data-kind",0);
        $(".linksInner").html('');

    }

    //点击每个分类旁边的复制链接
    $(".btnCopyP").click(function () {
        $(".linksInner").html('');
        var _lis = $(this).parent().siblings(".index_main_con").find("ul").find("li");
        for(var i=0;i<_lis.length;i++){
            var _src = _lis[i].getAttribute("data-item");
            var _string = "<P>"+ _src + "\r\n" +"</P>";
            $(".linksInner").append(_string);
        }

        var _html = $(".linksBox").html();
        var _text = $.trim($(".linksInner").text());
        console.log(_text);
        $('.copy-text').attr('data-clipboard-text', _text);
        var index = layer.load(2);
        setTimeout(function () {
            layer.close(index);
        },500);
        setTimeout(function () {
            layer.open({
                type: 1,
                title:false,
                closeBtn:0,
                skin:'links_lay',
                area:['70%','60%'],
                id:'lay_links',
                btn:['复制','关闭'],
                btnAlign:'c',
                content: _html, //这里content是一个普通的String
                yes:function () {
                    $('.copy-text').click();
                    location.reload();

                }
            })
        },1000)

    });

    //一键推广点击选中

    $(".btnPromote").click(function () {
        var user_id = '<?= Yii::$app->session['user_id'] ?>';
        var grade = '<?= $user_info->grade ?>'
       var _kind = $(this).attr("data-kind");
       if (!user_id) {
           location.href = '<?= Url::to(['index/login']) ?>';
           return;
       }
        if (grade == 0) {
            layer.confirm('您没有一键代理权限？您未开通会员资格！点击确定即可开通会员', {
                btn: ['确定'] //按钮
            }, function () {
                window.location.href = "<?=Url::toRoute('list/buy-agent')?>";
            })
            return;
        }
       if(_kind == 0){
           conArry = [];
           $(".iconNo").show();
           $(this).html("取消推广");
           $("#prom-all").css({"background":"#ccc","color":"#666"});
           $(this).attr("data-kind",1);
       }else{
           conArry = [];
           $(".iconCheck").hide();
           $(this).html("一键推广");
           $("#prom-all").css({"background":"rgb(59, 173, 255)","color":"#fff"})
           $(this).attr("data-kind",0);
           $(".btnCreat").hide();
       }
    });


    $(".iconCheck").click(function () {
        console.log(conArry);
        $(this).hide().siblings(".iconfont").show();
        var _kind = $(this).attr("data-status"); //0->添加  1->移除
        var _con = $(this).attr("data-item");
        if(_kind == 0){
             console.log(conArry.indexOf(_con));
             conArry.push(_con)

        }else{
            var _index = conArry.indexOf(_con)
            console.log(_index);
            conArry.splice(_index, 1);
        }

        if(conArry[0]){
            $(".btnCreat").show();
        }else{
            $(".btnCreat").hide();
        }
    });
</script>
<script>
    $(".index_main_top ul li a").each(function () {
        if ($(this)[0].href == String(window.location) && $(this).attr('href') != "") {
            $(this).parent('li').addClass("curr");
        }
    });
    var style = getQueryVariable("style");
    if(!style){
        $('.index_main_top li:nth-child(1)').addClass('curr');
    }

    // swiper
    $(function () {
        var mySwiper = new Swiper ('.swiper-container', {
            direction: 'horizontal', // 垂直切换选项
            autoplay:{
                stopOnLastSlide:true,
            },
            loop: true, // 循环模式选项
            pagination: {
                el: '.swiper-pagination',
            },
        })
    });
</script>
<script src="<?= Url::base()?>/mobile/web/js/inertia.js"></script>
<script>
    new Inertia(document.getElementById('prom-all'),{'edge':false});
</script>
<?php $this->endBlock(); ?>

