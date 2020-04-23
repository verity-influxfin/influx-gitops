export default {
    template:`
        <div class="blog-wrapper">
            <div class="blog-content" v-html="this.pageHtml"></div>
            <div class="pagination" ref="pagination"></div>
        </div>
    `,
    data:()=>({
        news:[
            {
                'imgSrc':'https://www.influxfin.com/wp-content/uploads/2020/04/小學堂.jpg',
                'title':'資產翻倍術 – 普匯投資人實例分享',
                'date':'2020-04-16',
                'content':'退休小學教師——王媽媽的投資經驗，王媽媽從2019年8月開始使用普匯投資債權，第一個月投入20萬本金，分散成200筆1000元，每月回款穩定，並將收回的本利再繼續投資新債權，額外每月挹注資金，以錢滾錢，截至今天已累積517筆！',
                'link':'#'
            },{
                'imgSrc':'https://www.influxfin.com/wp-content/uploads/2020/04/小學堂.jpg',
                'title':'資產翻倍術 – 普匯投資人實例分享',
                'date':'2020-04-16',
                'content':'退休小學教師——王媽媽的投資經驗，王媽媽從2019年8月開始使用普匯投資債權，第一個月投入20萬本金，分散成200筆1000元，每月回款穩定，並將收回的本利再繼續投資新債權，額外每月挹注資金，以錢滾錢，截至今天已累積517筆！',
                'link':'#'
            },{
                'imgSrc':'https://www.influxfin.com/wp-content/uploads/2020/04/小學堂.jpg',
                'title':'資產翻倍術 – 普匯投資人實例分享',
                'date':'2020-04-16',
                'content':'退休小學教師——王媽媽的投資經驗，王媽媽從2019年8月開始使用普匯投資債權，第一個月投入20萬本金，分散成200筆1000元，每月回款穩定，並將收回的本利再繼續投資新債權，額外每月挹注資金，以錢滾錢，截至今天已累積517筆！',
                'link':'#'
            },{
                'imgSrc':'https://www.influxfin.com/wp-content/uploads/2020/04/小學堂.jpg',
                'title':'資產翻倍術 – 普匯投資人實例分享',
                'date':'2020-04-16',
                'content':'退休小學教師——王媽媽的投資經驗，王媽媽從2019年8月開始使用普匯投資債權，第一個月投入20萬本金，分散成200筆1000元，每月回款穩定，並將收回的本利再繼續投資新債權，額外每月挹注資金，以錢滾錢，截至今天已累積517筆！',
                'link':'#'
            },{
                'imgSrc':'https://www.influxfin.com/wp-content/uploads/2020/04/小學堂.jpg',
                'title':'資產翻倍術 – 普匯投資人實例分享',
                'date':'2020-04-16',
                'content':'退休小學教師——王媽媽的投資經驗，王媽媽從2019年8月開始使用普匯投資債權，第一個月投入20萬本金，分散成200筆1000元，每月回款穩定，並將收回的本利再繼續投資新債權，額外每月挹注資金，以錢滾錢，截至今天已累積517筆！',
                'link':'#'
            },{
                'imgSrc':'https://www.influxfin.com/wp-content/uploads/2020/04/小學堂.jpg',
                'title':'資產翻倍術 – 普匯投資人實例分享',
                'date':'2020-04-16',
                'content':'退休小學教師——王媽媽的投資經驗，王媽媽從2019年8月開始使用普匯投資債權，第一個月投入20萬本金，分散成200筆1000元，每月回款穩定，並將收回的本利再繼續投資新債權，額外每月挹注資金，以錢滾錢，截至今天已累積517筆！',
                'link':'#'
            },
        ],
        pageHtml:''
    }),
    created(){
        console.log('blog');
    },
    mounted(){
        this.pagination();
    },
    methods:{
        pagination(){
            const $this = this;
            let container = $this.$refs.pagination;
            $(container).pagination({
                dataSource: $this.news,
                callback(data, pagination){
                    let html = '';
                    data.forEach((row,index)=>{
                        $this.pageHtml += `
                            <div class="card">
                                <img src="${row.imgSrc}" class="img-fluid">
                                <h5>${row.title}</h5>
                                <span">${row.date}</span>
                                <p class="gray">${row.content}</p>
                                <a href="#">閱讀更多》</a>
                            </div>
                        `;
                    });
                }
            })
        }
    }
}