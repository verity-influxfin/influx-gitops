export default {
    template:`
        <div class="blog-wrapper">
            <div class="blog-content">
                <div class="card" v-for="(item,index) in this.knowledge" :key="index">
                    <img :src="item.imageSrc" class="img-fluid">
                    <h5>{{item.title}}</h5>
                    <span">{{item.date}}</span>
                    <p class="gray">{{item.detail}}</p>
                    <router-link :to="item.link">閱讀更多》</router-link>
                </div>
            </div>
            <div class="pagination" ref="pagination"></div>
        </div>
    `,
    data:()=>({
        pageHtml:''
    }),
    computed: {
        knowledge(){
            return this.$store.getters.KnowledgeData;
        }
    },
    created(){
        console.log('blog');
    },
    mounted(){
        this.$nextTick(()=>{
            $(this.$refs.pagination).pagination();
        });
    }
}