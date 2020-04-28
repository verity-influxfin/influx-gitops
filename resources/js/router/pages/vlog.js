export default {
    template:`
        <div class="video-wrapper">
            <div v-for="(item,index) in shares" class="video-container" :key="index">
                <div class="video-iframe">
                    <iframe :src="item.videoLink" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="video-info">
                    <h3>{{item.title}}</h3>
                    <h4>{{item.subTitle}}</h4>
                    <p>{{item.detail}}</p>
                    <router-link v-if="item.category === 'share'" :to="'/videopage/'+item.id" class="btn btn-info">了解更多</router-link>
                    <a v-if="item.category === 'loan'" href="https://event.influxfin.com/line?event=web" target="_blank" class="btn btn-warning">立即借款</a>
                    <a v-if="item.category === 'invest'" href="https://event.influxfin.com/r/iurl?p=webinvest" target="_blank" class="btn btn-primary">立即投資</a>
                    <a v-if="item.category === 'sponsor'" href="https://docs.google.com/forms/d/1Pp02TNm2wtWZdUwJpuW1J_ZCjx2QR_h8pgU5PNiE6ks/viewform?edit_requested=true" target="_blank" class="btn btn-success">贊助申請</a>
                </div>
            </div>
        </div>
    `,
    computed: {
        shares(){
            return this.$store.getters.SharesData;
        }
    },
    watch:{
        '$route'(to,from){
            this.refresh();
        }
    },
    created(){
        this.refresh();
        console.log('vlog');
    },
    methods:{
        refresh(){
            let category = this.$route.params.category === 'share' ? 'share' : 'other';
            this.$store.dispatch('getSharesData',{category});
        }
    }
}