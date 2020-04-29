import shareBtnComponent from './component/shareBtnComponent';

export default {
    template:`
        <div class="content-wrapper">
            <div class="main">
                <h3 class="title">{{this.videoTitle}}</h3>
                <div class="title-img"><img :src="this.videoImg" class="img-fluid"></div>
                <div class="video-container"><iframe :src="this.videoLink" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>
                <div class="main-content" v-html="this.videoHtml"></div>
            </div>
            <fb:comments :href="this.link" num_posts="10" notify="true" :width="(window.outerWidth*0.99).toFixed(0)"></fb:comments>
            <shareBtn :link="this.link"></shareBtn>
        </div>
    `,
    components:{
        'shareBtn':shareBtnComponent
    },
    data:()=>({
        link : window.location.toString().replace('#','%23'),
        videoTitle:'',
        videoImg:'',
        videoLink:'',
        videoHtml:''
    }),
    created(){
        $('title').text(`${this.$route.params.id} - inFlux普匯金融科技`);
        this.getVideoPage();
    },
    methods:{
        getVideoPage(){
            const $this = this;

            $.ajax({
                url:'getVideoPage',
                type:'POST',
                dataType:'json',
                data:{
                    filter:$this.$route.params.id
                },
                success(data){
                    FB.XFBML.parse();
                    $this.videoTitle = data.title;
                    $this.videoImg = data.imageSrc;
                    $this.videoLink = data.videoSrc;
                    $this.videoHtml = data.content;
                }
            });

        }
    }
}