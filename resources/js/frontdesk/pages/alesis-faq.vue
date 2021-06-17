<template>
    <div class="index-wrapper">
        <div class="alesis-slate">
            <div class="wrapper">
                <div class="header">有任何疑問嗎？這裡為您解答</div>
                <div class="helper">請輸入關鍵字</div>
                <div class="search">
                    <img class="icon" src="/images/alesis-search-icon.svg" alt="">
                    <input type="text" class="input" placeholder="全站搜尋...">
                </div>
                <div class="categories">
                    <a href="#!" class="item -active">全部</a>
                    <a href="#!" class="item">會員訊息</a>
                    <a href="#!" class="item">借款</a>
                    <a href="#!" class="item">投資</a>
                    <a href="#!" class="item">還款</a>
                </div>
            </div>
        </div>
        <div class="alesis-questions">
            <index-section header="會員訊息">
                <div class="items">
                    <div class="item">
                        <div class="header">
                            <div class="text">Q1：我提供的訊息安全嗎？</div>
                            <img class="arrow" src="/images/alesis-chevron-down.svg" alt="">
                        </div>
                        <div class="content">
                            A1：所有提供資訊傳輸過程均由HTTPS方式利用SSL/TLS加密封包，<br>　　將所有資訊儲存於亞馬遜AWS雲端伺服器，資料傳輸穩定且安全。
                        </div>
                    </div>
                    <div class="item">
                        <div class="header">
                            <div class="text">Q1：我提供的訊息安全嗎？</div>
                            <img class="arrow" src="/images/alesis-chevron-down.svg" alt="">
                        </div>
                        <div class="content">
                            A1：所有提供資訊傳輸過程均由HTTPS方式利用SSL/TLS加密封包，<br>　　將所有資訊儲存於亞馬遜AWS雲端伺服器，資料傳輸穩定且安全。
                        </div>
                    </div>
                </div>
            </index-section>
        </div>
    </div>
</template>

<script>
// Alesis components
import indexCounter                    from "../component/alesis/index/counter";
import indexSection                    from "../component/alesis/index/section";
import indexBullet                     from "../component/alesis/index/bullet";
import indexMoon                       from "../component/alesis/index/moon";
import indexPlan                       from "../component/alesis/index/plan";
import indexHuman                      from "../component/alesis/index/human";
import indexButton                     from "../component/alesis/index/button";
import indexShanghai                   from "../component/alesis/index/shanghai";
import globalCarousel                  from "../component/alesis/global-carousel";
import AlesisLoanHeader                from "../component/alesis/loan-header";
import AlesisApplicationRecommendation from "../component/alesis/application-recommendation";

export default {
    components: {
        indexCounter,
        indexSection,
        indexBullet,
        indexMoon,
        indexPlan,
        indexHuman,
        indexButton,
        indexShanghai,
        globalCarousel,
        AlesisApplicationRecommendation,
        AlesisLoanHeader
    },
    data: () => ({

    }),
    created() {
    },
    mounted() {
    },
    methods: {
    },
};
</script>

<style lang="scss" scoped>
@import "../component/alesis/alesis";

.alesis-slate {
    background-image: url(/images/alesis-faq-bg.svg);
    background-size : cover;

    .wrapper {
        padding       : 5rem 0;
        display       : flex;
        flex-direction: column;
        gap           : 1rem;
        color         : #FFF;
        align-items   : center;

        @include rwd {
            padding: 4rem 2rem;
            gap    : .5rem;
        }

        .header {
            font-size  : 1.4rem;
            text-shadow: 0 0 7px #000;

            @include rwd {
                font-size  : 1.4rem;
                line-height: 1;
            }
        }

        .helper {
            font-size  : 1.8rem;
            color      : #F2E627;
            text-shadow: 0 0 7px #000;

            @include rwd {
                font-size: 1.2rem;
            }
        }

        .search {
            position : relative;
            width    : 50%;
            max-width: 51rem;

            @include rwd {
                width: calc(100%);
            }

            .icon {
                width    : 22px;
                position : absolute;
                left     : 1.5rem;
                top      : 50%;
                transform: translateY(-50%);

                @include rwd {
                    width: 16px;
                    left : .7rem;
                }

            }

            .input {
                appearance   : none;
                background   : transparent;
                color        : #FFF;
                border       : 1px solid #FFF;
                border-radius: 100rem;
                line-height  : 1.3;
                font-size    : 1.1rem;
                padding      : .7rem 2rem .6rem;
                padding-left : 3.7rem;
                width        : 100%;

                @include rwd {
                    padding  : 0.3rem 2rem 0.25rem;
                    font-size: .9rem;
                }

                &::placeholder {
                    color: #FFF;
                }
            }
        }

        .categories {
            display   : flex;
            gap       : 1.2rem;
            margin-top: 1rem;

            @include rwd {
                flex-wrap      : wrap;
                justify-content: center;
            }

            .item {
                display      : inline-block;
                background   : #FFF;
                color        : #18599E;
                font-weight  : bold;
                line-height  : 1;
                font-size    : 1.1rem;
                padding      : 0.6rem 1.2rem 0.4rem;
                border-radius: 0.4rem;
                min-width    : 5.5rem;
                text-align   : center;

                @include rwd {
                    font-size: 1rem;
                }

                &.-active {
                    background: #1E2973;
                    color     : #FFF;
                    border    : 1px solid #FFF;
                }
            }
        }
    }
}

.alesis-questions {
    margin-top: 8rem;

    .items {
        max-width     : 690px;
        display       : flex;
        margin        : 0 auto;
        flex-direction: column;
        gap           : 1.5rem;

        .item {
            display       : flex;
            flex-direction: column;
            gap           : 1.5rem;

            .header {
                display      : flex;
                border       : 1px solid #036EB7;
                border-radius: .4rem;
                padding      : .5rem 1.5rem;
                color        : #036EB7;
                font-size    : 1.2rem;

                @include rwd {
                    font-size: .9rem;
                }

                .text {
                    flex: 1;
                }

                .arrow {
                    width    : 20px;
                    transform: rotate(180deg);
                }
            }

            .content {
                display      : none;
                border       : 1px solid #5D5555;
                border-radius: .4rem;
                padding      : 0.5rem 1.5rem 1rem;
                color        : #5D5555;
                font-size    : 1.2rem;
            }

            &.-active {
                .header {
                    .arrow {
                        transform: none;
                    }
                }
                .content {
                    display: flex;
                }
            }
        }
    }
}
</style>
