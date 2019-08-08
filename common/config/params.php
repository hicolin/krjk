<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'appVersion' => '1.0.0',
    'appName' => 'YiiBoot',
    'homePage' => 'http://git.oschina.net/penngo/chadmin',

    // 微信公众号
    'wx' => [
        'app_id' => 'wxea8fb1395e28d369',
        'app_secret' => 'bd6da0eaacfaaddea72cd7795b89e311',
        'token' => 'kaxsd',
        // 模板消息
        'sms' => [   // 行业：金融 / 银行
            // 注册成功
            'register_success' => 'Vo6peZSl9SVzAiYtjOMKEB7gVFRps8FzcQnQ_HrGL10',
            // 贷款申请
             'loan_apply'=>'9hgELjCp2jBCl0cwtdD41divbe1ovcZbs8zdqwYsHog',
            // 收益到账
            'income_receive'=>'hqahaWS6EhzPGRmCJsX4QRsQMG7j6KWQOZSTPyID34o',
            // 购买成功
            'buy_success'=>'9cZu-7YRTvhvEQNL3E950RSc6vyRYEBE19JbSOAyIPI',
            // 提现结果
            'withdraw_result'=>'jkWbVLW5anTR7S65FaZGhPjALdGdNOES0KHMVYuVSqo',
            // 提现受理
            'withdraw_accept'=>'egluVsCj5JZ06SJLWETzL3s4B4bF0xLFl8Ko_PkBxOk',
        ],
    ],

    // 短信(腾讯云)
    'sms'=>[
        'app_id'=>'1400148074',
        'app_key'=>'111147fc7578f378f9f6936655a7caae',
        // 模板id(// 通用短信模板：{1}为您的短信验证码，请于{2}分钟内填写。如非本人操作，请忽略本短信。)
        'tpl_id'=>'206850',
    ],

    // 新浪短链接接口
    'xl'=>[
        'app_key' => '1681459862',
    ],

    // 意扬对外接口(保险)
    'yyang' => [
        'api_url' => 'https://www.yyang.net.cn/api/v1.0/ins',
        'site_id' => '1442_1001',
        'cs_code' => 'gi',
        'secret' => 'r4r9ze8hy3gm',
    ],

    // 目录文件权限(可读写)
        // access_token.txt
        // qrcode_temp

    // 域名全局修改
    // 缓存文件清除

    // 数据库连接修改
        // 支付宝异步回调
        // PHPExcel导入数据


];
