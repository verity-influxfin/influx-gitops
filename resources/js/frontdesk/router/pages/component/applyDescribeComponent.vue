<template>
    <div class="apply-wrapper">
        <div class="apply-title">
            <h2>{{this.$props.data.title}}</h2>
            <p>{{this.$props.data.subTitle}}</p>
        </div>
        <div class="apply-contnet">
            <h2>申請所需文件</h2>
            <div class="apply-slick"  ref="apply_slick">
                <div v-for="(item,index) in this.$props.data.requiredDocuments" class="slick-item" :key="index">
                    <img :src="item.imgSrc">
                    <p>{{item.text}}</p>
                    <div v-if="item.memo" v-html="item.memo"></div>
                </div>    
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props:['data'],
    methods:{
        createSlick(){
            let $this = this;
            $($this.$refs.apply_slick).slick({
                infinite: true,
                slidesToShow: $this.$props.data.requiredDocuments.length,
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
                        breakpoint: 1023,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1
                        }
                    },
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
}
</script>

<style lang="scss">
    %text-position{
        position: absolute;
        left: 50%;
        transform: translate(-50%, 0%);
    }

    .apply-title{
        height: 220px;
        width: 100%;
        background-color: #f7f7f7f7;
        position: relative;

        h2{
            @extend %text-position;
            top: 20%;
            font-weight: bolder;
        }

        p{
            @extend %text-position;
            top: 53%;
            font-size: 25px;
            color: red;
        }
    }
    
    .apply-contnet{
        width: 100%;
        margin: 50px 0px;
        min-height: 377px;
        overflow: hidden;
        
        h2{
            text-align: center;
            font-weight: bolder;
            color: #005075;
        }
        
        .apply-slick{

            .slick-item{
                margin: 0px 10px;

                img{
                    margin: 0px auto;
                }

                p{
                    text-align: center;
                    font-size: 21px;
                    font-weight: bold;
                    margin-bottom: 0px;
                }

                div{
                    text-align: center;
                }
            }
            
            .slick-list{
                width: 90%;
                margin: 0px auto;
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

        @media (min-width: 767px) {
            .slick-custom-dots{
                display: none !important;
            }
        }
    }

    @media (max-width: 767px) {
        .apply-title{
            height: 150px;

            h2{  
                font-size: 23px;
                width: fit-content;
            }

            p{
                width: fit-content;
                font-size: 19px;
            }
        }
    }
</style>