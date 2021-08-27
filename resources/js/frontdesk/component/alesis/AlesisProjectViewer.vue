<template>
    <div class="案件區塊">
        <div class="包裹容器">
            <div class="分類">
                <button @click="changeProductID(0)" class="項目" :class="{'項目_啟用的': product_id === 0}">
                    <div class="文字">全部</div>
                </button>
                <button @click="changeProductID(1)" class="項目" :class="{'項目_啟用的': product_id === 1}">
                    <div class="文字">學生</div>
                </button>
                <button @click="changeProductID(3)" class="項目" :class="{'項目_啟用的': product_id === 3}">
                    <div class="文字">上班族</div>
                </button>
            </div>
            <div class="排序條件">
                <!--<a @click="changeOrderBy('date')" class="項目" :class="{'項目_啟用的': order_by === 'date'}">上架日</a>-->
                <!--<a @click="changeOrderBy('amount')" class="項目" :class="{'項目_啟用的': order_by === 'amount'}">金額</a>-->
                <a @click="changeOrderBy('instalment')" class="項目" :class="{'項目_啟用的': order_by === 'instalment'}">期數</a>
                <a @click="changeOrderBy('interest')" class="項目" :class="{'項目_啟用的': order_by === 'interest'}">年利率</a>
                <a @click="changeOrderBy('creditLevel')" class="項目" :class="{'項目_啟用的': order_by === 'creditLevel'}">信評</a>
                <div class="項目">
                    <div class="箭頭群組">
                        <img @click="changeSortBy('desc')" class="排序項目 排序項目_上" :class="{'排序項目_啟用的': sort_by === 'desc'}" src="/images/alesis-work-loan-arrow-up.svg">
                        <img @click="changeSortBy('asc')" class="排序項目 排序項目_下" :class="{'排序項目_啟用的': sort_by === 'asc'}" src="/images/alesis-work-loan-arrow-down.svg">
                    </div>
                </div>
            </div>
            <div class="卡片列表">
                <div class="項目" v-for="result in paginated_results[page]" :key="result.id">
                    <alesis-project v-bind="result"></alesis-project>
                </div>
            </div>
            <div class="分頁導覽">
                <a @click="prevPage" class="項目">上一頁</a>
                <a @click="jumpPage(p-1)" class="項目" :class="{'項目_啟用的': page === p-1}" v-for="p in (this.max_page < 10 ? this.max_page : 10)" :key="p">{{ p }}</a>
                <a @click="nextPage" class="項目">下一頁</a>
            </div>
        </div>
    </div>
</template>

<script>
// Alesis 元件
import AlesisProject           from "./AlesisProject";
//
import { alesisProjects } from "./../../pages/api"

export default {
    components: {
        AlesisProject,
    },
    name: "AlesisProjectViewer",
    props: {
        initialStatus: {
            type   : Number,
            default: 3
        },
    },
    mounted() {
        this.status = this.initialStatus;
        this.getCases()
    },
    data: () => ({
        product_id       : 0,            // 0 = 全部, 1 = 學生, 3 = 上班族
        order_by         : "instalment",
        sort_by          : "desc",
        count            : 6,
        page             : 0,
        max_page         : 0,
        status           : 3,            // 3 = 上架中, 10 = 已結案
        paginated_results: [],
        results          : []
    }),
    methods: {
        getCases() {
            this.page = 0
            this.max_page = 0
            //
            alesisProjects({
                product_id: this.product_id,
                status    : this.status,
                orderby   : this.order_by,
                sort      : this.sort_by,
            }).then((v) => {
                //
                this.results = v;
                //
                this.paginated_results = []
                //
                for (let i=0; i< v.length; i+=this.count) {
                    this.paginated_results.push(this.results.slice(i, i+this.count))
                }
                this.max_page = parseInt(this.results.length / this.count, 10);
            })
        },
        changeProductID(v) {
            this.product_id = v;
            this.getCases()
        },
        nextPage() {
            if (this.page + 1 >= this.max_page) {
                return
            }
            this.page++;
        },
        prevPage() {
            if (this.page === 0) {
                return
            }
            this.page--;
        },
        jumpPage(page) {
            this.page = page;
        },
        changeSortBy(v) {
            this.sort_by = v;
            this.getCases()
        },
        changeOrderBy(v) {
            this.order_by = v;
            this.getCases()
        }
    }
}
</script>

<style lang="scss" scoped>
@import "./alesis";

/**
 * 案件區塊
 */

.案件區塊 {
    position: relative;
}

.案件區塊 .包裹容器 {
    margin: 0 auto;
}

.案件區塊 .包裹容器 .分類 {
    display        : flex;
    align-items    : center;
    justify-content: center;
    gap            : 1rem;
}

.案件區塊 .包裹容器 .分類 .項目 {
    border       : 1px solid #326398;
    border-radius: .4rem;
    background   : transparent;
    appearance   : none;
    padding      : .25rem .8rem;
    font-size    : 1.5rem;
    display      : flex;
    align-items  : center;
    gap          : .8rem;
    white-space  : nowrap;
    color        : #326398;
    outline      : 0;

    @include rwd {
        font-size: 1rem;
        padding  : .25rem .5rem;
        gap      : .4rem;
    }
}

.案件區塊 .包裹容器 .分類 .項目.項目_啟用的 {
    background: #326398;
    color     : #FFF;
}

.案件區塊 .包裹容器 .分類 .項目 .標籤 {
    font-size    : 1rem;
    border       : 1px solid #326398;
    padding      : .2rem .5rem;
    border-radius: .4rem;
    line-height  : 1;
    color        : #326398;
    background   : #FFF;

    @include rwd {
        font-size: .8rem;
    }
}

.案件區塊 .包裹容器 .排序條件 {
    display        : flex;
    align-items    : center;
    justify-content: center;
    margin-top     : 1rem;

    @include rwd {
        margin-top: 0;
    }
}

.案件區塊 .包裹容器 .排序條件 .項目 {
    padding        : 1rem 3rem;
    font-size      : 1.2rem;
    color          : #AAAAAA;
    text-decoration: none;
    white-space    : nowrap;
    cursor         : pointer;

    @include rwd {
        padding  : 1rem .7rem;
        font-size: 1rem;
    }
}

.案件區塊 .包裹容器 .排序條件 .項目.項目_啟用的 {
    color: #4D4D4D;
}

.案件區塊 .包裹容器 .排序條件 .項目 .箭頭群組 {
    display       : flex;
    flex-direction: column;
}

.案件區塊 .包裹容器 .排序條件 .項目 .箭頭群組 .排序項目.排序項目_上,
.案件區塊 .包裹容器 .排序條件 .項目 .箭頭群組 .排序項目.排序項目_下 {
    cursor : pointer;
    opacity: 0.5;
    width  : 16px;

    @include rwd {
        width: 12px;
    }
}

.案件區塊 .包裹容器 .排序條件 .項目 .箭頭群組 .排序項目.排序項目_啟用的 {
    opacity: 1;
}

.案件區塊 .包裹容器 .卡片列表 {
    max-width            : 950px;
    margin               : 0 auto;
    display              : flex;
    grid-template-columns: repeat(2, 1fr);
    gap                  : 3rem;
    margin-top           : 2rem;
    flex-wrap            : wrap;
    align-items          : center;
    justify-content      : center;

    @include rwd {
        grid-template-columns: 1fr;
    }
}

.案件區塊 .包裹容器 .卡片列表 > .項目 {
    @include rwd {
        display        : flex;
        align-items    : center;
        justify-content: center;
        transform      : scale(0.8) translateX(4.3rem);
        margin-left    : -7rem;
        margin-top     : -3rem;
    }
}

.案件區塊 .包裹容器 .分頁導覽 {
    display        : flex;
    width          : min-content;
    white-space    : nowrap;
    margin         : 0 auto;
    border         : 1px solid #326398;
    border-radius  : .4rem;
    align-items    : center;
    justify-content: center;
    margin-top     : 2rem;
}

.案件區塊 .包裹容器 .分頁導覽 .項目 {
    line-height    : 1;
    color          : #326398;
    padding        : .5rem .7rem;
    font-size      : 1.1rem;
    border-right   : 1px solid #326398;
    vertical-align : top;
    text-decoration: none;
    cursor         : pointer;

    @include rwd {
        font-size: 1rem;
        padding  : .5rem .5rem;
    }
}

.案件區塊 .包裹容器 .分頁導覽 .項目:last-child {
    border-right: 0;
}

.案件區塊 .包裹容器 .分頁導覽 .項目.項目_啟用的 {
    background: #326398;
    color     : #FFF;
}
</style>
