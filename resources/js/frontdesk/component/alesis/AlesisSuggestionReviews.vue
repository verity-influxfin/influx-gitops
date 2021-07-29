<template>
    <!-- 主要幻燈片容器 -->
    <div class="swiper-container">
        <!-- 內容包覆容器 -->
        <div class="swiper-wrapper">
             <div class="swiper-slide">
                <div class="群組">
                    <div class="項目">
                        <alesis-human :image="image">
                            <div slot="video">
                                <iframe src="https://www.youtube.com/embed/4RqbVmH6aHU" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        </alesis-human>
                    </div>
                    <div class="項目">
                        <alesis-human :image="image">
                            <div slot="video">
                                <iframe src="https://www.youtube.com/embed/we16JV8Hc1o" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        </alesis-human>
                    </div>
                </div>
            </div>

            <div class="swiper-slide">
                <div class="群組">
                    <div class="項目">
                        <alesis-human :image="image">
                            <div slot="video">
                                <iframe src="https://www.youtube.com/embed/tsd-YTxzRy" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        </alesis-human>
                    </div>
                    <div class="項目">
                        <alesis-human :image="image">
                            <div slot="video">
                                <iframe src="https://www.youtube.com/embed/THjekE5p2aw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        </alesis-human>
                    </div>
                </div>
            </div>
        </div>
        <!-- / 內容包覆容器 -->

        <!-- 分頁導覽指示器 -->
        <div class="swiper-pagination"></div>
        <!-- / 分頁導覽指示器 -->

        <!-- 導覽按鈕 -->
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
        <!-- / 導覽按鈕 -->
    </div>
    <!-- / 主要幻燈片容器 -->
</template>

<style lang="scss" scoped>
@import "./alesis";

.swiper-container {
    max-width     : 1170px;
    padding-bottom: 3rem;
    padding-top   : 1.5rem;
}

.swiper-container .swiper-slide {
}

.swiper-container .swiper-slide .群組 {
    max-width            : 90%;
    display              : grid;
    grid-template-columns: repeat(2, 1fr);
    gap                  : 3rem;
    margin               : 0 auto;

    @include rwd {
        grid-template-columns: 1fr !important;
        gap                  : 0 !important;
    }
}

.swiper-container .swiper-slide .群組 .項目 {
    max-width: 490px;

    @include rwd {
        &:not(:first-child) {
            display: none;
        }
    }
}

// 導覽按鈕
.swiper-container .swiper-button-next,
.swiper-container .swiper-button-prev {
    opacity  : 0.6;
    transform: scale(0.7);
}

.swiper-container .swiper-button-next.swiper-button-disabled,
.swiper-container .swiper-button-prev.swiper-button-disabled {
    display: none;
}
</style>

<script>
import AlesisHuman from "./AlesisHuman";

import InvestData from "./../../data/reviews_invest";

import 'swiper/swiper.scss';
import "swiper/components/navigation/navigation.min.css"
import SwiperCore, {
  Navigation
} from 'swiper/core';

export default {
    components: {
        AlesisHuman,
    },
    props: {
        type: {
            default: "index",
        }
    },
    data() {
        return {
            image  : "",
            invests: InvestData
        }
    },
    mounted() {
        switch (this.type) {
            case "work":
                this.image = "/images/alesis-human-work-symbol.svg";
                break
            case "student":
            default:
                this.image = "/images/alesis-human-student-symbol.svg";
                break
        }


        // 替 SwiperCore 載入 Navigation 導覽模組。
        SwiperCore.use([Navigation]);
        // 初始化這個案例分享容器幻燈片。
        const swiper = new Swiper('.swiper-container', {
            pagination: {
                el: '.swiper-pagination',
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    }
}
</script>

