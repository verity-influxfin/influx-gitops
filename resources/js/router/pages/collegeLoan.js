import videoShareComponent from './component/videoShareComponent';
import experienceComponent from './component/experienceComponent';
import bannerComponent from './component/bannerComponent';
import joinComponent from './component/joinComponent';
import applyDescribeComponent from './component/applyDescribeComponent';
import qaComponent from './component/qaComponent';

export default {
    template:`
        <div class="college-wrapper">
            <banner :data="this.bannerData" :isShowLoan="true"></banner>
            <experience ref="experience" title="聽聽大家怎麼說" :data="this.experiences"></experience>
            <applyDescribe :data="this.applyData" ref="apply"></applyDescribe>
            <join href="./image/child_banner.jpg" :isShowLoan="true" subTitle="加入普匯完成你的目標吧！"></join>
            <qa :data="this.qaData" title="常見問題"></qa>
            <videoShare ref="videoShare" title="Follow普匯小學堂<br>增進科普金融知識" :data="this.shares"></videoShare>
        </div>
    `,
    components:{
        'videoShare':videoShareComponent,
        'experience':experienceComponent,
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
        experiences(){
            return this.$store.getters.ExperiencesData;
        },
        shares(){
            return this.$store.getters.SharesData;
        }
    },
    created(){
        this.$store.dispatch('getExperiencesData');
        this.$store.dispatch('getSharesData',{category:'share'});
        this.getApplydata();
        this.getBannerData();
        this.getQaData();
        $('title').text(`學生貸款 - ${$('title').text()}`);
    },
    mounted(){
        $(this.$refs.videoShare.$refs.share_content).attr('data-aos','fade-left');
        $(this.$refs.experience.$refs.experience_slick).attr('data-aos','zoom-in');
        $(this.$refs.apply.$refs.apply_slick).attr('data-aos','fade-up');
        AOS.init();
    },
    watch: {
        experiences(){
            this.$nextTick(() => {
                this.$refs.experience.createSlick();
            });
        }
    },
    methods:{
        getBannerData(){
            const $this = this;
            $.ajax({
                url:'getBannerData',
                type:'POST',
                dataType:'json',
                data:{
                    filter:'college'
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
                    filter:'college'
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
                    filter:'college'
                },
                dataType:'json',
                success(data){
                    $this.qaData = data;
                }
            });
        }
    }

};