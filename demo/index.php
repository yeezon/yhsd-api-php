<?php

var_dump($_GET);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>友好速搭Demo</title>
</head>
<body>
<h2>测试友好速搭API</h2><hr/>

<h3>开放应用</h3><br/>
<div style="margin: 0 auto;">
    <ul>
        <li><a href="result.php?method=authorize_url&type=public">生成用户跳转地址</a> </li>
        <li><a href="result.php?method=generate_token&type=public">获取店铺访问token</a> </li>
        <li><a href="result.php?method=get&type=public&url=products">GET方法</a> </li>
        <li><a href="result.php?method=post&type=public&url=products">POST方法</a> </li>
        <li><a href="result.php?method=put&type=public&url=products&id=22954">PUT方法</a> </li>
        <li><a href="result.php?method=delete&type=public&url=products&id=22953">DELETE方法</a> </li>
        <li><a href="result.php?method=hmac_verify&type=public">验证hmac</a> </li>
        <li><a href="result.php?method=thirdapp_aes_encrypt&type=public">第三方接入支持</a> </li>
        <li><a href="result.php?method=webhook_verify&type=public">验证 webhook 签名</a> </li>
        <li><a href="result.php?method=openpayment_verify&type=public">验证开放支付的回调来源</a> </li>


        </ul>
</div>
<h3>私有应用</h3><br/>
<div style="margin: 0 auto;">
    <ul>
        <li><a href="result.php?method=authorization&type=private">生成访问token的Authorization</a> </li>
        <li><a href="result.php?method=generate_token&type=private">获取店铺访问token</a> </li>
        <li><a href="result.php?method=get&type=private&url=products">GET方法</a> </li>
        <li><a href="result.php?method=post&type=private&url=products">POST方法</a> </li>
        <li><a href="result.php?method=put&type=private&url=products&id=22954">PUT方法</a> </li>
        <li><a href="result.php?method=delete&type=private&url=products&id=22953">DELETE方法</a> </li>
        <li><a href="result.php?method=hmac_verify&type=private&hmac=4497a8354ab52e69b2718282f436e5d5efee1ab887be52c759652fd16e755ff4&time_stamp=2016-01-07T00%3A56%3A48Z">验证hmac</a> </li>
        <li><a href="result.php?method=thirdapp_aes_encrypt&type=private">第三方接入支持</a> </li>
        <li><a href="result.php?method=webhook_verify&type=private">验证 webhook 签名</a> </li>
        <li><a href="result.php?method=openpayment_verify&type=private">验证开放支付的回调来源</a> </li>
    </ul>
</div>
</body>
</html>
