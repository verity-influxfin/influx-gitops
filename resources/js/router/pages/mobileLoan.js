export default {
    template: `
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
                <div v-for="(item,index) in this.mobileData" :key="index">
                    <img :src="item.imageSrc" class="img-fluid">
                    <h3>ASUS Zenfone Max pro 2019</h3>
                    <p>空機價＄{{item.price}}</p>
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
    data: () => ({
        mobileData: []
    }),
    created() {
        this.getMobileData();
        console.log('mobileLoan');
    },
    methods: {
        getMobileData() {
            const $this = this;
            $.ajax({
                url: 'getMobileData',
                type: 'POST',
                dataType: 'json',
                success(data) {
                    $this.mobileData = data;
                }
            });
        }
    },
}