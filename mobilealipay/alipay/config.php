<?php
$config = array (
	//应用ID,您的APPID。
	'app_id' => "2018101161613948",

	//商户私钥
	'merchant_private_key' =>"MIIEpAIBAAKCAQEAmK6p8bDQfxlcl8fMMOTsXohQN0KdJBNlrxK43EoKggel6peVL3XoTIptiMgNhrEs+CIwwZvvkvW9JpQRDh/6qJ0J9ITiqj8ZJ3tQaeqnqVeJNL3lcoZHc08Mrs2ndsJ7Cun8yabRvevL6fERBQ1BpppAPu5AufUr4xp2Vs2JkM2+YMW6v6O27E5KI+o7uh6Q2FsXf28WOfh6qEofeC7VQWR4BHULYUft0Mf6+Kh01Zc0kUgYtn/Wcmu+LOeG3Oylxptr7pkG/H0DnROaPjyykmHfGh4i/r0l2FZTOImimMD/okJCNvcnlEq7DvZULvuyEQ3cKHzRU/0lis+CsePeewIDAQABAoIBAQCL/gpAWviwsNZfLIiihCPeAWpbYK6od0iSn73z7cd4tf6A/H+DRr65/2qzMYqFZ0puMRs3Sfz+aGcISlbg9p1joKmSE42ov7YWp1Bx7P/Rmztoqn0I5un4ZhCg7luP3n3m4egwFM7XLq6HT8tlMGRzl5c0nEuotY2J66d5q3do1nIL5uw9nlSB1dZYQ368y3WJ9GKl7BFoPjVL/9hsGYbDslW3fKArYcnTcM/zOk25z8MqRb7ZYlQcyzq50I4TUcW5kZ+r0f6ka/LIpJ+qkmrmIbzoYggAVTNxIOD3KnBEnA3ypBTz4/PSE6NphxJOepWrgv38NpmO0K2b8muqfOqBAoGBAMmgXfF/b2le6kPWznreVsVku9fMh1XXhJhU/Dgz+oHUwTyZTifDXLd+mEUFvRxp9UGI4IdmhQb7dGtGR97FqPmMwtZUsZVSJEoEP55J/cGuiMZOsgifxHLYQCBlirRRIjhhlfr/OfKvsd4e4c7k+TnAgQ6KYPDV/jav+cOwv5CbAoGBAMHbWg8VO5Q2aVUH2qKRFHYUBZP9yhPITOvrLaSM/LeR2ch+ffqqOP/rrEIcoFfq0AQMWH+ecvGea0g2LuEIxYNUgnY9SX8eGrLhWVi4hDTw++hLjONyKcUhc6so5w8As8miqu8FiDof3CBffiruOPMjVCBqSvzvrz88LHHDBBehAoGBAKUsMsl71BFGKDOa7VvKeNsMQrXuX4/7FiyUsmp4amehmwvBqzz31iScGLGymdGFG92r/APwAFpP9HdNA+ODmYGVxqc+ipwAxqm8AUHy62aXTIToQwC9oXlTnnTruk0tihMWHn+YRiiBtfx44BNVkLW29gKrae7h33d9gebGDKixAoGASIJESDn34/MKKOzcIUxEq/nHMFiP+iBEbUW098RoTj02iWCOvHBpC4haEQxmCRxiG/GGNI3OjoNju0nQ9Qjz37B/b7xyviJDeD8DzUSO5sJwZq3Dj9tGtl5RxmZxLlJj7Pp0Fx0pnBwImO8+4M3j7UV8lPdM/xijovJd6Vs5L6ECgYAW7zIH95/eYD3nSXYh5L8LPS8CP2bBDt5ZypAVUBfiRv/ywiEKBkoq0atGH9K67hbaP+XsPJbxRrGbQecBxw/+EtrsHmgvJrhTae7X8VKVK0a8+Zd0TUwOrMxJ0tUi3muLFPj6uU7hMhCqLYkFqd4GKKxJi+wQfIREHJwIu+DFyw==",
	//异步通知地址
	'notify_url' => "http://".$_SERVER['HTTP_HOST']."/pay/alipay-notify",
	//同步跳转
	'return_url' => "http://".$_SERVER['HTTP_HOST']."/pay/alipay-return",

	//编码格式
	'charset' => "UTF-8",

	//签名方式
	'sign_type'=>"RSA2",

	//支付宝网关
	'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

	//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
	'alipay_public_key' =>"MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAg2jBEPNk4Mec0vtcq3gZU6ExE9UQCTRfS6CKO5W3JvZbD2MdTADPsI4wx2ZoQJzsK0QPkdC9pbbkjbcktKQesUQmVlHbfQMe+PijyF4U6Ubw49lRNtqpOZqKJDah8JoncXXHzI9RnGiN/nfCGJHmb+F2uYcx5NgeqL2OwNLRC9nsNQkccDitoUQYll91+I5PNujDrMHjdn5XCljl0lRzI5Vkix7bKJ6cMf1OxBGwbavrkUDjIjXpzhd+471mi56JlZwpymxhrdkritpzRbKd0GQ+tiPli350W1TIer5g+CukD1A0HLviA3fXkG4PB2/Kz5VtMfoMTG2U8quxdW5QawIDAQAB",
);