<?php
include_once('../../system/pay/unionpay/func/common.php');
include_once('../../system/pay/unionpay/func/secureUtil.php');

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
    <meta content="telephone=no" name="format-detection" />
    <title>好就分享</title>
    <link rel="stylesheet" href="../css/pay.css"/>
</head>
<body>
<!-- header S -->
<div class="header">
    <div class="logo">好就分享</div>
    <a href="javascript:void(0)" class="menu">菜单</a>
    <a href="javascript:void(0)" class="search">搜索</a>
    <a href="javascript:void(0)" class="buy">购物车</a>
    <div class="search-cont">
        <div class="l-logo">好就分享</div>
        <div class="search-box">
            <input type="text" class="text" placeholder="搜索"/>
        </div>
        <a href="javascript:void(0)" class="search-btn">取消</a>
    </div>
</div>
<div class="menu-cont">
    <div class="mlist-cont">
        <div class="mlist" id="mlist">
            <ul class="nav-list">
                <li><a href="javascript:void(0)"><span class="index">首页</span></a></li>
                <li class="list-shop">
                    <div class="toolbar">
                        <a href="javascript:void(0)"><span class="hotshop">热门商品</span></a>
                        <a href="javascript:void(0)" class="more">更多</a>
                    </div>
                    <ul class="shop-list">
                        <li><a href="javascript:void(0)">品牌馆</a></li>
                        <li><a href="javascript:void(0)">爆品馆</a></li>
                        <li><a href="javascript:void(0)">健康馆</a></li>
                        <li><a href="javascript:void(0)">科技馆</a></li>
                        <li><a href="javascript:void(0)">成人馆</a></li>
                        <li><a href="javascript:void(0)">分享馆</a></li>
                        <li><a href="javascript:void(0)">活动馆</a></li>
                    </ul>
                </li>
                <li><a href="javascript:void(0)"><span class="jchd">精彩活动</span></a></li>
<!--                <li><a href="javascript:void(0)"><span class="share">分享热榜</span></a></li>-->
                <li class="list-my">
                    <div class="toolbar">
                        <a href="javascript:void(0)"><span class="my">我的</span></a>
                        <a href="javascript:void(0)" class="more">更多</a>
                    </div>
                    <ul class="mylist">
                        <li><a href="javascript:void(0)">购物车</a></li>
                        <li><a href="javascript:void(0)">我的订单</a></li>
                        <li><a href="javascript:void(0)">我的积分</a></li>
                        <li><a href="javascript:void(0)">我的销售体系</a></li>
                        <li><a href="javascript:void(0)">我的分享</a></li>
                        <li><a href="javascript:void(0)">我的信息</a></li>
                        <li><a href="javascript:void(0)">我的收藏</a></li>
                    </ul>
                </li>
                <li class="last">
                    <a href="javascript:void(0)" class="login">登录/注册</a>
                </li>
            </ul>
        </div>
    </div>
    <a href="javascript:void(0)" class="nav_close">关闭</a>
</div>
<!-- login S -->
<div class="tlBg"></div>
<div class="tlCont">
    <a href="javascript:void(0)" class="close ui-login-close">关闭</a>
    <div class="bd ui-login undis">
        <h4>登录</h4>
        <div class="icont">
            <input type="text" class="phone" placeholder="好就分享账号/手机号"/>
        </div>
        <div class="icont">
            <input type="password" class="mima" placeholder="请输入密码"/>
        </div>
        <a href="javascript:void(0)" class="btn_01">登录</a>
        <a href="javascript:void(0)" class="btn_02">注册</a>
        <div class="uInfo">
            <a href="javascript:void(0)" class="rmme ui-rmme">记住我</a>
            <a href="javascript:void(0)" class="wjpw">忘记密码？</a>
        </div>
        <div class="title"><span>或从以下方式登录</span></div>
        <div class="shareLink">
            <a href="javascript:void(0)" class="qq">QQ登录</a><a href="javascript:void(0)" class="weibo">微博登录</a><a href="javascript:void(0)" class="weixin">微信登录</a>
        </div>
    </div>
    <div class="bd ui-register undis">
        <h4>欢迎注册账号</h4>
        <div class="icont">
            <input type="text" class="user" placeholder="请输入账号"/>
        </div>
        <div class="icont">
            <input type="password" class="mima" placeholder="请输入密码"/>
        </div>
        <div class="icont">
            <input type="password" class="mima" placeholder="请再次输入密码"/>
        </div>
        <div class="icont">
            <input type="text" class="phone" placeholder="请输入手机号"/>
        </div>
        <div class="icont">
            <div class="ibox">
                <input type="text" class="pw" placeholder="请输入手机验证码"/>
            </div>
            <a href="javascript:void(0)" class="getcode">获取验证码</a>
        </div>
        <div class="icont">
            <input type="text" class="pw" placeholder="邀请码"/>
        </div>
        <div class="uXieyi">
            <a href="javascript:void(0)" class="rmme ui-rmme">我同意“服务条款”和“用户隐私权保护和个人信息利用政策”</a>
        </div>
        <a href="javascript:void(0)" class="btn_03">注册</a>
    </div>
    <div class="bd ui-wjmm undis">
        <h4>找回密码</h4>
        <div class="icont">
            <input type="text" class="phone" placeholder="请输入手机号"/>
        </div>
        <a href="javascript:void(0)" class="btn_01">发送密码到手机</a>
        <p class="tipstxt">120秒内不可重复发送</p>
    </div>
</div>
<!-- login E -->
<!-- header E -->
<!-- wrap S -->
<div class="wrap">
    <div class="pay-succ">
        <?php
        if(isset($_GET['test']) && $_GET['test'] == 1){
            echo '交易成功!';
        }else{

            if (isset ( $_POST ['signature'] )) {
                $isSuccess = verify ( $_POST );
                if($isSuccess){
                    $orderNo = $_POST['orderId'];
                    $respCode = $_POST ['respCode'];
                    $respMsg = $_POST ['respMsg'];
                    if($respCode && $respCode=='00' || $respCode == 'A6'){
                        echo '交易成功!';
                    }else{
                        echo '交易失败!';
                    }
                }else{
                    echo '交易失败!';
                }
            } else {
                echo '交易失败!';
            }
        }

        ?>
    </div>
    <div class="maybe">
        <div class="tit tc">
            您可能喜欢的其他商品
        </div>
        <div class="shoplist-cont">
            <ul class="splist">
                <!--<li>-->
                    <!--<img src="../pic/pic06.jpg" />-->
                <!--</li>-->
                <!--<li>-->
                    <!--<img src="../pic/pic06.jpg" />-->
                <!--</li>-->
                <!--<li>-->
                    <!--<img src="../pic/pic06.jpg" />-->
                <!--</li>-->
                <!--<li>-->
                    <!--<img src="../pic/pic06.jpg" />-->
                <!--</li>-->
                <!--<li>-->
                    <!--<img src="../pic/pic06.jpg" />-->
                <!--</li>-->
                <!--<li>-->
                    <!--<img src="../pic/pic06.jpg" />-->
                <!--</li>-->
                <!--<li>-->
                    <!--<img src="../pic/pic06.jpg" />-->
                <!--</li>-->
                <!--<li>-->
                    <!--<img src="../pic/pic06.jpg" />-->
                <!--</li>-->
                <!--<li>-->
                    <!--<img src="../pic/pic06.jpg" />-->
                <!--</li>-->
            </ul>
        </div>
    </div>
</div>
<!-- wrap E -->
<!-- footer S -->
<div class="footer">
    <p>享用科技有限公司版权所有©2015-2020</p>
</div>
<!-- footer E -->

<!-- script S -->
<script src="../js/zepto.min.js"></script>
<script src="../js/iscroll.js"></script>
<script src="../js/common.js"></script>
<script src="../js/pay-02.js"></script>
<!-- script E -->
</body>
</html>