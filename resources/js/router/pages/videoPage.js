export default {
    template:`
        <div>
            <p>影片詳細內容...</p>
            <p>{{this.$route.params.id}}</p>
        </div>
    `,
    created(){
        console.log('videopage');
    }
}