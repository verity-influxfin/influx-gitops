import videoShareComponent from './component/videoShareComponent';
import bannerComponent from './component/bannerComponent';
import joinComponent from './component/joinComponent';
import applyDescribeComponent from './component/applyDescribeComponent';
import qaComponent from './component/qaComponent';

export default {
    template:`
        <div class="freshGraduate-wrapper">
            <banner :data="this.bannerData"  :isShowLoan="true"></banner>
            <div class="img-wrapper" data-aos="flip-up">
                <img src="./image/worker_web.jpg" class="img-fluid desktop">
                <img src="./image/worker_mobile.jpg" class="img-fluid mobile">
            </div>
            <applyDescribe :data="this.applyData" ref="apply"></applyDescribe>
            <join href="./image/child_banner.jpg" :isShowLoan="true"></join>
            <qa :data="this.qaData" title="常見問題"></qa>
            <videoShare ref="videoShare" title="借款人怎麼說？" :data="this.shares"></videoShare>
        </div>
    `,
    components:{
        'videoShare':videoShareComponent,
        'banner':bannerComponent,
        'join':joinComponent,
        'applyDescribe':applyDescribeComponent,
        'qa':qaComponent
    },
    data:()=>({
        qaData:[],
        bannerData:{},
        applyData:{}
    }),
    computed: {
        shares(){
            return this.$store.getters.SharesData;
        }
    },
    created(){
        this.$store.dispatch('getSharesData',{category:'loan'});
        this.getApplydata();
        this.getBannerData();
        this.getQaData();
        console.log('workerLoan');
    },
    mounted(){
        $(this.$refs.apply.$refs.apply_slick).attr('data-aos','fade-up');
        $(this.$refs.videoShare.$refs.share_content).attr('data-aos','fade-left');
        AOS.init();
    },
    methods:{
        getBannerData(){
            const $this = this;
            $.ajax({
                url:'getBannerData',
                type:'POST',
                dataType:'json',
                data:{
                    filter:'freshgraduate'
                },
                success(data){
                    $this.bannerData = data;
                }
            });
        },
        getApplydata(){
            const $this = this;
            $.ajax({
                url:'getApplydata',
                type:'POST',
                dataType:'json',
                data:{
                    filter:'freshgraduate'
                },
                success(data){
                    $this.applyData = data;
                    $this.$nextTick(()=>{
                        $this.$refs.apply.createSlick();
                    });
                }
            });
        },
        getQaData(){
            const $this = this;
            $.ajax({
                url:'getQaData',
                type:'POST',
                data:{
                    filter:'freshgraduate'
                },
                dataType:'json',
                success(data){
                    $this.qaData = data;
                }
            });
        }
    }

};