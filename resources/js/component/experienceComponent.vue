<template>
    <div class="experience-wrapper">
        <h2>{{this.$props.title}}</h2>
        <div class="experience-slick" ref="experience_slick">
            <div v-for="(item,index) in userFeedback" class="slick-item" :key="index">
                <div class="slick-title">
                    <img :src="item.imageSrc">
                    <div>
                        <p>{{item.name}}</p>
                        <span>{{item.unit}}</span>
                    </div>
                </div>
                <div class="slick-content">
                    <div></div>
                    <p>{{item.memo}}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:['title'],
        data:()=>({
            userFeedback:[
                {
                    'name':'羅同學',
                    'unit':'國立台灣大學',
                    'memo':'為了睡覺',
                    'imageSrc':'./image/message_icon.png'
                },{
                    'name':'羅同學',
                    'unit':'國立台灣大學',
                    'memo':'為了睡覺',
                    'imageSrc':'./image/message_icon.png'
                },{
                    'name':'羅同學',
                    'unit':'國立台灣大學',
                    'memo':'為了睡覺',
                    'imageSrc':'./image/message_icon.png'
                },{
                    'name':'羅同學',
                    'unit':'國立台灣大學',
                    'memo':'為了睡覺',
                    'imageSrc':'./image/message_icon.png'
                },{
                    'name':'羅同學',
                    'unit':'國立台灣大學',
                    'memo':'為了睡覺',
                    'imageSrc':'./image/message_icon.png'
                },{
                    'name':'羅同學',
                    'unit':'國立台灣大學',
                    'memo':'為了睡覺',
                    'imageSrc':'./image/message_icon.png'
                }
            ]
        }),
        mounted(){
            this.createSlick();
        },
        methods:{
            createSlick(){
                 $(this.$refs.experience_slick).slick({
                infinite: true,
                slidesToShow: 4,
                slidesToScroll: 1,
                autoplay: true,
                dots:true,
                dotsClass:'slick-custom-dots',
                customPaging(slider, i) {
                    return '<i class="fas fa-circle"></i>';
                },
                prevArrow:'<i class="fas fa-chevron-left arrow-left"></i>',
                nextArrow:'<i class="fas fa-chevron-right arrow-right"></i>',
                responsive: [
                  {
                    breakpoint: 767,
                    settings: {
                      slidesToShow: 1,
                      slidesToScroll: 1
                    }
                  }
                ]
            });
            }
        }
    };
</script>

<style lang="scss">
    .experience-wrapper{
        width: 100%;
        margin: 50px 0px;
        min-height: 377px;
        overflow: hidden;
        
        h2{
            text-align: center;
            padding: 20px 0px;
            font-weight: bolder;
        }
        
        .experience-slick{

            .slick-item{
                margin: 0px 10px;

                .slick-title{
                    display: flex;
                    margin: 0px 35px;

                    img{
                        height: 105px;
                        width: 105px;
                    }

                    div{
                        width: 50%;
                        margin-left: 10px;
                        text-align: center;
                        font-size: larger;
                        font-weight: bolder;

                        p{
                            color: #002E58;
                            padding-top:10px;
                        } 
                    }
                }

                .slick-content{
                    padding-top: 15px;

                    div{
                        width: 0; 
                        height: 0; 
                        margin: 0px auto;
                        border-left: 10px solid transparent;
                        border-right: 10px solid transparent;
                        border-bottom: 13px solid #002E58;
                    }

                    p{
                        background: #002E58;
                        color: #ffffff;
                        padding: 10px;
                    }
                }
            }
            
            .slick-list{
                width: 90%;
                margin: 0px auto;
                cursor: all-scroll;
            }

            .slick-arrow{
                position: absolute;
                top: 50%;
                transform: translatey(-50%);
                font-size: 23px;
                z-index: 1;
                cursor: pointer;
            }

            .arrow-left{
                left:20px;
            }
            
            .arrow-right{
                right:20px;
            }

            .slick-custom-dots{
                position: absolute;
                padding: 15px 0px;
                text-align: center;
                color: #a0a0a0;
                display: flex;
                list-style-type: none;
                left: 50%;
                transform: translateX(-50%);
                font-size: 13px;

                li{
                    margin: 0px 3px;
                }

                .slick-active{
                    color: #000000;
                }
            }
        }
    }
</style>