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
            <div class="pagination" ref="pagination"></div>
        </div>
    `,
    computed: {
        news() {
            return this.$store.getters.NewsData;
        },
    },
    created(){
        this.$store.dispatch('getNewsData');
        $('title').text(`最新消息 - inFlux普匯金融科技`);
    },
    watch:{
        news(){
            this.pagination();
        }
    },
    mounted(){
        this.pagination();
    },
    methods:{
        pagination(){
            const $this = this;
            $this.$nextTick(()=>{
                $($this.$refs.pagination).pagination({
                    dataSource: $this.news,
                    callback(data, pagination) {
                        data.forEach((item,index)=>{
                            $this.pageHtml += `
                                <div class="card">
                                    <img src="${item.imageSrc}" class="img-fluid">
                                    <h5>${item.title}</h5>
                                    <span>${item.date}</span>
                                    <p class="gray">${item.detail}</p>
                                    <a href="#${item.link}">閱讀更多》</a>
                                </div>
                            `;
                        });
                    }
                });
            });
        }
    }
}