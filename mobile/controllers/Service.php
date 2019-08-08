<?php
/**
 * User: Colin
 * Time: 2019/3/6 10:16
 */

namespace mobile\controllers;


class Service
{

    /**
     * 处理交易人用户名或手机号（脱敏）
     * @param $dyUserInfo
     * @return mixed
     */
    public static function dealTrader($dyUserInfo)
    {
        if (!$dyUserInfo) {
            return $dyUserInfo;
        }
        $begin = mb_substr($dyUserInfo, 0, 1);
        if (is_numeric($begin)) {
            return substr_replace($dyUserInfo, '****', 3, 4);
        } else {
            return $dyUserInfo;
        }
    }

}