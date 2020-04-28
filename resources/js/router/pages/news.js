export default {
    template: `
        <div class="news-page-wrapper">
            <h1>最新消息</h1>
            <div class="news-content">
                <div v-for="(item,index) in this.news" class="card" :key="index">
                    <router-link :to="item.link"><img :src="item.imageSrc" class="img-fluid"></router-link>
                    <span>{{item.title}}</span>
                    <p><span class="gray">Posted by </span>{{item.author}}<span class="gray"> Comments:</span></p>
                    <p class="gray">{{item.content}}…</p>
                    <router-link class="btn btn-info" :to="item.link">Read More</router-link>
                </div>
            </div>
        </div>
    `,
    computed: {
        news() {
            return this.$store.getters.NewsData;
        },
    },
    created(){
        $('title').text(`最新消息 - inFlux普匯金融科技`);
    }
}