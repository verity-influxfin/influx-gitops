<template>
    <div>
        <!-- 電腦：主要幻燈片容器 -->
        <div class="swiper-container swiper-container_電腦的">
            <!-- 內容包覆容器 -->
            <div class="swiper-wrapper">
                <div class="swiper-slide" v-for="(slide, key) in computer_slides" :key="key">
                    <div class="群組">
                        <div class="項目" v-for="(item, item_key) in slide" :key="item_key">
                            <alesis-human
                                :amount="item.amount"
                                :interest="item.rate"
                                :instalment="item.period_range"
                                :spend="item.spend_day"
                                :rank="item.rank"
                            >
                                <div slot="video">
                                    <iframe v-bind:src="item.video_link" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            </alesis-human>
                        </div>

                        <!-- 填空欄位 -->
                        <div class="項目" v-if="slide.length < 2"></div>
                        <!-- / 填空欄位 -->
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
        <!-- / 電腦：主要幻燈片容器 -->

        <!-- 手機：主要幻燈片容器 -->
        <div class="swiper-container swiper-container_手機的">
            <!-- 內容包覆容器 -->
            <div class="swiper-wrapper">
                <div class="swiper-slide" v-for="(slide, key) in mobile_slides" :key="key">
                    <div class="群組">
                        <div class="項目" v-for="(item, item_key) in slide" :key="item_key">
                            <alesis-human
                                :amount="item.amount"
                                :interest="item.rate"
                                :instalment="item.period_range"
                                :spend="item.spend_day"
                                :rank="item.rank"
                            >
                                <div slot="video">
                                    <iframe v-bind:src="item.video_link" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
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
            <div class="swiper-button-prev hidden-desktop"></div>
            <div class="swiper-button-next hidden-desktop"></div>
            <!-- / 導覽按鈕 -->
        </div>
        <!-- / 手機：主要幻燈片容器 -->
    </div>
</template>

<style lang="scss" scoped>
@import "./alesis";

.swiper-container.swiper-container_電腦的 {
    display: block;
    @include rwd {
        display: none;
    }
}

.swiper-container.swiper-container_手機的 {
    display: none;
    @include rwd {
        display: block;
    }
}

.swiper-container {
    max-width     : 1170px;
    padding-bottom: 3rem;
    padding-top   : 1.5rem;
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
import AlesisHuman          from "./AlesisHuman";
import {alesisIndexHumans,
        alesisCollegeHumans,
        alesisWorkHumans,
        alesisBorrowHumans} from "./../../pages/api";
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
        computer_slides: [],
        mobile_slides: []
    }),
    mounted() {
        this.getExperiences();
    },
    methods: {
        process(v) {
            let computer_chunk = 2;
            let mobile_chunk = 1;
            // 只留下有影片連結的項目。
            v = v.filter(x => x.video_link);
            // 電腦版本專用的幻燈片。
            for (let i=0; i< v.length; i+=computer_chunk) {
                this.computer_slides.push(v.slice(i, i+computer_chunk))
            }
            // 手機版本專用的幻燈片。
            for (let i=0; i< v.length; i+=mobile_chunk) {
                this.mobile_slides.push(v.slice(i, i+mobile_chunk))
            }
            // 替 SwiperCore 載入 Navigation 導覽模組。
            SwiperCore.use([Navigation]);
            // 初始化這個案例分享容器幻燈片。
             new Swiper('.swiper-container.swiper-container_電腦的', {
                pagination: {
                    el: '.swiper-pagination',
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
             new Swiper('.swiper-container.swiper-container_手機的', {
                pagination: {
                    el: '.swiper-pagination',
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
        },
        //
        getExperiences() {
            switch (this.type) {
                case "borrow":
                    alesisBorrowHumans().then(this.process)
                    break
                case "college":
                    alesisCollegeHumans().then(this.process)
                    break
                case "index":
                    alesisIndexHumans().then(this.process)
                    break
                case "work":
                    alesisWorkHumans().then(this.process)
                    break
            }
        },
    }
}
</script>

