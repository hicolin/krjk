<?php
use yii\helpers\Url;
use common\controllers\PublicController;

?>
<link rel="stylesheet" type="text/css" href="<?=Url::base()?>/backend/web/site/dashboard.css">
<link rel="stylesheet" type="text/css" href="<?=Url::base()?>/backend/web/site/index.css">
<script src="<?=Url::base()?>/backend/web/site/highcharts.js"></script>
<script src="<?=Url::base()?>/backend/web/site/exporting.js"></script>
<script src="<?=Url::base()?>/backend/web/site/index.js"></script>
<script src="<?=Url::base()?>/backend/web/site/jquery.timers.js"></script>


<!--<script src="highcharts.js"></script>
<script src="exporting.js"></script>
<script src="ADMIN_JS/index.js"></script>
<script src="ADMIN_JS/jquery.timers.js"></script>-->
<!-- ********************【脚本统一写在index.js中】******************** -->
<!-- ********************【脚本统一写在index.js中】******************** -->
<section class="content">
  <!-- Small boxes (Stat box) -->
  <div class="row">
    <div class="col-md-12">
    <div class="box">
<div class="statistical">
  <ul>
    <li class="completed-transaction-statistics">
      <header>
        <i class="ns-icon-base i-focus-number"></i>
        <!-- <span>关注人数(个)</span> -->
        <span>会员总人数(个)</span>
        <span class="js-weixin-fans-count" style="margin-left: 20px;font-size: 31px"><?= $queryall?></span>
      </header>
      <header>
        <i class="ns-icon-base i-focus-number"></i>
        <!-- <span>关注人数(个)</span> -->
        <span>普通会员数(个)</span>
        <span class="js-weixin-fans-count" style="margin-left: 20px;font-size: 16px"><?= $queryall -$queryt-$queryy -$queryj?></span>
      </header>

      <header>
        <i class="ns-icon-base i-focus-number"></i>
        <!-- <span>关注人数(个)</span> -->
        <span>铜牌会员数(个)</span>
        <span class="js-weixin-fans-count" style="margin-left: 20px;font-size: 16px"><?= $queryt?></span>
      </header>
      <header>
        <i class="ns-icon-base i-focus-number"></i>
        <!-- <span>关注人数(个)</span> -->
        <span>银牌会员数(个)</span>
        <span class="js-weixin-fans-count" style="margin-left: 20px;font-size: 16px"><?= $queryy?></span>
      </header>
      <header>
        <i class="ns-icon-base i-focus-number"></i>
        <!-- <span>关注人数(个)</span> -->
        <span>金牌会员数(个)</span>
        <span class="js-weixin-fans-count" style="margin-left: 20px;font-size: 16px"><?= $queryj?></span>
      </header>
    </li>

    <li class="order-total-statistics">
      <!--<header>
        <i class="ns-icon-base i-order-total"></i>
        <span>本月总会员个数</span>
      </header>
      <p class="js-order-total">0</p>-->
      <header>
        <i class="ns-icon-base i-focus-number"></i>
        <!-- <span>关注人数(个)</span> -->
        <span>本月总会员个数(个)</span>
        <span class="js-weixin-fans-count" style="margin-left: 20px;font-size: 31px "><?= $monthqueryall?></span>
      </header>

      <header>
        <i class="ns-icon-base i-focus-number"></i>
        <!-- <span>关注人数(个)</span> -->
        <span>本月普通会员数(个)</span>
        <span class="js-weixin-fans-count" style="margin-left: 20px;font-size: 16px"><?= $monthqueryall - $monthqueryt -$monthqueryy - $monthqueryj?></span>
      </header>

      <header>
        <i class="ns-icon-base i-focus-number"></i>
        <!-- <span>关注人数(个)</span> -->
        <span>本月铜牌会员数(个)</span>
        <span class="js-weixin-fans-count" style="margin-left: 20px;font-size: 16px"><?= $monthqueryt?></span>
      </header>
      <header>
        <i class="ns-icon-base i-focus-number"></i>
        <!-- <span>关注人数(个)</span> -->
        <span>本月银牌会员数(个)</span>
        <span class="js-weixin-fans-count" style="margin-left: 20px;font-size: 16px"><?= $monthqueryy?></span>
      </header>
      <header>
        <i class="ns-icon-base i-focus-number"></i>
        <!-- <span>关注人数(个)</span> -->
        <span>本月金牌会员数(个)</span>
        <span class="js-weixin-fans-count" style="margin-left: 20px;font-size: 16px"><?= $monthqueryj?></span>
      </header>

    </li>

    <li class="focus-number-statistics">
      <header>
        <i class="ns-icon-base i-focus-number"></i>
        <!-- <span>关注人数(个)</span> -->
        <span>今日会员总数(个)</span>
        <span class="js-weixin-fans-count" style="margin-left: 28px;font-size: 31px"><?= $todayqueryall?></span>
      </header>
      <header>
        <i class="ns-icon-base i-focus-number"></i>
        <!-- <span>关注人数(个)</span> -->
        <span>今日普通会员数(个)</span>
        <span class="js-weixin-fans-count" style="margin-left: 20px;font-size: 16px"><?=$todayqueryall- $todayqueryt-$todayqueryy-$todayqueryj?></span>
      </header>

      <header>
        <i class="ns-icon-base i-focus-number"></i>
        <!-- <span>关注人数(个)</span> -->
        <span>今日铜牌会员数(个)</span>
        <span class="js-weixin-fans-count" style="margin-left: 20px;font-size: 16px"><?= $todayqueryt?></span>
      </header>
      <header>
        <i class="ns-icon-base i-focus-number"></i>
        <!-- <span>关注人数(个)</span> -->
        <span>今日银牌会员数(个)</span>
        <span class="js-weixin-fans-count" style="margin-left: 20px;font-size: 16px"><?= $todayqueryy?></span>
      </header>
      <header>
        <i class="ns-icon-base i-focus-number"></i>
        <!-- <span>关注人数(个)</span> -->
        <span>今日金牌会员数(个)</span>
        <span class="js-weixin-fans-count" style="margin-left: 20px;font-size: 16px"><?= $todayqueryj?></span>
      </header>


    </li>

    <li class="order-amount-statistics">
      <header>
        <i class="ns-icon-base i-order-amount"></i>
        <span>购买会员总额(元)</span>
        <span class="js-weixin-fans-count" style="margin-left: 20px;font-size: 31px"><?=$totalmoney?></span>
      </header>
      <header>
        <i class="ns-icon-base i-focus-number"></i>
        <!-- <span>关注人数(个)</span> -->
<!--         <span>购买普通会员(元)</span>
        <span class="js-weixin-fans-count" style="margin-left: 20px;font-size: 16px">0</span> -->
      </header>

      <header>
        <i class="ns-icon-base i-focus-number"></i>
        <!-- <span>关注人数(个)</span> -->
        <span>购买铜牌会员(元)</span>
        <span class="js-weixin-fans-count" style="margin-left: 20px;font-size: 16px"><?=$modelt?></span>
      </header>
      <header>
        <i class="ns-icon-base i-focus-number"></i>
        <!-- <span>关注人数(个)</span> -->
        <span>购买银牌会员(元)</span>
        <span class="js-weixin-fans-count" style="margin-left: 20px;font-size: 16px"><?=$modely?></span>
      </header>
      <header>
        <i class="ns-icon-base i-focus-number"></i>
        <!-- <span>关注人数(个)</span> -->
        <span>购买金牌会员(元)</span>
        <span class="js-weixin-fans-count" style="margin-left: 20px;font-size: 16px"><?=$modelj?></span>
      </header>

    </li>

    <li class="goods-release-statistics" style="height: 130px">
      <header>
        <i class="ns-icon-base i-goods-release"></i>
        <span>今日产品申请数量</span>
      </header>
      <p><span class="js-weixin-fans-count" style="margin-left: 20px;font-size: 31px"><?= $dairecord?></span></p>
    </li>

    <li class="month-sales-statistics" style="height: 130px">
      <header>
        <i class="ns-icon-base i-month-sales"></i>
        <span>总产品申请数量</span>
      </header>
       <p><span class="js-weixin-fans-count" style="margin-left: 20px;font-size: 31px"><?= $dairecordall?></span></p>
    </li>
    <li class="month-sales-statistics" style="height: 130px;background: rgb(96,92,168);">
      <header>
        <i class="ns-icon-base i-month-sales"></i>
        <span>线下购买未审核数:</span>
        <span class="js-month-sales" style="margin-left: 20px;font-size: 31px"><?= $buyagent?></span>
      </header>
      <header style="margin-top:20px">
        <i class="ns-icon-base i-month-sales"></i>
        <span>提现未审核数:</span>
        <span class="js-month-sales" style="margin-left: 48px;font-size: 31px"><?= $withdraw?></span>

      </header>

    </li>


    <!--<li class="completed-transaction-statistics">
      <header>
        <i class="ns-icon-base i-completed-transaction"></i>
        <span>VIP会员总数</span>
      </header>
      <p class="vip">0</p>
    </li>-->
  </ul>
</div>
<header class="home">
  <article>
    <div class="home-shop">
      <img src="<?=Url::base()?>/backend/web/images/niushop_home.png" />
      <span><a href="<?=Url::toRoute(['admin-setting/index'])?>">网站设置</a></span>
    </div>
    <div class="home-info">
      <p><span class="js-user-name"><?php echo Yii::$app->user->identity->uname;?></span></p>
      <p>管理权限：<span>管理员</span></p>
      <!--<p>网站名称：<span>贷款分销</span></p>-->
    </div>
  </article>
<!--  --><?php
/*    var_dump(Yii::$app->user-> identity);
  */?>
  <article>
    <div>
      <p class="last-login-time">网站名称：<span><?=PublicController::getSysInfo(25)?></span></p>
   <!-- <p class="last-login-time">最后登录时间：<span><?php /*echo Yii::$app->user->identity->last_date;*/?></span></p>
    <p class="last-login-ip">最后登录IP：<span><?php /*echo Yii::$app->user->identity->last_ip;*/?></span></p>-->
    </div>
  </article>
  <article style="width:300px; margin-top: 45px; margin-left: 50px">
    <div>
      <p class="last-login-version">最后登录IP：<span><?php echo Yii::$app->user->identity->last_ip;?></span></p>
    <!--<p class="last-login-version">软件版本：<span>{$niu_version}</span></p>
    <p class="last-login-version">更新时间：<span>{$niu_ver_date}</span></p>-->
    </div>
  </article>
</header>
<div class="goods-prompt" style="display: none;">
  <h3>店铺及商品提示<span>您需要关注的店铺信息以及待处理事项</span></h3>
  <div class="subtitle">
    <img src="<?=Url::base()?>/backend/web/images/green_giftbag.png" /><label>店铺商品发布情况：<span class="goods_all_count">0/不限</span></label>
    <img src="<?=Url::base()?>/backend/web/images/orange_picture.png" /><label>图片空间使用情况：<span>不限</span></label>
  </div>
  <div class="goods-state">
    <ul>
      <li onclick="location.href=''"><h4 class="goods_sale_count">0</h4>出售中</li>
      <li onclick="location.href=''"><h4 class="goods_audit_count">0</h4>仓库中</li>
      <li onclick="location.href='';"><h4 class="goods_consult_count">0</h4>待回复咨询</li>
      <li onclick="location.href='';"><h4 class="member_balance_withdraw">0</h4>会员提现审核</li>
    </ul>
  </div>
</div>

      <style>
        .merchants-use table tr td {
          border-bottom: 1px solid #e3e3e3;
          padding: 20px;
        }
      </style>
<div class="merchants-help">
  <h3>会员推广前十</h3>
  <div class="merchants-use">
    <table style="width: 100%">
      <tr style="width: 139px">
        <td>排行</td>
        <td style="text-align:left;">会员名</td>
        <td>邀请人数</td>
      </tr>
      <?php
      $wxd = 1;
      foreach($tgquery as $list) {
        ?>
        <tr>
          <td>
            <?php
            if($wxd ==1)
            {
              echo "<span class=\"frist\">".$wxd;
            }else if($wxd ==2)
            {
              echo "<span class=\"second\">".$wxd;
            }
            else if($wxd ==3)
            {
              echo "<span class=\"third\">".$wxd;
            }else
            {
              echo "<span>".$wxd;
            }
            ?>

          </td>
          <td title="" style="text-align:left;width: 495px; ">
            <!--<a href="" target="_blank">-->
            <?php
            echo $list['nickname'];
            ?>
            <!--</a>-->
          </td>
          <td>
            <?php
            echo $list['count'];
            ?>
          </td>
        </tr>
        <?php
        $wxd ++;
      }
      ?>

    </table>
  </div>
</div>

<div class="goods-prompt" style="display: none;">
  <h3>
    交易提示<span>您需要立即处理的交易订单</span>
  </h3>
  <div class="subtitle">
    <img src="<?=Url::base()?>/backend/web/images/green_list.png" /><label>近期售出：<span>交易中的订单</span></label>
    <img src="<?=Url::base()?>/backend/web/images/orange_shield.png" /><label>维权投诉：<span>收到维权投诉</span></label>
  </div>
  <div class="goods-state">
    <ul>
      <li onclick="location.href='';"><h4 class="daifukuan">0</h4>待付款</li>
      <li onclick="location.href='';"><h4 class="daifahuo">0</h4>待发货</li>
      <li onclick="location.href='';"><h4 class="yifahuo">0</h4>已发货</li>
      <li onclick="location.href='';"><h4 class="yishouhuo">0</h4>已收货</li>
      <li onclick="location.href='';"><h4 class="yiwancheng">0</h4>已完成</li>
      <li onclick="location.href='';"><h4 class="yiguanbi">0</h4>已关闭</li>
      <li onclick="location.href='';"><h4 class="tuikuanzhong">0</h4>退款中</li>
      <li onclick="location.href='';" style="display: none;"><h4 class="yituikuan">0</h4>已退款</li>
    </ul>
  </div>
</div>

<div class="sales">
  <h3>
    销售情况统计<span>按周期统计商家店铺的订单量和订单金额</span>
  </h3>
  <table>
    <tr>
      <td>项目</td>
      <td>销量</td>
      <td>订单量（件）</td>
    </tr>
    <tr>
      <td>会员购买</td>
      <td>昨日销量</td>
      <td><span class="month_goods"><?= $yesterdayqueryagent?></span></td>

    </tr>
    <tr>
      <td></td>
      <td>本月销量</td>
      <td><span class="month_goods"><?= $monthqueryt +$monthqueryy + $monthqueryj?></span></td>

    </tr>
    <tr>
      <td>产品购买</td>
      <td>昨日销量</td>
      <td><span class="month_goods"><?= $yesterdayquerydairecord?></span></td>

    </tr>
    <tr>
      <td></td>
      <td>本月销量</td>
      <td><span class="month_goods"><?= $thismonthquerydairecord?></span></td>

    </tr>
  </table>
</div>

<div class="operation-promote" style="display: none;">
  <h3>
    店铺运营推广<span>合理参见促销活动可以有效提供商品销量</span>
  </h3>

  <div class="operation-promote-state" ">
    <ul>
      <li class="snapup"><img
        src="<?=Url::base()?>/backend/web/images/promoote_snapup.png" class="promote-img" />
        <h5 class="promote-h5">
          抢购活动<span>已开通</span>
        </h5>
        <p class="promote-p">参与平台发起的抢购活动提搞商品成交量及店铺浏览量</p>
      </li>
      <li class=time-limit><img
        src="<?=Url::base()?>/backend/web/images/promoote_snapup.png" class="promote-img" />
        <h5 class="promote-h5">
          限时折扣<span>已开通</span>
        </h5>
        <p class="promote-p">在规定时间段内对店铺中所选商品进行打折促销活动</p>
      </li>
      <li class=full_present><img
        src="<?=Url::base()?>/backend/web/images/promoote_snapup.png" class="promote-img" />
        <h5 class="promote-h5">
          满即送<span>已开通</span>
        </h5>
        <p class="promote-p">商家自定义满即送标准与规则，促进购买转化率</p>
      </li>
      <li class=preferential-suit><img
        src="<?=Url::base()?>/backend/web/images/promoote_snapup.png" class="promote-img" />
        <h5 class="promote-h5">
          优惠套装<span>未开通</span>
        </h5>
        <p class="promote-p">商品优惠套装，多重搭配更多实惠、商家必备营销方式</p>
      </li> 
      <li class=recommended_booth><img
        src="<?=Url::base()?>/backend/web/images/promoote_snapup.png" class="promote-img" />
        <h5 class="promote-h5">
          推荐展位<span>未开通</span>
        </h5>
        <p class="promote-p">选择商品参与平台发布的主题活动，审核后集中展示</p>
      </li>
      <li class=kims_volume><img
        src="<?=Url::base()?>/backend/web/image/promoote_snapup.png" class="promote-img" />
        <h5 class="promote-h5">
          代金券<span>已开通</span>
        </h5>
        <p class="promote-p">自定义代金卷使用规则并由平台统一展示供买家领取</p>
      </li>
    </ul>
  </div>
</div>
      <style>
        .sales-ranking table tr td {
          border-bottom: 1px solid #e3e3e3;
          /*padding: 20px, 20px 20px 20px;*/

        }
       .sales-ranking table{

          text-align: ;
        }
      </style>
<div class="sales-ranking">
  <h3>
    单品销售排名<span>按周期统计商家店铺的订单量和订单金额</span>
  </h3>
  <table style="width: 100%">
    <tr style="width: 139px">
      <td >排行</td>
      <td align="left" style="width: 488px;">商品名称</td>
      <td align="left" style="width: 184px;">销量</td>
    </tr>
    <?php
    $wxd = 1;
  foreach($dairecordquery as $list) {


    ?>
    <tr>
      <td>
   <?php
    if($wxd ==1)
    {
      echo "<span class=\"frist\">".$wxd;
    }else if($wxd ==2)
    {
      echo "<span class=\"second\">".$wxd;
    }
    else if($wxd ==3)
    {
      echo "<span class=\"third\">".$wxd;
    }else
    {
      echo "<span>".$wxd;
    }
   ?>
       </td>
      <td title="" style="text-align:left;">
        <!--<a href="" target="_blank">-->
        <?php
        echo $list['title'];
        ?>
        <!--</a>-->
      </td>
      <td>
        <?php
        echo $list['count'];
        ?>
      </td>
    </tr>
    <?php
    $wxd ++;
  }
    ?>

  </table>
</div>
      <div class="charts">
        <h3>
          订单总数统计<span><i></i>订单数量</span>
        </h3>
        <span class="charts-title order" onclick="onloadOrderChart(1)">今日</span>
        <span class="charts-title order black" onclick="onloadOrderChart(2)">昨日</span>
        <span class="charts-title order black" onclick="onloadOrderChart(3)">本周</span>
        <span class="charts-title order black" onclick="onloadOrderChart(4)">本月</span>
        <div id="orderCharts">
        </div>
        <script>
          function onloadOrderChart(e){
            $(".order").addClass('black');
            $(".order").eq(e-1).removeClass('black');
            $.ajax({
              type : "post",
              url : '<?=Url::toRoute('site/getorderchartcount')?>',
              data : {'date':e},
              success : function(data) {
               
                var data1=JSON.parse(data);
               
                $('#orderCharts').highcharts({
                  chart: {
                    type: 'column',

                  },
                  title: {
                    text: '订单统计'
                  },

                  xAxis: {
                    type: 'category',
                    labels : {
                      rotation : -45,
                     /* style : {
                        fontSize : '13px',
                        fontFamily : 'Verdana, sans-serif'
                      }*/
                    }
                  },
                  yAxis: {
                    min: 0,
                    title: {
                      text: '订单数'
                    }
                  },
                  legend: {
                    enabled: false
                  },

                  series: [{
                    name: '订单个数',
                    data: data1,
                    dataLabels: {
                      enabled: true,
                      rotation: -90,
                      color: '#FFFFFF',
                      /*format: '{point.y:.1f}',*/
                      align: 'right',
                      y: 10
                    }
                  }]
                });
              }

            })

          }
          $(function () {
            onloadOrderChart(1);
            //chart2();
//      chart3();
          });
        </script>
      </div>
      <div class="charts" style="border-right: 0;">
        <h3>
          关注人数统计<span><i></i>关注人数</span>
        </h3>
        <span class="charts-title fans" onclick="onloadWeiXinFansChart(1)">今日</span>
        <span class="charts-title fans black" onclick="onloadWeiXinFansChart(2)">昨日</span>
        <span class="charts-title fans black" onclick="onloadWeiXinFansChart(3)">本周</span>
        <span class="charts-title fans black" onclick="onloadWeiXinFansChart(4)">本月</span>
        <div id="focusCharts"></div>
        <script>
          function onloadWeiXinFansChart(e)
          {
            $(".fans").addClass('black');
            $(".fans").eq(e-1).removeClass('black');
            $.ajax({
              type : "post",
              url : '<?=Url::toRoute('site/getweixinfanschartcount')?>',
              data : {'date':e},
              success : function(data) {

                var data1=JSON.parse(data);

                $('#focusCharts').highcharts({
                  chart: {
                    type: 'column',

                  },
                  title: {
                    text: '关注人数统计'
                  },

                  xAxis: {
                    type: 'category',
                    labels : {
                      rotation : -45,
                      /* style : {
                       fontSize : '13px',
                       fontFamily : 'Verdana, sans-serif'
                       }*/
                    }
                  },
                  yAxis: {
                    min: 1,
                    title: {
                      text: '关注人数'
                    }
                  },
                  legend: {
                    enabled: false
                  },

                  series: [{
                    name: '关注人数',
                    data: data1,
                    dataLabels: {
                      enabled: true,
                      rotation: -90,
                      color: '#FFFFFF',
                      /*format: '{point.y:.1f}',*/
                      align: 'right',
                      y: 10
                    }
                  }]
                });
              }

            })
          }
          $(function () {
            onloadWeiXinFansChart(1);
            //chart2();
//      chart3();
          });
        </script>
      </div>

      <!-- /.box-body -->
      <div class="box-footer clearfix">

      </div>
    </div>
    <!-- /.box -->
  </div>


  </div>
          <!-- /.box -->

    
    

  <!-- /.row -->
  <!-- Main row -->
  <div class="row">
    
  </div>
  <!-- /.row (main row) -->

</section>
