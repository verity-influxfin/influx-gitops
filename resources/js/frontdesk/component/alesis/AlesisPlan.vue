<template>
    <div class="單張容器">
        <img class="圖示" :src="image">
        <div class="特色">
            <div class="標題">
                {{ header }}
            </div>
            <div class="清單">
                <div class="項目" v-for="(item, index) in targets" :key="index">{{ item }}</div>
                <div class="尚未就緒" v-if="unready">敬請期待</div>
            </div>
        </div>
        <div class="清單">
            <div class="項目" v-for="(item, index) in features" :key="index">{{ item }}</div>
            <div class="尚未就緒" v-if="unready">敬請期待</div>
        </div>
        <a class="動作" :href="link +'#credit_report'" target="_blank">
            <alesis-button xPadding="0.9rem" yPadding="1rem" size="1.3rem">{{ action }}</alesis-button>
        </a>
    </div>
</template>

<script>
import AlesisButton from "./AlesisButton";

export default {
    name      : "AlesisPlan",
    components: {
        AlesisButton,
    },
    props: {
        header  : {
            default: "",
        },
        unready : {
            default: false,
        },
        targets : {
            default: [],
        },
        action  : {
            default: "",
        },
        link  : {
            default: null,
        },
        image   : {
            default: "",
        },
        features: {
            default: [],
        },
    },
};
</script>

<style lang="scss" scoped>
.單張容器 {
    padding       : 1rem;
    border        : 1px solid #2664a5;
    border-radius : 24px;
    position      : relative;
    height        : 100%;
    display       : flex;
    flex-direction: column;
}

.單張容器 .圖示 {
    position     : absolute;
    top          : 0;
    left         : 50%;
    border       : 8px solid #f5f6f8;
    transform    : translate(-50%, -50%);
    z-index      : 2;
    background   : #f5f6f8;
    border-radius: 3rem;
}

.單張容器 .特色 {
    display       : flex;
    flex-direction: column;
    align-items   : center;
    margin-top    : 3rem;
}

.單張容器 .特色 .標題 {
    font-size  : 1.2rem;
    font-weight: bold;
    color      : #036eb7;
}

.單張容器 .特色 .清單 {
    font-size : .9rem;
    min-height: 43px;
}

.單張容器 .特色 .清單 .項目 {
    display: list-item;
}

.單張容器 .特色 .清單 .尚未就緒 {
    margin-top: 1rem;
    text-align: center;
}

.單張容器 > .清單 {
    border-top   : 1px solid #2664a5;
    padding-top  : 1rem;
    margin-top   : 1rem;
    font-size    : .9rem;
    line-height  : 2;
    margin-bottom: 1.5rem;
}

.單張容器 > .清單 .項目 {
    display    : list-item;
    margin-left: 1rem;
}

.單張容器 > .清單 .尚未就緒 {
    text-align: center
}

.單張容器 .動作 {
    display   : block;
    width     : 100%;
    text-align: center;
    margin-top: auto;
}

.單張容器 .動作 > div {
    width: 100%;
}
</style>
