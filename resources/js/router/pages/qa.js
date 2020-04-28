import qaComponent from './component/qaComponent';

export default {
    template:`
        <div class="qaPage-wrapper" id="qaPage">
            <div class="qaPage-header">
                <div class="header-title">
                    <img src="./image/child_banner.jpg">
                    <div class="title">常見問題</div>
                    <div class="qa-btn-row">
                        <a href="#loan">借款常見問題</a>
                        <a href="#invest">投資常見問題</a>
                        <a href="#afterLoanData">貸後常見問題</a>
                    </div>
                </div>
            </div>
            <div class="qaPage-content">
                <div id="loan"><qa :data="this.qaData.loanData" category="loanData" title="借款常見問題" :hideLink="true"></qa></div>
                <div id="invest"><qa :data="this.qaData.investData" category="invest" title="投資常見問題" :hideLink="true"></qa></div>
                <div id="afterLoanData"><qa :data="this.qaData.afterLoanData" category="afterLoanData" title="貸後常見問題" :hideLink="true"></qa></div>
            </div>
            <a class="back-top" href="#qaPage"><i class="fas fa-arrow-left"></i></a>
        </div>
    `,
    components:{
        'qa':qaComponent
    },
    data:()=>({
        qaData:{}
    }),
    created(){
        this.getQaData();
        console.log('qa');
    },
    methods:{
        getQaData(){
            const $this = this;
            $.ajax({
                url:'getQaData',
                type:'POST',
                data:{
                    filter:'qa'
                },
                dataType:'json',
                success(data){
                    $this.qaData = data;
                }
            });
        }
    }
}




