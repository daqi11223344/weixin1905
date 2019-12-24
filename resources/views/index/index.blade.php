@extends('layout.app')
@section('title','Mstore - Online Shop Mobile Template')
@section('content')
<body>

<!-- side nav right-->
<div class="side-nav-panel-right">
    <ul id="slide-out-right" class="side-nav side-nav-panel collapsible">
        <li class="profil">
            <img src="img/profile.jpg" alt="">
            <h2>John Doe</h2>
        </li>
        <li><a href="setting.html"><i class="fa fa-cog"></i>Settings</a></li>
        <li><a href="about-us.html"><i class="fa fa-user"></i>About Us</a></li>
        <li><a href="contact.html"><i class="fa fa-envelope-o"></i>Contact Us</a></li>
        <li><a href="login.html"><i class="fa fa-sign-in"></i>Login</a></li>
        <li><a href="register.html"><i class="fa fa-user-plus"></i>Register</a></li>
    </ul>
</div>
<!-- end side nav right-->


<!-- slider -->
<div class="slider">

    <ul class="slides">
        <li>
            <img src="img/slide1.jpg" alt="">
            <div class="caption slider-content  center-align">
                <h2>WELCOME TO MSTORE</h2>
                <h4>Lorem ipsum dolor sit amet.</h4>
                <a href="" class="btn button-default">SHOP NOW</a>
            </div>
        </li>
        <li>
            <img src="img/slide2.jpg" alt="">
            <div class="caption slider-content center-align">
                <h2>JACKETS BUSINESS</h2>
                <h4>Lorem ipsum dolor sit amet.</h4>
                <a href="" class="btn button-default">SHOP NOW</a>
            </div>
        </li>
        <li>
            <img src="img/slide3.jpg" alt="">
            <div class="caption slider-content center-align">
                <h2>FASHION SHOP</h2>
                <h4>Lorem ipsum dolor sit amet.</h4>
                <a href="" class="btn button-default">SHOP NOW</a>
            </div>
        </li>
    </ul>

</div>
<!-- end slider -->

<!-- features -->
<div class="features section">
    <div class="container">
        <div class="row">
            <div class="col s6">
                <div class="content">
                    <div class="icon">
                        <i class="fa fa-car"></i>
                    </div>
                    <h6>Free Shipping</h6>
                    <p>Lorem ipsum dolor sit amet consectetur</p>
                </div>
            </div>
            <div class="col s6">
                <div class="content">
                    <div class="icon">
                        <i class="fa fa-dollar"></i>
                    </div>
                    <h6>Money Back</h6>
                    <p>Lorem ipsum dolor sit amet consectetur</p>
                </div>
            </div>
        </div>
        <div class="row margin-bottom-0">
            <div class="col s6">
                <div class="content">
                    <div class="icon">
                        <i class="fa fa-lock"></i>
                    </div>
                    <h6>Secure Payment</h6>
                    <p>Lorem ipsum dolor sit amet consectetur</p>
                </div>
            </div>
            <div class="col s6">
                <div class="content">
                    <div class="icon">
                        <i class="fa fa-support"></i>
                    </div>
                    <h6>24/7 Support</h6>
                    <p>Lorem ipsum dolor sit amet consectetur</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end features -->

<!-- quote -->
<div class="section quote">
    <div class="container">
        <h4>FASHION UP TO 50% OFF</h4>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid ducimus illo hic iure eveniet</p>
    </div>
</div>
<!-- end quote -->

<!-- product -->
<div class="section product">
    <div class="container">
        <div class="section-head">
            <h4>NEW PRODUCT</h4>
            <div class="divider-top"></div>
            <div class="divider-bottom"></div>
        </div>
        <div class="row">
            @foreach ($post as $v)
                <div class="col s6">
                    <div class="content">
                        <img src="/storage/{{$v->img}}" alt="">
                        <h6><a href="{{url('shop/?id='.$v->id)}}">{{$v->goods_name}}</a></h6>
                        <div class="price">
                            ${{$v->price}} <span>${{$v->price}}</span>
                        </div>
                        <button class="btn button-default">ADD TO CART</button>
                    </div>
                </div>
            @endforeach
                </div>

<!-- end product -->

        <div class="pagination-product">
            <ul>
                <li class="active">1</li>
                <li><a href="">2</a></li>
                <li><a href="">3</a></li>
                <li><a href="">4</a></li>
                <li><a href="">5</a></li>
            </ul>
        </div>
    </div>
</div>
<!-- end product -->

<!-- loader -->
<div id="fakeLoader"></div>
<!-- end loader -->

<!-- footer -->
<div class="footer">
    <div class="container">
        <div class="about-us-foot">
            <h6>Mstore</h6>
            <p>is a lorem ipsum dolor sit amet, consectetur adipisicing elit consectetur adipisicing elit.</p>
        </div>
        <div class="social-media">
            <a href=""><i class="fa fa-facebook"></i></a>
            <a href=""><i class="fa fa-twitter"></i></a>
            <a href=""><i class="fa fa-google"></i></a>
            <a href=""><i class="fa fa-linkedin"></i></a>
            <a href=""><i class="fa fa-instagram"></i></a>
        </div>
        <div class="copyright">
            <span>© 2017 All Right Reserved</span>
        </div>
    </div>
</div>
<!-- end footer -->

@endsection
<script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
<script>


    wx.config({
        debug:true,//开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: "{{$wx_config['appId']}}", // 必填，公众号的唯一标识
        timestamp: "{{$wx_config['timestamp']}}", // 必填，生成签名的时间戳
        nonceStr: "{{$wx_config['nonceStr']}}", // 必填，生成签名的随机串
        signature: "{{$wx_config['signature']}}",// 必填，签名
        jsApiList: ['updateAppMessageShareData','chooseImage','updateTimelineShareData'] // 必填，需要使用的JS接口列表
    });

    wx.ready(function () {   //需在用户可能点击分享按钮前就先调用
        //发送给朋友
        wx.updateAppMessageShareData({
            title: '分享测试', // 分享标题
            desc: '描述', // 分享描述
            link: 'http://wangqi.bianaoao.top/', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: 'http://wangqi.bianaoao.top/wx_media/imgs/2019121414155029246.jpeg', // 分享图标
            success: function () {
                // 设置成功
                alert(11111);
            }
        })

        //分享到盆友圈
        wx.ready(function () {      //需在用户可能点击分享按钮前就先调用
            wx.updateTimelineShareData({
                title: '分享测试', // 分享标题
                link: 'http://wangqi.bianaoao.top/', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: 'http://wangqi.bianaoao.top/wx_media/imgs/2019121414155029246.jpeg', // 分享图标
                success: function () {
                    alert("分享成功");
                }
            })
        });
    });

</script>


