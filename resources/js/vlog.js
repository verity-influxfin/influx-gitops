export default {
    template:`
        <div class="video-wrapper">
            <div v-for="(item,index) in video" class="video-container" :key="index">
                <div class="video-iframe">
                    <iframe :src="item.videoLink" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="video-info">
                    <h3>{{item.title}}</h3>
                    <h4>{{item.subTitle}}</h4>
                    <p>{{item.text}}</p>
                    <a :href="item.link" class="btn btn-primary">了解更多</a>
                </div>
            </div>
        </div>
    `,
    data:()=>({
        video:[
            {
                videoLink:'https://www.youtube.com/embed/Msub13_5B-g',
                title:'【普匯小學堂】',
                subTitle:'普匯公司幫助年輕人創業！',
                text:'金融小白好困擾，這回就讓我們帶您一起來了解普匯解救他們什麼吧!!',
                link:"#"
            },{
                videoLink:'https://www.youtube.com/embed/Msub13_5B-g',
                title:'【普匯小學堂】',
                subTitle:'普匯公司幫助年輕人創業！',
                text:'金融小白好困擾，這回就讓我們帶您一起來了解普匯解救他們什麼吧!!',
                link:"#"
            },{
                videoLink:'https://www.youtube.com/embed/Msub13_5B-g',
                title:'【普匯小學堂】',
                subTitle:'普匯公司幫助年輕人創業！',
                text:'金融小白好困擾，這回就讓我們帶您一起來了解普匯解救他們什麼吧!!',
                link:"#"
            },{
                videoLink:'https://www.youtube.com/embed/Msub13_5B-g',
                title:'【普匯小學堂】',
                subTitle:'普匯公司幫助年輕人創業！',
                text:'金融小白好困擾，這回就讓我們帶您一起來了解普匯解救他們什麼吧!!',
                link:"#"
            },
        ]
    }),
    created(){
        console.log('vlog');
    }
}