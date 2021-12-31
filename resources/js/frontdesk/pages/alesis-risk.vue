<template>
    <div>
        <!-- 頂部板岩 -->
        <div class="頂部板岩">
            <div class="背景">
                <img class="圖片" src="/images/sshot-1848.png">
            </div>
            <div class="遮罩">
                <img class="標題" style="height: 1.2em;padding-bottom: 8px;" src="/images/風險報告書標題.svg">
            </div>
        </div>
        <!-- / 頂部板岩 -->
        <!-- 報告書 -->
        <div class="報告書">
            <alesis-header>
                <div class="標題">違約率報告書</div>
            </alesis-header>
            <alesis-space size="medium"></alesis-space>
            <alesis-section>
                <alesis-space size="medium"></alesis-space>
                <div class="包裹容器">
                    <img :src="img_path" class="圖片">
                    <div class="swiper">
                    <!-- Additional required wrapper -->
                        <div class="swiper-wrapper 連結">
                            <!-- Slides -->
                            <div class="swiper-slide" v-for="(item,index) in renderList" :key="index">
                                <a @click="current_risk_month = x.month" href="javascript:;" class="項目" v-for="x in item" :key="x.month">
                                    <alesis-button>2021年{{String(x.month).padStart(2, '0')}}月</alesis-button>
                                </a>
                            </div>
                        </div>
                        <!-- If we need navigation buttons -->
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
                    <a href="/invest" class="行動">
                        <alesis-button>立即投資</alesis-button>
                    </a>
                </div>
                <alesis-space size="medium"></alesis-space>
            </alesis-section>
        </div>
        <!-- / 報告書 -->
    </div>
</template>

<script>
// Alesis 元件
import AlesisAppRecommendation from "../component/alesis/AlesisAppRecommendation";
import AlesisBullet            from "../component/alesis/AlesisBullet";
import AlesisButton            from "../component/alesis/AlesisButton";
import AlesisCounter           from "../component/alesis/AlesisCounter";
import AlesisHeader            from "../component/alesis/AlesisHeader";
import AlesisHorizontalRoadmap from "../component/alesis/AlesisHorizontalRoadmap";
import AlesisHuman             from "../component/alesis/AlesisHuman";
import AlesisLoanHeader        from "../component/alesis/AlesisLoanHeader";
import AlesisMoon              from "../component/alesis/AlesisMoon";
import AlesisPlan              from "../component/alesis/AlesisPlan";
import AlesisProject           from "../component/alesis/AlesisProject";
import AlesisSection           from "../component/alesis/AlesisSection";
import AlesisSuggestionReviews from "../component/alesis/AlesisSuggestionReviews";
import AlesisSymcard           from "../component/alesis/AlesisSymcard";
import AlesisTaiwanMap         from "../component/alesis/AlesisTaiwanMap";
import AlesisVerticalRoadmap   from "../component/alesis/AlesisVerticalRoadmap";
import AlesisSpace             from "../component/alesis/AlesisSpace";
import 'swiper/swiper.scss';
import "swiper/components/navigation/navigation.min.css"
import SwiperCore, {
  Navigation
} from 'swiper/core';

export default {
    components: {
        AlesisAppRecommendation,
        AlesisBullet,
        AlesisButton,
        AlesisCounter,
        AlesisHeader,
        AlesisHorizontalRoadmap,
        AlesisHuman,
        AlesisLoanHeader,
        AlesisMoon,
        AlesisPlan,
        AlesisProject,
        AlesisSection,
        AlesisSuggestionReviews,
        AlesisSymcard,
        AlesisTaiwanMap,
        AlesisVerticalRoadmap,
        AlesisSpace,
    },
    data: () => {
        return {
            current_risk_month : 11,
            report_list: [
                {
                    month:7,
                    image:'/images/risk07-report.jpg',
                },
                {
                    month:8,
                    image:'/images/risk08-report.jpg',
                },
                {
                    month:9,
                    image:'/images/risk09-report.jpg',
                },
                {
                    month:10,
                    image:require('../asset/images/risk/risk10-report.jpg'),
                },
                {
                    month:11,
                    image:require('../asset/images/risk/risk11-report.jpg'),
                },
            ]
        }
    },
    computed: {
        img_path() {
            return this.report_list.find(({month})=>this.current_risk_month===month).image;
        },
        renderList(){
            const { report_list } = this
            report_list.reverse()
            const ans = []
            for (let index = 0; index < report_list.length; index+=3) {
                ans.push(report_list.slice(index,index+3))
            }
            return ans
        }
    },
    created() {
        console.log(this.renderList)
        $("title").text(`風險報告書 - inFlux普匯金融科技`);
    },
    mounted () {
        SwiperCore.use([Navigation]);
            // 初始化這個案例分享容器幻燈片。
        new Swiper('.swiper', {
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    },
};
</script>

<style lang="scss" scoped>
@import "../component/alesis/alesis";

/**
 * 頂部板岩
 */

.頂部板岩 {
    position: relative;
    height  : 33rem;

    @include rwd {
        height: clamp(33rem, 155vw, 155vw);
    }
}

.頂部板岩 .背景 {
    position: absolute;
    top     : 0;
    left    : 31rem;
    right   : 0;
    bottom  : 0;

    @include rwd {
        left      : 0;
        margin-top: 50vw;
    }
}

.頂部板岩 .背景 .圖片 {
    height    : 100%;
    width     : 100%;
    object-fit: cover;
}

.頂部板岩 .遮罩 {
    background-image : url(/images/alesis-shell-mask.svg);
    position         : relative;
    z-index          : 1;
    display          : flex;
    align-items      : center;
    padding-left     : 11rem;
    background-size  : contain;
    background-repeat: no-repeat;
    min-width        : 1485px;
    height           : 100%;

    @include rwd {
        background-image: url(/images/alesis-wave-bg-rotated.svg);
        padding-left    : 0;
        background-size : 100vw;
        min-width       : initial;
        align-items     : initial;
        padding-top     : 21vw;
        justify-content : center;
    }
}

.頂部板岩 .遮罩 .標題 {
    padding-bottom         : 0.2rem;
    border-bottom          : 1px solid #FFF;
    display                : inline-block;
    font-weight            : bolder;
    background-image       : linear-gradient(to right, #e7e7f4 0%, #4fb7ec 50%, #c4c4fc 75%);
    background-clip        : text;
    width                  : fit-content;
    color                  : rgba(255, 255, 255, 0);
    font-size              : 2rem;
    line-height            : 1.2;
    height                 : min-content;
}

/**
 * 報告書
 */

.報告書 {
    position: relative;
    margin-top: 8rem;
}

.報告書 .包裹容器 {
    text-align: center;
}

.報告書 .包裹容器 .圖片 {
    width: 30vw;

    @include rwd {
        width: 70vw;
    }
}
.swiper{
    width: 800px;
    @include rwd{
        width: 95%;
    }
    .swiper-slide{
        gap: 15px;
        display: flex;
        justify-content: center;
        @include rwd{
            flex-direction: column;
        }
    }
}

.報告書 .包裹容器 .連結 {
    margin-top     : 1.5rem;
    margin-bottom  : 1.5rem;

}

.報告書 .包裹容器 .連結 .項目 {
    // padding: 0.9rem 2.5rem;

    --font-size: 1.1rem;
    --x-padding: 2.5rem;
    --y-padding: 0.9rem;
    white-space: nowrap;

    @include rwd {
        --x-padding: 1.4rem;
        --y-padding: 0.7rem;


    }
}

.報告書 .包裹容器 .行動 {
    --x-padding: 2.5rem;
    --y-padding: 0.9rem;

    @include rwd {
        --x-padding: 1.7rem;
        --y-padding: 0.7rem;

        white-space: nowrap;
    }
}
</style>
