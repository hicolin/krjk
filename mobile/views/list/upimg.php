<?php
use yii\helpers\Url;
use common\controllers\PublicController;
$order_id = Yii::$app->session->get('order_id');
?>

<?php $this->beginBlock('header'); ?>
<!-- <head></head>中代码块 -->
<?php $this->endBlock(); ?>
<?php $this->beginContent('@app/views/layouts/header.php')?>
<?php $this->endContent();?>
		<!-- <link href="css/mui.min.css" rel="stylesheet" /> -->
		<link rel="stylesheet" href="<?=Url::base()?>/mobile/web/mui.min.css">
		<link rel="stylesheet" href="<?=Url::base()?>/mobile/web/style.css" />
		<style type="text/css">
				.person_foot{
					height: 3rem;
				}
		</style>
		<div class="mui-content bar-apply">
		    <div class="apply-top">
		    	<h4>申请状态通知：</h4>
		    	<p>
					<?=$msg?><br>
					<?php if(isset($agent->status)&&$agent->status==-1){ ?>
						<?=$agent->ju_content?>
					<?php } ?>
		    	</p>
		    	订单：
		    	<?=!empty($order)?$order:'待提交'?>

		    </div>
				<div class="apply-con">
				<form method="post"  action=""  enctype="multipart/form-data"  onsubmit="return check2()">
					<div class="mui-row">
					<div class="apply-tj">
						<h4 style="font-weight: normal;">凭证截图：</h4>
						<img src="<?=Url::base()?>/mobile/web/images/iconfont-tianjia.png" onclick="$('#ID_1').click()" style="height: 80px;"  id="idcard_face"/>
						<input type="hidden" name="id_zm" value="" class="id_zm">
                		<input type="file" id="ID_1"  name="idcard_face"  style="display:none;" onchange="imgPreview(this)"/>
						<?php if(Yii::$app->session->hasFlash('file')){?>
							<div style="color:red;font-size:14px;">请上传凭证截图!</div>
						<?php }?>
					</div>
					<div class="apply-textarea">
						<h4 style="font-weight: normal;">备注：</h4>
						<textarea class="describle" name="content"></textarea>
						<span style="color: #aaa;">（注：100字内）</span>
					</div>
						<?php if(Yii::$app->session->hasFlash('file2')){?>
							<div style="color:red;font-size:14px;">备注不能大于100字</div>
						<?php }?>
					<div class="apply-textarea">
						<span style="color: red;"></span>
					</div>
					<style type="text/css">
						.survi2{width: 100%;text-align: center;height: 40px;line-height: 40px;margin-bottom: 50px;}
					</style>
					<div class="survi2">
					</div>
						<button type="submit" class="mui-btn mui-btn-primary bar-b-btn">提交申请</button>
					</form>
		    </div>
		</div>
		<div class="fu">

		</div>
	<script>
		$(function(){
			$(".survi").click(function(){
				$(".fu").toggle();
			})
		});
		function check2(){
			var status = '<?=isset($agent->status)?$agent->status:''?>';
			if(status==1){
				alert('您的申请已经通过，请勿重新申请！');
				return false; 
			}
		}
	</script>

	<script type="text/javascript">
		        //图片上传1
	    function imgPreview(fileDom){
	            //判断是否支持FileReader
	            if (window.FileReader) {
	                var reader = new FileReader();
	            } else {
	                alert("您的设备不支持图片预览功能，如需该功能请升级您的设备！");
	            }
	            //获取文件
	            var file = fileDom.files[0];
	            var imageType = /^image\//;
	            //是否是图片
	            if (!imageType.test(file.type)) {
	                alert("请选择图片！");
	                return;
	            }
	            //读取完成
	            reader.onload = function(e) {
	                //获取图片dom
	                var img = document.getElementById("idcard_face");
	                //图片路径设置为读取的图片
	                img.src = e.target.result;
	            };
	            reader.readAsDataURL(file);
	    }
	</script>
<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>