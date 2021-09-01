<template>
    <div class="王道容器">
        <img class="商標" src="/images/oBankBottom.svg" style="height: 33px;width: 243px;">
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
    max-width    : 380px;
    text-align   : justify;
    font-size    : 1.3rem;
    margin-right: 50px;

    @include rwd {
        padding  : 1.5rem 1.25rem;
        font-size: 1.1rem;
        margin-bottom: 10px;
        margin-righ: unset;
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
