<template>
    <div class="join-wrapper">
        <img :src="this.$props.href">
        <h2>準備好完成夢想了嗎？</h2>
        <p>{{this.$props.subTitle}}</p>
        <div class="btn-wrapper" v-if="this.$props.isShowAll">
            <a class="btn btn-loan" href="https://event.influxfin.com/R/url?p=webbanner" target="_blank">立即借款</a>
            <a class="btn btn-invest" href="https://event.influxfin.com/r/iurl?p=webinvest" target="_blank">立即投資</a>
        </div>
        <div class="btn-wrapper" v-if="this.$props.isShowLoan">
            <a class="btn btn-single" href="https://event.influxfin.com/R/url?p=webbanner" target="_blank" @mouseover="transfrom($event)" @mouseleave="recovery($event)">立即加入</a>
        </div>
        <div class="btn-wrapper" v-if="this.$props.isShowInvest">
            <a class="btn btn-single" href="https://event.influxfin.com/r/iurl?p=webinvest" target="_blank" @mouseover="transfrom($event)" @mouseleave="recovery($event)">立即加入</a>
        </div>
    </div>
</template>

<script>
    export default {
        props:['href','isShowAll','isShowLoan','isShowInvest','subTitle'],
        data:()=>({
            timeLineMax:''
        }),
        methods:{
            transfrom($event){
                this.timeLineMax = new TimelineMax({paused: true, reversed: true});
                this.timeLineMax.to($event.target, {scale:1.2});
                this.timeLineMax.play();
            },
            recovery($event){
                this.timeLineMax.reverse();
            }
        }
    }
</script>

<style lang="scss">
    %make-center {
        position: absolute;
        left: 50%;
        transform: translate(-50%,-50%);
        color: #ffffff;
    }

    %headler-btn{
        border-radius: 30px;
        font-weight: 600;
        padding: 10px 25px;
        color:#ffffff;
        opacity: 0.8;

        &:hover{
            background-color:#ff6a00;
        }
    }

    .join-wrapper{
        overflow: hidden;
        position: relative;

        img{
            min-width: 100%;
            height: 400px;
        }

        h2{
            @extend %make-center;
            text-align: center;
            padding: 20px 0px;
            font-weight: bolder;
            width: fit-content;
            top: 30%;
        }

        p{
            @extend %make-center;
            width: fit-content;
            top: 45%;
        }

        .btn-wrapper{
            @extend %make-center;
            width: 100%;
            top: 67%;
            text-align: center;

            .btn-loan{
                @extend %headler-btn;
                background-color: #ffeb01;
                margin-right: 60px;
            }

            .btn-invest{
                @extend %headler-btn;
                background-color: #0419b2;
            }

            .btn-single{
                @extend %headler-btn;
                background-color: #ff6a00;
            }
        }

        @media (max-width: 767px) {
            img{
                height: 300px;
            }
        }
    }
</style>