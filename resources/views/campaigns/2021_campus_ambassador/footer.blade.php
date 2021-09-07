<section class="footer">
    <div class="row my-4">
        <div class="col-lg-3 text-center">
            <a class="site-link" href="{{url('/')}}">
                <img src="{{image_url('influx_logo.svg')}}" class="img-logo" alt="普匯金融科技官方網站" />
            </a>
        </div>
        <div class="col-lg-6 text-center d-none d-lg-block">
            <p class="copyright">Copyright &copy;{{date('Y')}} 普匯金融科技股份有限公司 All rights reserved.</p>
        </div>
        <div class="col-lg-3 text-center">
            <div class="d-flex flex-row justify-content-evenly">
                <a href="https://facebook.com/inFluxtw/" target="_blank" class="d-flex flex-row align-items-center sns-link">
                    <img class="img-logo" src="{{image_url('sns_fb_logo.svg')}}">
                    Facebook
                </a>
                <a href="https://www.instagram.com/pop.finance/" target="_blank" class="d-flex flex-row align-items-center sns-link">
                    <img class="img-logo" src="{{image_url('sns_ig_logo.svg')}}">
                    Instagram
                </a>
            </div>
        </div>
        <div class="col-lg-6 text-center d-block d-lg-none">
            <p class="copyright">Copyright &copy;{{date('Y')}} 普匯金融科技股份有限公司 All rights reserved.</p>
        </div>
    </div>
</section>

<style type="text/css">
    section.footer {
        min-height: 90px;
        background-color: #145da1;
        color: white;
        font-size: 22px;
    }
    .footer .site-link {
        display: inline-block;
    }
    .footer .site-link .img-logo {
        width: 6em;
    }
    .footer .copyright {
        font-size: 0.6em;
        font-weight: normal;
        margin-top: .5em;
    }
    .footer .sns-link {
        color: white;
        text-decoration: none;
        font-size: 0.65em;
        font-weight: normal;
    }
    .footer .sns-link:hover {
        text-decoration: underline;
    }
    .footer .sns-link .img-logo {
        height: 1.6em;
        margin-right: 0.5em;
    }
    @media (max-width:  767.98px) {
        .footer .site-link {
            margin-bottom: 3em;
        }
        .footer .site-link .img-logo {
            width: 8em;
        }
        .footer .sns-link {
            font-size: 0.8em;
            margin-bottom: 3em;
        }
    }
</style>