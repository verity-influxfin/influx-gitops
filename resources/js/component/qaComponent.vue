<template>
    <div class="qa-wrapper">
        <h2>常見問題</h2>
        <div class="qa-accordion" id="qa_content">
            <div class="card" v-for="(item,index) in this.qaList" :key="index">
                <div class="card-header" data-toggle="collapse" :data-target="'#collapse'+index" aria-expanded="true">
                    <span class="accicon"><i class="fas fa-angle-down rotate-icon"></i></span>
                    <span class="title">Q{{index+1}}：{{item.title}}</span>
                </div>
                <div :id="'collapse'+index" class="collapse" data-parent="#qa_content">
                    <div class="card-body">
                        <div>A{{index+1}}：<br>{{item.content}}</div>
                        <img v-for="(src,index) in item.imgSrc" :src="src" :key="index">
                    </div>
                </div>
            </div>
        </div>
        <div class="qa-link">
            <router-link class="btn btn-primary" to="/qa">更多詳細問題請參考Q&A</router-link>
        </div>
    </div>
</template>

<script>
export default {
    props:['filter'],
    data:()=>({
        qaList:[]
    }),
    created(){
        this.getQaList();
    },
    mounted(){
        
    },
    methods:{
        getQaList(){
            this.qaList=[
                {
                    title:'誰可以使用學生貸？',
                    content:'年滿20歲之大學在學生。',
                    imgSrc:['./image/college_qa1.png']
                },{
                    title:'持證自拍不清晰？',
                    content:'1. 持證自拍時請留意證件與人臉都需清晰可辨識。\n\r2. 證件不可擋到臉部，手也不可擋到證件。\n\r3. 請盡量於背景單純明亮的拍攝，若有配戴眼鏡建議將眼鏡拿下。',
                    imgSrc:['./image/college_qa2.png']
                },{
                    title:'SIP是什麼？',
                    content:'SIP為登入學校線上系統使用的資訊。\n\r如您的校方信箱無法順利收信，SIP資訊能協助您快速完成學生認證，請填寫正確資訊。',
                    imgSrc:['./image/college_qa3-1.png','./image/college_qa3-2.png']
                },{
                    title:'學生證遺失怎麼辦？',
                    content:'可使用校方開立並有校方戳章的在學證明代替。',
                    imgSrc:['./image/college_qa4.png']
                },{
                    title:'非中華民國國民或境外學校可以申請嗎？',
                    content:'目前尚未開放非中華民國國民及境外學校學生申請。',
                    imgSrc:[]
                },
            ];
        }
    }
}
</script>

<style lang="scss">
    .qa-wrapper{
        background-color: #f7f7f7;
        padding: 2%;

        h2{
            font-weight: bolder;
            text-align: center;
        }

        .qa-accordion{
            padding-bottom: 10px;
            width: 50%;
            margin: 0px auto;

            .card{
                border: 1px solid #ddd;

                .card-header{
                    cursor: pointer;
                    border-bottom: none;
                    color:#777777;

                    .title {
                        font-size: 17px;
                        margin-right: 10px;
                        font-weight: bolder;
                    }

                    .accicon {
                        float: right;
                        font-size: 20px;  
                        width: 1.2em;
                    }

                    &:not(.collapsed){
                        .rotate-icon {
                            transform: rotate(180deg);
                        }
                    }

                    &:hover{
                        color:#000000
                    }
                }

                .card-body{
                    border-top: 1px solid #ddd;

                    img{
                        max-height: 400px;
                    }
                }
            }

        }

        .qa-link{
            text-align: center;

            a{
                font-size: 23px;
            }
        }
        
        @media (max-width: 767px) {
            .qa-accordion{
                width: 100%;
            }
        }
    }

</style>