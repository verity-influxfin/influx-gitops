import shareBtnComponent from './component/shareBtnComponent';

export default {
    template:`
        <div>
            <p>影片詳細內容...</p>
            <p>{{this.$route.params.id}}</p>
            <shareBtn :link="this.link"></shareBtn>
        </div>
    `,
    components:{
        'shareBtn':shareBtnComponent
    },
    data:()=>({
        link : window.location.toString().replace('#','%23')
    }),
    created(){
        $('title').text(`${this.$route.params.id} - inFlux普匯金融科技`);
    }
}