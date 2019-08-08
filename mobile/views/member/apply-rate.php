<?php
use yii\helpers\Url;
?>
<?php $this->beginBlock('header'); ?>
<?php $this->endBlock(); ?>

<?php $this->beginContent('@app/views/layouts/header.php')?>
<?php $this->endContent()?>
<style>
    .invite_main_nav li{width:33.3%;float: left;text-align: center;}
    .invite_main_nav li a{font-size: 0.6rem;padding: 0 0.2rem;line-height: 1.5rem;display: inline-block;}
    .invite_main_nav li.curr a{color: #33aaff;border-bottom: 1px solid #33aaff;}

    .invite_main_con{width: 100%;background-color: #fff;margin-top: 5px}
    .invite_main_con table{width: 100%;text-align: center;border-collapse: collapse; min-height: 1.8rem; line-height: 1.8rem;}
    .invite_main_con table tr td{background: #fff;color: #666;font-size: 0.6rem;border-bottom: 1px solid #f0f0f0;overflow:hidden;white-space: nowrap;text-overflow: ellipsis;}
    .invite_main_con table tr:first-child{height: 2rem;line-height: 2rem;}
    .invite_main_con table tr:first-child td{font-size: 0.6rem;color: #333;}
    .invite_main_con table tr td{font-size: 0.6rem;color: #888;}
</style>
<div class="invite_main">
    <div class="invite_main_con">
        <table>
            <tr>
                <td>申请时间</td>
                <td>姓名</td>
                <td>好友手机号</td>
                <td>状态</td>
            </tr>
            <?php
            foreach ($data as $list) { ?>
                <tr>
                    <td><?=date('Y-m-d',$list['created_time'])?></td>
                    <td><?=$list['name']?></td>
                    <td><?=substr_replace($list['tel'],'****',3,4)?></td>
                    <td>
                        <?php $matchNum = [0=>'待审核',1=>'申请成功',2=>'已失效'];echo $matchNum[$list['match_num']]?>
                    </td>
                </tr>
            <?php }
            ?>
        </table>
    </div>
</div>
<!--main end-->

<?php $this->beginBlock('footer'); ?>

<?php $this->endBlock(); ?>
