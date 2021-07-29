<template>
    <div class="案件區塊">
        <div class="包裹容器">
            <div class="分類">
                <button @click="changeCategoryBy('')" class="項目" :class="{'項目_啟用的': categoryBy === ''}">
                    <div class="文字">全部</div>
                    <div class="標籤">{{ results.length }}</div>
                </button>
                <button @click="changeCategoryBy('student')" class="項目" :class="{'項目_啟用的': categoryBy === 'student'}">
                    <div class="文字">學生</div>
                    <div class="標籤">86</div>
                </button>
                <button @click="changeCategoryBy('work')" class="項目" :class="{'項目_啟用的': categoryBy === 'work'}">
                    <div class="文字">上班族</div>
                    <div class="標籤">60</div>
                </button>
            </div>
            <div class="排序條件">
                <a @click="changeSortBy('date')" class="項目" :class="{'項目_啟用的': sortBy === 'date'}">上架日</a>
                <a @click="changeSortBy('amount')" class="項目" :class="{'項目_啟用的': sortBy === 'amount'}">金額</a>
                <a @click="changeSortBy('instalment')" class="項目" :class="{'項目_啟用的': sortBy === 'instalment'}">期數</a>
                <a @click="changeSortBy('interest')" class="項目" :class="{'項目_啟用的': sortBy === 'interest'}">年利率</a>
                <a @click="changeSortBy('creditLevel')" class="項目" :class="{'項目_啟用的': sortBy === 'creditLevel'}">信評</a>
                <div class="項目">
                    <div class="箭頭群組">
                        <img src="/images/alesis-work-loan-arrow-up.svg" class="上">
                        <img src="/images/alesis-work-loan-arrow-down.svg" class="下">
                    </div>
                </div>
            </div>
            <div class="卡片列表">
                <div class="項目" v-for="result in current_results" :key="result.id">
                    <alesis-project v-bind="result"></alesis-project>
                </div>
            </div>
            <div class="分頁導覽">
                <a @click="prevPage" class="項目">上一頁</a>
                <a href="#!" class="項目">1</a>
                <a href="#!" class="項目">2</a>
                <a href="#!" class="項目">3</a>
                <a href="#!" class="項目">4</a>
                <a href="#!" class="項目">...</a>
                <a href="#!" class="項目">30</a>
                <a @click="nextPage" class="項目">下一頁</a>
            </div>
        </div>
    </div>
</template>

<script>
// Alesis 元件
import AlesisProject           from "./AlesisProject";
// 遠端資料
import StudentDone    from "./../../data/projects_student_done";
import StudentWorking from "./../../data/projects_student_working";
import WorkDone       from "./../../data/projects_work_done";
import WorkWorking    from "./../../data/projects_work_working";

export default {
    components: {
        AlesisProject,
    },
    name: "AlesisProjectViewer",
    props: {
        status: {
            default: 3,
        }
    },
    mounted() {
        // 3 代表進行中 Working
        if (this.status === 3) {
            this.results = [...StudentWorking.data, ...WorkWorking.data]
        } else {
            this.results = [...StudentDone.data, ...WorkDone.data]
        }
        this.paginate(6, 1)
    },
    data() {
        return {
            categoryBy     : "",
            sortBy         : "date",
            orderBy        : "asc",
            results        : [],
            current_results: [],
            studentDone    : StudentDone.data,
            studentWorking : StudentWorking.data,
            workDone       : WorkDone.data,
            workWorking    : WorkWorking.data,
            page           : 1,
        }
    },
    methods: {
        changeCategoryBy(v) {
            this.categoryBy = v;
        },
        changeStatus(v) {
            this.status = v;
            this.changeOrderBy(this.orderBy);
        },
        nextPage() {
            var maxPage = parseInt(this.results.length / 6, 10);
            if (this.page >= maxPage) {
                return
            }
            this.page++;
            this.paginate(6, this.page)
        },
        prevPage() {
            if (this.page <= 1) {
                return
            }
            this.page--;
            this.paginate(6, this.page)
        },
        paginate(size, page) {
            return this.current_results = this.results.slice((page - 1) * size, page * size);
        },
        jumpPage() {

        },
        changeSortBy(v) {
            this.sortBy = v
            switch (v) {
                case "date":
                    this.results.sort((a, b) => {
                        if (this.orderBy === "asc") {
                            return a.created_at > b.created_at
                        } else {
                            return a.created_at < b.created_at
                        }
                    })
                    break
                case "instalment":
                    this.results.sort((a, b) => {
                        if (this.orderBy === "asc") {
                            return a.instalment > b.instalment
                        } else {
                            return a.instalment < b.instalment
                        }
                    })
                    break
                case "amount":
                    this.results.sort((a, b) => {
                        if (this.orderBy === "asc") {
                            return a.loan_amount > b.loan_amount
                        } else {
                            return a.loan_amount < b.loan_amount
                        }
                    })
                    break
                case "interest":
                     this.results.sort((a, b) => {
                        if (this.orderBy === "asc") {
                            return a.interest_rate > b.interest_rate
                        } else {
                            return a.interest_rate < b.interest_rate
                        }
                    })
                    break
                case "creditLevel":
                    this.results.sort((a, b) => {
                        if (this.orderBy === "asc") {
                            return a.credit_level > b.credit_level
                        } else {
                            return a.credit_level < b.credit_level
                        }
                    })
                    break
            }
        },
        changeOrderBy(v) {
            this.orderBy = (v === "asc") ? "desc" : "asc";
            changeSortBy(this.sortBy)
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

.案件區塊 .包裹容器 .排序條件 .項目 .箭頭群組 img {
    width: 16px;

    @include rwd {
        width: 12px;
    }
}

.案件區塊 .包裹容器 .卡片列表 {
    max-width            : 950px;
    margin               : 0 auto;
    display              : grid;
    grid-template-columns: repeat(2, 1fr);
    gap                  : 3rem;
    margin-top           : 2rem;

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
</style>