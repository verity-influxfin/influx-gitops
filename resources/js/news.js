export default {
    template:`
        <div class="news-page-wrapper">
            <h1>最新消息</h1>
            <div class="news-content">
                <div v-for="(item,index) in this.news" class="card" :key="index">
                    <img :src="item.imgSrc" class="img-fluid">
                    <span></span>
                    <p><span class="gray">Posted by </span>inFlux 普匯金融科技2020-04-01<span class="gray"> Comments:</span></p>
                    <p class="gray">{{item.content}}…</p>
                    <router-link class="btn btn-info" to="#">Read More</router-link>
                </div>
            </div>
        </div>
    `,
    data:()=>({
        news:[
            {
                'imgSrc':'./image/statement.png',
                'subTitle':'小小幫助，溫暖回饋',
                'author':'inFlux 普匯金融科技2020-04-01',
                'content':'普匯默默支持需要幫助的年輕人 並建立平台搭',
                'link':'#'
            },{
                'imgSrc':'./image/statement.png',
                'subTitle':'小小幫助，溫暖回饋',
                'author':'inFlux 普匯金融科技2020-04-01',
                'content':'普匯默默支持需要幫助的年輕人 並建立平台搭',
                'link':'#'
            },{
                'imgSrc':'./image/statement.png',
                'subTitle':'小小幫助，溫暖回饋',
                'author':'inFlux 普匯金融科技2020-04-01',
                'content':'普匯默默支持需要幫助的年輕人 並建立平台搭',
                'link':'#'
            },{
                'imgSrc':'./image/statement.png',
                'subTitle':'小小幫助，溫暖回饋',
                'author':'inFlux 普匯金融科技2020-04-01',
                'content':'普匯默默支持需要幫助的年輕人 並建立平台搭',
                'link':'#'
            },{
                'imgSrc':'./image/statement.png',
                'subTitle':'小小幫助，溫暖回饋',
                'author':'inFlux 普匯金融科技2020-04-01',
                'content':'普匯默默支持需要幫助的年輕人 並建立平台搭',
                'link':'#'
            },{
                'imgSrc':'./image/statement.png',
                'subTitle':'小小幫助，溫暖回饋',
                'author':'inFlux 普匯金融科技2020-04-01',
                'content':'普匯默默支持需要幫助的年輕人 並建立平台搭',
                'link':'#'
            },
        ]
    })
}