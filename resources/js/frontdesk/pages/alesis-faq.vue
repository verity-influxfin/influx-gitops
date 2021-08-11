<template>
    <div>
        <!-- 頂部板岩 -->
        <div class="頂部板岩">
            <div class="包裹容器">
                <div class="標題">有任何疑問嗎？這裡為您解答</div>
                <div class="說明">請輸入關鍵字</div>
                <div class="搜尋">
                    <img class="圖示" src="/images/alesis-search-icon.svg">
                    <input class="輸入欄位" type="text" placeholder="全站搜尋..." name="global-search" autocomplete="off" />
                </div>
                <div class="分類">
                    <a @click="category('all')" class="項目" :class="{'項目_啟用的': current_category === 'all'}">全部</a>
                    <a @click="category('member')" class="項目" :class="{'項目_啟用的': current_category === 'member'}">會員訊息</a>
                    <a @click="category('borrow')" class="項目" :class="{'項目_啟用的': current_category === 'borrow'}">借款</a>
                    <a @click="category('invest')" class="項目" :class="{'項目_啟用的': current_category === 'invest'}">投資</a>
                    <a @click="category('default')" class="項目" :class="{'項目_啟用的': current_category === 'default'}">還款</a>
                </div>
            </div>
        </div>
        <!-- / 頂部板岩 -->

        <!-- 問題集 -->
        <div class="問題集">
            <alesis-header>
                <div class="標題">
                    <span v-if="current_category === 'all'">全部</span>
                    <span v-if="current_category === 'member'">會員訊息</span>
                    <span v-if="current_category === 'borrow'">借款</span>
                    <span v-if="current_category === 'invest'">投資</span>
                    <span v-if="current_category === 'default'">還款</span>
                </div>
            </alesis-header>
            <alesis-section>
                <alesis-space size="medium"></alesis-space>
                <div class="群組">
                    <div class="項目" :class="{'項目_啟用的': item.active}" v-for="(item, index) in current_questions" :key="index" @click="toggle(item)">
                        <div class="標題">
                            <div class="文字">{{item.title}}</div>
                            <img class="箭頭" src="/images/alesis-chevron-down.svg">
                        </div>
                        <div class="內容" v-html="item.content"></div>
                    </div>
                </div>
                <alesis-space size="medium"></alesis-space>
            </alesis-section>
        </div>
        <!-- / 問題集 -->
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
import AlesisShanghai          from "../component/alesis/AlesisShanghai";
import AlesisSuggestionReviews from "../component/alesis/AlesisSuggestionReviews";
import AlesisSymcard           from "../component/alesis/AlesisSymcard";
import AlesisTaiwanMap         from "../component/alesis/AlesisTaiwanMap";
import AlesisVerticalRoadmap   from "../component/alesis/AlesisVerticalRoadmap";
import AlesisSpace             from "../component/alesis/AlesisSpace";
// 遠端資料
import FAQData from "../data/faq_data_latest"


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
        AlesisShanghai,
        AlesisSuggestionReviews,
        AlesisSymcard,
        AlesisTaiwanMap,
        AlesisVerticalRoadmap,
        AlesisSpace,
    },
    data: () => {
        return {
            questions        : FAQData.QA,
            current_questions: [],
            current_category : "all",
        }
    },
    mounted() {
        this.questions.forEach((_, k) => {
            this.$set(this.questions[k], 'active', false);
        })
        this.category('all')
    },
    methods: {
        // toggle 會切換一個問答的展開狀態。
        toggle(item) {
            item.active = !item.active
        },

        // category 會切換目前的問題分類。
        category(v) {
            this.current_category = v;
            this.current_questions = this.questions.filter((j) => {
                return v !== 'all' ? j.type === v : true;
            })
        }
    },
};
</script>

<style lang="scss" scoped>
@import "../component/alesis/alesis";

/**
 * 頂部板岩
 */

.頂部板岩 {
    background-image: url(/images/alesis-faq-bg.svg);
    background-size : cover;
}

.頂部板岩 .包裹容器 {
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
}

.頂部板岩 .包裹容器 .標題 {
    font-size  : 1.4rem;
    text-shadow: 0 0 7px #000;

    @include rwd {
        font-size  : 1.4rem;
        line-height: 1;
    }
}

.頂部板岩 .包裹容器 .說明 {
    font-size  : 1.8rem;
    color      : #F2E627;
    text-shadow: 0 0 7px #000;

    @include rwd {
        font-size: 1.2rem;
    }
}

.頂部板岩 .包裹容器 .搜尋 {
    position : relative;
    width    : 50%;
    max-width: 51rem;

    @include rwd {
        width: calc(100%);
    }
}

.頂部板岩 .包裹容器 .搜尋 .圖示 {
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

.頂部板岩 .包裹容器 .搜尋 .輸入欄位 {
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
    outline      : 0;

    @include rwd {
        padding  : 0.3rem 2rem 0.25rem;
        font-size: .9rem;
    }
}

.頂部板岩 .包裹容器 .搜尋 .輸入欄位::placeholder {
    color: #FFF;
}

.頂部板岩 .包裹容器 .分類 {
    display   : flex;
    gap       : 1.2rem;
    margin-top: 1rem;

    @include rwd {
        flex-wrap      : wrap;
        justify-content: center;
    }
}

.頂部板岩 .包裹容器 .分類 .項目 {
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
    cursor       : pointer;

    @include rwd {
        font-size: 1rem;
    }
}

.頂部板岩 .包裹容器 .分類 .項目.項目_啟用的 {
    background: #1E2973;
    color     : #FFF;
    border    : 1px solid #FFF;
}

/**
 * 問題集
 */

.問題集 {
    position: relative;
    margin-top: 8rem;
}

.問題集 .群組 {
    max-width     : 690px;
    display       : flex;
    margin        : 0 auto;
    flex-direction: column;
    gap           : 1.5rem;
}

.問題集 .群組 .項目 {
    display       : flex;
    flex-direction: column;
    gap           : 1.5rem;
}

.問題集 .群組 .項目 .標題 {
    display      : flex;
    border       : 1px solid #036EB7;
    border-radius: .4rem;
    padding      : .5rem 1.5rem;
    color        : #036EB7;
    font-size    : 1.2rem;

    @include rwd {
        font-size: .9rem;
    }
}

.問題集 .群組 .項目 .標題 .文字 {
    flex: 1;
}

.問題集 .群組 .項目 .標題 .箭頭 {
    width    : 20px;
    transform: rotate(180deg);
}

.問題集 .群組 .項目.項目_啟用的 .標題 .箭頭 {
    transform: none;
}

.問題集 .群組 .項目 .內容 {
    display      : none;
    border       : 1px solid #5D5555;
    border-radius: .4rem;
    padding      : 0.5rem 1.5rem 1rem;
    color        : #5D5555;
    font-size    : 1.2rem;

    @include rwd {
        font-size: .9rem;
    }
}

.問題集 .群組 .項目.項目_啟用的 .內容 {
    display: flex;
}
</style>
