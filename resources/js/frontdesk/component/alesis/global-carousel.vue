<template>
    <div class="carousel">
        <div class="selector">
            <div class="left" :class="{'-hidden': index == 0 }">
                <div @click="previous"><img class="image" src="/images/alesis-carousel-left.svg" alt=""></div>
            </div>
            <div class="lightbox">
                <slot></slot>
            </div>
            <div class="right" :class="{'-hidden': index == length - 1 }">
                <div @click="next"><img class="image" src="/images/alesis-carousel-right.svg" alt=""></div>
            </div>
        </div>
        <div class="indicator" v-if="indicator === true">
            <div class="item" v-for="i in length" :key="i" :class="{'-active': index == i-1}"></div>
        </div>
        <div class="dsds">sadas</div>


        <!-- Slider main container -->
        <div class="swiper-container">
        <!-- Additional required wrapper -->
        <div class="swiper-wrapper">
            <!-- Slides -->
            <slot></slot>
        </div>
        <!-- If we need pagination -->
        <div class="swiper-pagination"></div>

        <!-- If we need navigation buttons -->
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>

        <!-- If we need scrollbar -->
        <div class="swiper-scrollbar"></div>
        </div>

    </div>
</template>

<script>
import 'swiper/swiper.scss';
import "swiper/components/navigation/navigation.min.css"
import SwiperCore, {
  Navigation
} from 'swiper/core';



export default {
    name : "Carousel",
    props: {
        indicator: {
            default: true,
        },
    },
    data() {
        return {
            index : 0,
            length: 0,
        }
    },
    components: {
    },
    mounted() {
        this.length = this.$el.querySelectorAll(".lightbox .set").length
        this.$el.querySelectorAll(".lightbox .set")[0].classList.add("-visible")
        // install Swiper modules
        SwiperCore.use([Navigation]);
        const swiper = new Swiper('.swiper-container', {
            // Optional parameters
            loop: true,

            // If we need pagination
            pagination: {
                el: '.swiper-pagination',
            },

            // Navigation arrows
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },

            // And if we need scrollbar
            scrollbar: {
                el: '.swiper-scrollbar',
            },
            });
    },
    methods: {
        next() {
            if (this.index + 1 > this.length - 1) {
                this.index = 0
            } else {
                this.index++
            }
        },
        previous() {
            if (this.index - 1 < 0) {
                this.index = this.length - 1
            } else {
                this.index--
            }
        }
    },
    watch: {
        index(newVal) {
            this.$el.querySelectorAll(".lightbox .set").forEach((v) => {
                v.classList.remove("-visible")
            })
            this.$el.querySelectorAll(".lightbox .set")[newVal].classList.add("-visible")
        }
    }
};
</script>

<style lang="scss" scoped>
@import "./alesis";

.swiper-container {
    width: 370px;
    height: 130px;
}

.carousel {
    display        : flex;
    flex-direction : column;
    align-items    : center;
    justify-content: center;

    .selector {
        display              : grid;
        justify-items        : center;
        align-items          : center;
        grid-template-columns: min-content 1fr min-content;
        gap                  : 3rem;

        > .left,
        > .right {
            cursor  : pointer;
            position: relative;
            height  : 100%;

            &.-hidden {
                visibility: hidden;
            }
        }

        > .left {
            > div {
                position       : absolute;
                top            : 0;
                bottom         : 0;
                max-height     : 34rem;
                display        : flex;
                align-items    : center;
                justify-content: center;
            }
        }

        > .right {
            > div {
                position       : absolute;
                top            : 0;
                bottom         : 0;
                max-height     : 34rem;
                display        : flex;
                align-items    : center;
                justify-content: center;
            }
        }

        .lightbox {
            flex: 1;

            .set {
                display              : none;
                grid-template-columns: repeat(2, 1fr);
                gap                  : 3rem;
                margin               : 0 auto;

                &.-visible {
                    display: grid;
                }

                .item {

                }
            }
        }
    }

    .indicator {
        display        : flex;
        align-items    : center;
        justify-content: center;
        margin-top     : 2rem;

        @include rwd {
            display: none;
        }

        .item {
            width        : 8px;
            height       : 8px;
            background   : #549cce;
            border-radius: 100rem;
            line-height  : 0;

            &.-active {
                width     : 16px;
                background: #036eb7;
            }

            &:not(:last-child) {
                margin-right: .5rem;
            }
        }
    }
}
</style>
