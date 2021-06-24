<template>
    <div class="item" :class="{'-level-1': level == 1, '-level-2': level == 2, '-level-3': level == 3, '-level-4': level == 4, '-multiline': multiline !== undefined}">
        <div class="line"></div>
        <div class="circle"></div>
        <div class="text">
            {{ header }}
            <template v-if="multiline !== undefined">
                <div class="multiline">{{ multiline }}</div>
            </template>
        </div>
    </div>
</template>

<script>
export default {
    name : "Moon",
    props: {
        level    : 0,
        header   : "",
        multiline: ""
    }
};
</script>

<style lang="scss" scoped>
@import "../alesis";

.item {
    display        : flex;
    align-items    : center;
    position       : relative;
    justify-content: flex-end;
    min-width      : 130px;

    &.-multiline {
        .text {
            margin-left: .3rem;
        }
    }

    &.-level-1 {
        .line {
            background: #112e53;
        }
        .circle {
            border-color: #112e53;
        }
        .text {
            color: #112e53;
        }
    }

    &.-level-2 {
        .line {
            width     : 35px;
            background: #326398;
        }
        .circle {
            border-color: #326398;
        }
        .text {
            color: #326398;
        }
    }

    &.-level-3 {
        .line {
            width     : 20px;
            background: #2794e0;
        }
        .circle {
            border-color: #2794e0;
        }
        .text {
            color: #2794e0;
        }
    }

    &.-level-4 {
        .line {
            width     : 0px;
            background: #68bde7;
        }
        .circle {
            border-color: #68bde7;
        }
        .text {
            color: #68bde7;
        }
    }

    .line {
        height      : 1px;
        width       : 60px;
        margin-right: -16px;
        background  : #000;

        @include rwd {
            margin-right: -10px;
        }
    }

    .circle {
        width                  : 60px;
        height                 : 30px;
        border                 : 3px solid black;
        transform              : rotate(270deg);
        border-bottom          : 0;
        left                   : 24px;
        border-top-left-radius : 113px;
        border-top-right-radius: 110px;

        @include rwd {
            width : 40px;
            height: 20px;
        }
    }

    .text {
        margin-left: -16px;
        font-weight: bold;
        font-size  : 1.2rem;

        .multiline {
            margin-top: 1rem;
        }
    }
}
</style>
