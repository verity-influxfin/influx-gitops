export default {
    template:`
        <div class="blog-wrapper">
            <div class="blog-content" v-html="this.pageHtml"></div>
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
        $('title').text(`influx 小學堂 - inFlux普匯金融科技`);
        this.$store.dispatch('getKnowledgeData');
    },
    watch:{
        knowledge(){
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
                    dataSource: $this.knowledge,
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