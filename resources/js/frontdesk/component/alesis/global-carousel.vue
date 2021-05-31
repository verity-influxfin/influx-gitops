<template>
    <div class="carousel">
        <div class="selector">
            <div class="left">
                <a href="#!" @click="previous"><img class="image" src="/images/alesis-carousel-left.svg" alt=""></a>
            </div>
            <div class="lightbox">
                <slot></slot>
            </div>
            <div class="right">
                <a href="#!" @click="next"><img class="image" src="/images/alesis-carousel-right.svg" alt=""></a>
            </div>
        </div>
        <div class="indicator">
            <div class="item" v-for="i in length" :key="i" :class="{'-active': index == i-1}"></div>
        </div>
    </div>
</template>

<script>
export default {
    name : "Carousel",
    props: {
    },
    data() {
        return {
            index : 0,
            length: 0,
        }
    },
    mounted() {
        this.length = this.$el.querySelectorAll(".lightbox .set").length
        this.$el.querySelectorAll(".lightbox .set")[0].classList.add("-visible")
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

        .left,
        .right {

        }
        .left {

        }
        .right {

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
