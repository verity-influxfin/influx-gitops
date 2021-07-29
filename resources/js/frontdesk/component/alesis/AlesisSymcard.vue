<template>
    <div class="特色卡片">
        <div class="標題">
            <div class="象徵">
                <div class="包裹容器">
                    <img class="圖片" :src="symbol">
                </div>
            </div>
            <div class="文字">
                <div class="標題">{{ header }}</div>
                <div class="線條"></div>
                <div v-if="subheader" class="子標題">{{ subheader }}</div>
            </div>
        </div>
        <div class="內容">
            <img class="圖片" :src="image">
            <div class="註釋">
                {{ description }}
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name : "AlesisSymcard",
    props: {
        symbol: {
            default: "",
        },
        image: {
            default: "",
        },
        header: {
            default: "",
        },
        subheader: {
            default: "",
        },
        description: {
            default: "",
        },
    }
};
</script>

<style lang="scss" scoped>
.特色卡片 {
    box-shadow    : 0px 4px 6px 0px rgb(0 0 0 / 30%);
    flex          : 1;
    display       : flex;
    flex-direction: column;
    border-radius : 20px;
}

.特色卡片 > .標題 {
    background   : #ecedf1;
    display      : flex;
    padding      : .5rem 1rem;
    box-sizing   : border-box;
    border-radius: 20px 20px 0px 0;
    box-shadow   : 0px 6px 6px 0px rgb(0 0 0 / 13%);
    z-index      : 1;
    position     : relative;
    min-height   : 78px;
    align-items  : center;
}

.特色卡片 > .標題 .象徵 {
    position: relative;
    width   : 110px;
}

.特色卡片 > .標題 .象徵 .包裹容器 {
    position     : absolute;
    top          : -4.5rem;
    left         : .5rem;
    background   : #FFF;
    padding      : .5rem;
    border-radius: 100rem;
    box-shadow   : 0px 4px 6px 0px rgb(0 0 0 / 20%);
    z-index      : 1;
}

.特色卡片 > .標題 .象徵 .包裹容器 .圖片 {
    width : 80px;
    height: 80px;
}

.特色卡片 > .標題 .文字 {
    position: relative;
    flex    : 1;
}

.特色卡片 > .標題 .文字 .標題 {
    text-align      : center;
    background-image: linear-gradient(to right, #1e2973 0%, #319acf 50%, #1e2973 75%);
    background-clip : text;
    width           : fit-content;
    color           : rgba(255, 255, 255, 0);
    margin          : 0px auto;
    font-weight     : bolder;
    font-size       : 1.4rem;
    line-height     : 1.8;
}

.特色卡片 > .標題 .文字 .子標題 {
    text-align      : center;
    background-image: linear-gradient(to right, #1e2973 0%, #319acf 50%, #1e2973 75%);
    background-clip : text;
    width           : fit-content;
    color           : rgba(255, 255, 255, 0);
    margin          : 0px auto;
    font-weight     : bolder;
    font-size       : .9rem;
    line-height     : 1.7;
}

.特色卡片 > .標題 .文字 .線條 {
    height    : 1px;
    background: #2f95cb;
    width     : 40%;
    transform : translateX(-50%);
    left      : 50%;
    position  : relative;
}

.特色卡片 .內容 {
    position     : relative;
    overflow     : hidden;
    border-radius: 0px 0px 20px 20px;
    flex: 1;
}

.特色卡片 .內容 .圖片 {
    vertical-align: top;
    width         : 100%;
    height        : 190px;
    object-fit    : cover;
}

.特色卡片 .內容 .註釋 {
    position: absolute;
    bottom  : 0;
    left    : 0;
    right   : 0;
    background: rgb(0 0 0 / 70%);
    color: #FFF;
    padding: 1rem;
}
</style>
