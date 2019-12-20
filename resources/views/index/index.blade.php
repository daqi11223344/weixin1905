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
            <div class="col s6">
                <div class="content">
                    <img src="img/product-new1.png" alt="">
                    <h6><a href="">Fashion Men's</a></h6>
                    <div class="price">
                        $20 <span>$28</span>
                    </div>
                    <button class="btn button-default">ADD TO CART</button>
                </div>
            </div>
            <div class="col s6">
                <div class="content">
                    <img src="img/product-new2.png" alt="">
                    <h6><a href="">Fashion Men's</a></h6>
                    <div class="price">
                        $20 <span>$28</span>
                    </div>
                    <button class="btn button-default">ADD TO CART</button>
                </div>
            </div>
        </div>
        <div class="row margin-bottom">
            <div class="col s6">
                <div class="content">
                    <img src="img/product-new3.png" alt="">
                    <h6><a href="">Fashion Men's</a></h6>
                    <div class="price">
                        $20 <span>$28</span>
                    </div>
                    <button class="btn button-default">ADD TO CART</button>
                </div>
            </div>
            <div class="col s6">
                <div class="content">
                    <img src="img/product-new4.png" alt="">
                    <h6><a href="">Fashion Men's</a></h6>
                    <div class="price">
                        $20 <span>$28</span>
                    </div>
                    <button class="btn button-default">ADD TO CART</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end product -->

<!-- promo -->
<div class="promo section">
    <div class="container">
        <div class="content">
            <h4>PRODUCT BUNDLE</h4>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit</p>
            <button class="btn button-default">SHOP NOW</button>
        </div>
    </div>
</div>
<!-- end promo -->

<!-- product -->
<div class="section product">
    <div class="container">
        <div class="section-head">
            <h4>TOP PRODUCT</h4>
            <div class="divider-top"></div>
            <div class="divider-bottom"></div>
        </div>
        <div class="row">
            <div class="col s6">
                <div class="content">
                    <img src="img/product-new1.png" alt="">
                    <h6><a href="">Fashion Men's</a></h6>
                    <div class="price">
                        $20 <span>$28</span>
                    </div>
                    <button class="btn button-default">ADD TO CART</button>
                </div>
            </div>
            <div class="col s6">
                <div class="content">
                    <img src="img/product-new2.png" alt="">
                    <h6><a href="">Fashion Men's</a></h6>
                    <div class="price">
                        $20 <span>$28</span>
                    </div>
                    <button class="btn button-default">ADD TO CART</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col s6">
                <div class="content">
                    <img src="img/product-new3.png" alt="">
                    <h6><a href="">Fashion Men's</a></h6>
                    <div class="price">
                        $20 <span>$28</span>
                    </div>
                    <button class="btn button-default">ADD TO CART</button>
                </div>
            </div>
            <div class="col s6">
                <div class="content">
                    <img src="img/product-new4.png" alt="">
                    <h6><a href="">Fashion Men's</a></h6>
                    <div class="price">
                        $20 <span>$28</span>
                    </div>
                    <button class="btn button-default">ADD TO CART</button>
                </div>
            </div>
        </div>
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


