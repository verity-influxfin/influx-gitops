<template>
    <!-- 主要幻燈片容器 -->
    <div class="swiper-container">
        <!-- 內容包覆容器 -->
        <div class="swiper-wrapper">
             <div class="swiper-slide" v-for="slide in slides">
                <div class="群組">
                    <div class="項目" v-for="item in slide">
                        <alesis-human
                            :image="image"
                            :amount="item.amount"
                            :interest="item.rate"
                            :instalment="item.period_range"
                            :spend="item.spend_day"
                        >
                            <div slot="video">
                                <iframe v-bind:src="item.video_link" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        </alesis-human>
                    </div>
                    <div class="項目" v-if="slide.length < 2">
                    </div>
                </div>
            </div>
        </div>
        <!-- / 內容包覆容器 -->

        <!-- 分頁導覽指示器 -->
        <div class="swiper-pagination"></div>
        <!-- / 分頁導覽指示器 -->

        <!-- 導覽按鈕 -->
        <div class="swiper-button-prev hidden-desktop"></div>
        <div class="swiper-button-next hidden-desktop"></div>
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
        max-width            : 100%;
        grid-template-columns: repeat(2, 100%);
        gap                  : 0 !important;
    }
}

.swiper-container .swiper-slide .群組 .項目 {
    max-width: 490px;

    @include rwd {
        max-width: 100%;

        &:not(:first-child) {
            display: none;
        }
    }
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
        },
        category: {
            default: 'loan'
        }
    },
    data: () => ({
        image: '',
        slides: []
    }),
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
        this.getExperiences();
    },
    methods: {
        getExperiences() {
            var self = this;

            let data = new FormData();
            // 借款端|投資端
            // let category = this.category == 'loan' ? 'loan' : 'invest';
            let category = 'loan';
            data.append('category', category)

            axios({
                url: '/getExperiencesData',
                method: 'post',
                data: data,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Accept': 'application/json',
                }
            }).then((resp) => {
                let chunk = 2;

                let data = resp.data.filter(x => x.video_link);

                for (let i=0; i< data.length; i+=chunk) {
                    self.slides.push(data.slice(i, i+chunk))
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

            })
        },
    }
}
</script>

