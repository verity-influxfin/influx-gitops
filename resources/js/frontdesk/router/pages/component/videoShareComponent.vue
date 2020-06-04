<template>
    <div class="share-wrapper">
        <h2 v-html="this.$props.title"></h2>
        <div class="share-content" ref="share_content">
            <div v-for="(item,index) in this.$props.data" class="content-row" :key="index">
                <iframe :src="item.video_link" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <div>
                    <p>{{item.post_title}}</p>
                    <br>
                    <router-link :to="item.link" class="btn btn-warning">閱讀更多</router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:['title','data']
    };
</script>

<style lang="scss">
    .share-wrapper{
        overflow: hidden;

        h2{
            text-align: center;
            padding: 20px 0px;
            font-weight: bolder;
        }

        .share-content{
            display: flex;
            width: 100%;

            .content-row{
                width: 25%;
                margin: 10px;

                iframe{
                    width: 100%;
                    height: 196px;
                }

                div{
                    padding: 15px;
                    text-align: justify;

                    a{
                        color:#ffffff;
                        margin-top: 10px;
                    }

                    p{
                        margin-bottom: 0px;
                        font-weight: bold;
                    }

                    span{
                        color: #a7a7a7;
                    }
                }
            }
        }

        @media (max-width:1023px) {
            h2{
                font-size: 26px;
            }

            .share-content{
                display: block;

                .content-row{
                    width: 95%;

                    iframe{
                        height: 400px;
                    }
                }
            }
        }
    }
</style>