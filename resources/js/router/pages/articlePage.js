export default {
    template:`
        <div>
            <p>文章詳細內容...</p>
            <p>{{this.$route.params.id}}</p>
        </div>
    `,
    created(){
        console.log('articlepage');
    }
}