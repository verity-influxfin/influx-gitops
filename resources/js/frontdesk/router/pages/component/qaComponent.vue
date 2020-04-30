<template>
    <div class="qa-wrapper">
        <h2>{{this.$props.title}}</h2>
        <div class="qa-accordion" :id="'qa_content' + (category ? category:'' )">
            <div class="card" v-for="(item,index) in this.$props.data" :key="index">
                <div class="card-header" data-toggle="collapse" :data-target="'#collapse' + (category ? category:'' ) + index" aria-expanded="true">
                    <span class="accicon"><i class="fas fa-angle-down rotate-icon"></i></span>
                    <span class="title">Q{{index+1}}：{{item.title}}</span>
                </div>
                <div :id="'collapse' + (category ? category:'' ) + index" class="collapse" :data-parent="'#qa_content' + (category ? category:'' )">
                    <div class="card-body">
                        <div>A{{index+1}}：<br><p v-html="item.content"></p></div>
                        <img v-for="(src,index) in item.imgSrc" :src="src" :key="index" :width="1/item.imgSrc.length*75+'%'">
                    </div>
                </div>
            </div>
        </div>
        <div v-if="!this.$props.hideLink" class="qa-link">
            <router-link class="btn btn-primary" to="/qa">更多詳細問題請參考Q&A</router-link>
        </div>
    </div>
</template>

<script>
export default {
    props:['filter','data','title','hideLink','category']
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

        @media (max-width: 1023px) {
            .qa-accordion{
                width: 90%;
            }
        }
        
        @media (max-width: 767px) {
            .qa-accordion{
                width: 100%;
            }
        }
    }

</style>