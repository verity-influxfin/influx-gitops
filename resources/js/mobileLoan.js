export default {
    template:`
        <div class="mobile-wrapper">
            <div class="mobile-header">
                <div class="header-title">
                    <img src="./image/child_banner.jpg">
                    <div class="title">手機分期</div>
                </div>
                <div class="banner-text">選擇喜歡的手機，無卡也能分期支付，輕鬆購買</div>
                <div class="header-img">
                    <img src="./image/sop_web.jpg" class="img-fluid desktop">
                    <img src="./image/sop_mobile.jpg" class="img-fluid mobile">
                </div>
            </div>
            <div class="mobile-content">
                <div v-for="index in 12" :key="index">
                    <img :src="'./image/mobile'+(index%3+1)+'.png'" class="img-fluid">
                    <h3>ASUS Zenfone Max pro 2019</h3>
                    <p>空機價＄{{index*1000}}</p>
                    <a class="btn btn-loan" href="https://event.influxfin.com/R/url?p=webbanner" target="_blank">立即申請分期</a>
                </div>
            </div>
            <div class="banner-text">優良店家推薦</div>
            <div>
                <img src="./image/mobile_banner_web.jpg" class="img-fluid desktop">
                <img src="./image/mobile_banner_mobile.jpg" class="img-fluid mobile">
            </div>
        </div>
    `,
    created(){
        console.log('mobileLoan');
    }
}