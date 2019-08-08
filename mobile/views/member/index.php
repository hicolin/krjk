<?php
use yii\helpers\Url;
use common\controllers\PublicController;

$user_id = Yii::$app->session['user_id'];
if($user_id){
    $no_read_notice = \backend\models\AdminNotice::findOne(['user_id'=>$user_id,'is_read'=>1]);
}
?>
<?php $this->beginBlock('header'); ?>

<!-- <head></head>中代码块 -->
<?php $this->endBlock(); ?>

<style>
    .userTopBox{display: flex!important;justify-content: space-between;}
    .userTopBoxLeft{display: flex;align-items: center;}
    .userTopBoxLeftImg{margin-right: 0.4rem;overflow: hidden;width: 80px;height: 80px;border-radius: 6px;}
    .userTopBoxLeftImg img{width: 100%;}
    .userTopBoxRight{position: relative;padding-right: 10px;}
    .userTopBoxRight .red-dot{position: absolute;right: 0;top:-6px;}
</style>
<!--nav-->
<div class="person_nav">
    <a href="javascript:;" class="userTopBox">
        <div class="userTopBoxLeft">
            <div class="userTopBoxLeftImg">
                <img src="<?=$user_info->pic?:Url::base().'/mobile/web/images/tx2.png'?>" class="" id="wxdfl" />
            </div>
            <div class="userTopBoxLeftTxt">
                <h1 >昵称：<?=$user_info->nickname?></h1>
                <h1>手机：<?=$user_info->tel?></h1>
                <h1>真实姓名：<?=$user_info->real_name?></h1>
                <h1>会员角色：<?=isset($user_info->grade)&&$user_info->grade==0?'普通会员':$user_info->grades['grade_name']?></h1>
            </div>

        </div>

        <div class="userTopBoxRight">
             <i class="iconfont icon-xiaoxi2 fr" onclick="location.href = '<?= Url::to(['index/announce']) ?>'"></i>
            <?php if($user_id && $no_read_notice): ?>
             <span class="red-dot"></span>
            <?php endif; ?>
        </div>
    </a>

        <?php if (!strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) :?>
            <a href="<?=Url::toRoute('index/loginout')?>" style="float: right; color: red;">退出登录</a>
        <?php endif;?>
<!--         <?php  if($user_info->grade!=3){ ?>
            <span style="position:absolute;right: 10%;top: 45%;font-size: 0.6rem" > <a href="<?=Url::toRoute('list/buy-agent')?>" >前往升级会员</a></span>
            <i class="iconfont icon-dayuhao fr"></i>
        <?php } ?> -->

</div>

<div style="display: none">
    <input type="file" name="file" class="file" id="file" onchange="document.getElementById('textfield').value=this.value" />
    <span onclick="UploadFile()" class="mybtn">上传</span>
</div>
<!--nav end-->
<!--menu-->
<div class="person_menu">
    <h1>我的信息</h1>
    <div class="person_menu_li">
        <ul>
            <li>
                <a href="<?=Url::toRoute(['member/info'])?>">
                    <i class="iconfont icon-zhenshixingming"></i>
                    <span>个人信息</span>
                </a>
            </li>
            <li>
                <a href="<?=Url::toRoute(['member/change-tel'])?>">
                    <i class="iconfont icon-shouji"></i>
                    <span>手机号码</span>
                </a>
            </li>
            <li>
                <a href="<?=Url::toRoute(['member/change-account'])?>">
                    <i class="iconfont icon-dizhi"></i>
                    <span>账户信息</span>
                </a>
            </li>
            <li>
                <a href="<?=Url::toRoute(['member/change-psd','type'=>'passwd'])?>">
                    <i class="iconfont icon-weibiaoti-"></i>
                    <span>修改密码</span>
                </a>
            </li>
            <div class="clear"></div>
        </ul>
    </div>
</div>
<!--menu end-->

<!--doc-->
<div class="person_doc" style="height: 1.5rem;">
    <span style="line-height: 1.5rem;font-size:0.5rem;">否开启消息提醒</span>
    <div class="sure_btn <?=$user_info->is_open?'':'on'?>" style='top: 0.2rem;'>
        <div class="sure_btn_son">
        </div>
    </div>
</div>

<div class="person_doc_bot">
    <span>若关闭此开关，您将不再收到该公众号的文档提醒。</span>
</div>
<script>
    $(".sure_btn").click(function(){
        $.ajax({
            url  : "<?=Url::toRoute('member/is-open')?>",
            type : 'get',
            data : '',
            dateType:'text',
            success:function(data) {
                if(data==100) {
                    $('.sure_btn').toggleClass("on");
                }
            }
        })
    })
</script>
<!--doc end-->

<!--main-->
<div class="person_main">
    <ul>
        <li>
            <a href="javascript:;" onclick="see('<?=Url::toRoute('member/account')?>')">
                <i class="iconfont icon-zhanghu pm1"></i>
                <span>我的账户</span>
                <em class="iconfont icon-dayuhao fr"></em>
            </a>
        </li>
        <li>
            <a href="javascript:;" onclick="see('<?=Url::toRoute('member/customer')?>')">
                <i class="iconfont icon-wodezhanghu pm2"></i>
                <span>我的团队</span>
                <em class="iconfont icon-dayuhao fr"></em>
            </a>
        </li>
        <?php if($user_info['is_partner'] == 2):?>
            <li>
                <a href="javascript:;" onclick="see('<?=Url::toRoute('member/statistic')?>')">
                    <i class="fa fa-paw" style="color: rgb(48, 165, 233)"></i>
                    <span>我的合伙人</span>
                    <em class="iconfont icon-dayuhao fr"></em>
                </a>
            </li>
        <?php endif;?>
        <li>
            <a href="javascript:;" onclick="see('<?=Url::toRoute('member/poster')?>')">
                <i class="iconfont icon-haibao pm3"></i>
                <span>我的推广码</span>
                <em class="iconfont icon-dayuhao fr"></em>
            </a>
        </li>

        <li>
            <a href="<?=Url::toRoute(['list/wx'])?>">
                <i class="iconfont icon-kefu pm7"></i>
                <span>联系客服</span>
                <em class="iconfont icon-dayuhao fr"></em>
            </a>
        </li>

        <li>
            <a href="<?=Url::toRoute(['list/page','id'=>67])?>">
                <i class="iconfont icon-jiagou pm7"></i>
                <span>常见问题</span>
                <em class="iconfont icon-dayuhao fr"></em>
            </a>
        </li>
        <li>
            <a href="<?=Url::toRoute(['list/app-download'])?>">
                <i class="iconfont icon-haibao pm3"></i>
                <span>APP下载</span>
                <em class="iconfont icon-dayuhao fr"></em>
            </a>
        </li>
    </ul>

</div>
<!--main end-->

<!--foot-->
<div class="person_foot">
    <ul>
        <li>
            <a href="<?=Url::toRoute('list/card')?>">
                <i class="iconfont icon-handle-card-empty"></i>
                <span>我要办卡</span>
            </a>
        </li>
        <li>
            <a href="http://daikuan.51kanong.com/index.php?s=/Home/Daikuan/lists.html">
                <i class="iconfont icon-jiekuan"></i>
                <span>我要借款</span>
            </a>
        </li>
        <li style="height: 2.5rem;">
            <a href="<?=Url::toRoute(['member/product','type'=>1])?>" class="pf_mid">
                <i class="iconfont icon-qiandai"></i>
            </a>
        </li>
        <li>
            <a href="<?=Url::toRoute('member/withdraw-money')?>">
                <i class="iconfont icon-wsmp-withdrawals"></i>
                <span>我要提额</span>
            </a>
        </li>
        <li>
            <a href="<?=Url::toRoute('member/index')?>">
                <i class="iconfont icon-rengezhongxin"></i>
                <span>个人中心</span>
            </a>
        </li>
    </ul>
</div>
<!--foot end-->

<script>
    function see(url){
        var agent = "<?=$user_info->grade?>";
        if(agent>0) {
            window.location.href=url;
        }else{
            layer.confirm('没有权限查看？您未开通会员资格！点击确定即可开通会员', {
                btn: ['确定'] //按钮
            }, function () {
                window.location.href = "<?=Url::toRoute('list/buy-agent')?>";
            }, function (e) {
                layer.close(e);
                return false;
            });
        }
    }
</script>
<!--修改图片-->

<script>
    $("#wxdfl").click(function(){
        alert("请您上传80*75像素的图片")
        $('#file').click()

    })
    var file = document.getElementById("file");
    file.onchange=function(){
        $('.mybtn').click()
    }
    function UploadFile()
    {
        var fileObj = document.getElementById("file").files[0];
        //服务器端的路径
        var FileController = "<?=Url::toRoute('public/file')?>";
        var form = new FormData();
        //file可更改，在服务器端获取$_FILES['file']
        form.append("file", fileObj);
        createXMLHttpRequest();
        xhr.onreadystatechange = deal;
        xhr.open("post", FileController, true);
        xhr.send(form);
    }
    var xhr;
    function createXMLHttpRequest()
    {
        if(window.ActiveXObject)
        {
            xhr = new ActiveXObject("Microsoft.XMLHTTP");
        }
        else if(window.XMLHttpRequest)
        {
            xhr = new XMLHttpRequest();
        }
    }
    function deal()
    {

        var csrfToken = "<?=Yii::$app->request->csrfToken?>";
        if(xhr.readyState == 4)
        {
            if (xhr.status == 200 || xhr.status == 0)
            {
                var result = xhr.responseText;
                $('#wxdfl').attr('src',result)
                $.ajax({
                    type: "POST",
                    url: "<?= Url::toRoute('member/ajax-update')?>",
                    data: {"pic": result, 'field':'pic', '_csrf': csrfToken},
                    cache: false,
                    dataType: "json",
                    error: function (xmlHttpRequest, textStatus, errorThrown) {
                        alert("出错了，" + textStatus);
                    },
                    success: function (data) {
                        //window.location.reload();
                    }
                });
            }
        }
    }
</script>

<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>
