<template>
    <div class="王道容器">
        <img class="商標" src="/images/oBankBottom.svg">
        <div class="分隔線"></div>
        <template>
            <div class="特色清單">
                <div class="項目">額度最高350萬</div>
                <div class="項目">利率0.07%起</div>
                <div class="項目">開辦費只要1999元</div>
            </div>
            <div class="動作群組">
                <a class="動作" href="/obank">
                    <alesis-button :fluid="true" yPadding=".4rem" xPadding="1rem" size="1.1rem">了解更多</alesis-button>
                </a>

                <a class="動作" href="javascript:;">
                    <alesis-button :fluid="true" yPadding=".4rem" xPadding="1rem" size="1.1rem">取得資格</alesis-button>
                </a>
            </div>
        </template>

    </div>
</template>

<script>
import AlesisButton from "./AlesisButton";

export default {
    name : "AlesisObankcard",
    components: {
        AlesisButton,
    },
    data() {
    },
    mounted() {

        // 管理與財經
        this.workCategories.n = this.workCategories.n.map(v => {
            this.flattenWorkCategories.push({
                disabled: true,
                title: v.des,
                value: "",
            })

            // 經營幕僚
            v.n = v.n.map((j) => {
                j.des = `　　${j.des}`
                this.flattenWorkCategories.push({
                    disabled: true,
                    title: j.des,
                    value: "",
                })
                // 儲備幹部
                j.n = j.n.map((l, k) => {
                    l.des = `　　　　${l.des}`
                    this.flattenWorkCategories.push({
                        disabled: false,
                        title: l.des,
                        value: l.no,
                    })
                    return l
                })
                return j
            })
            return v
        });
    },
    methods: {
    }
};
</script>

<style lang="scss" scoped>
@import "./alesis";

.王道容器 {
    display      : inline-block;
    border-radius: 17px;
    border       : 1px solid #2664a5;
    padding      : 1.5rem 1.25rem;
    width    : 380px;
    text-align   : justify;
    font-size    : 1.3rem;
    margin-left: 50px;

    @include rwd {
        padding  : 1.5rem 1.25rem;
        font-size: 1.1rem;
    }

    .商標 {
        margin : 0 auto;
        display: block;
    }

    .分隔線 {
        height    : 1px;
        background: #2664a5;
        margin    : 1rem 0;
    }

    .特色清單 {
        line-height: 2;
        margin-left: 1rem;

        .項目 {
            display: list-item;
            line-height: 1.5em;
            padding-bottom: 1em;

            @include rwd {
                font-size: 15px;
            }
        }
    }
    .動作群組 {
        margin-top: 1rem;
        display   : flex;
        .動作 {
            flex         : 1;
            margin-right : 1rem;
            cursor       : pointer;
            & + .動作 {
                margin-right: 0;
            }
        }
    }
}
</style>
