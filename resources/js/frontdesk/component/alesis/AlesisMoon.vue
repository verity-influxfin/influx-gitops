<template>
    <div class="半月項目" :class="{
        '半月項目_等級一': level === 1,
        '半月項目_等級二': level === 2,
        '半月項目_等級三': level === 3,
        '半月項目_等級四': level === 4,
        '半月項目_多行': multiline !== ''
    }">
        <div class="線條"></div>
        <div class="圓圈"></div>
        <div class="文字">
            {{ header }}
            <template v-if="multiline !== ''">
                <div class="多行">{{ multiline }}</div>
            </template>
        </div>
    </div>
</template>

<script>
export default {
    name : "AlesisMoon",
    props: {
        level: {
            default: 0,
        },
        header: {
            default: "",
        },
        multiline: {
            default: "",
        }
    }
};
</script>

<style lang="scss" scoped>
@import "./alesis";

.半月項目 {
    display        : flex;
    align-items    : center;
    position       : relative;
    justify-content: flex-end;
    min-width      : 130px;

    @include rwd {
        transform: translateX(-40px);
    }
}

.半月項目.半月項目_多行 .文字 {
    margin-left: .3rem;
}

.半月項目.半月項目_等級一 .線條 {
    background: #112e53;
}

.半月項目.半月項目_等級一 .圓圈 {
    border-color: #112e53;
}

.半月項目.半月項目_等級一 .文字 {
    color: #112e53;
}

.半月項目.半月項目_等級二 .線條 {
    width     : 35px;
    background: #326398;
}

.半月項目.半月項目_等級二 .圓圈 {
    border-color: #326398;
}

.半月項目.半月項目_等級二 .文字 {
    color: #326398;
}

.半月項目.半月項目_等級三 .線條 {
    width     : 20px;
    background: #2794e0;
}

.半月項目.半月項目_等級三 .圓圈 {
    border-color: #2794e0;
}

.半月項目.半月項目_等級三 .文字 {
    color: #2794e0;
}

.半月項目.半月項目_等級四 .線條 {
    width     : 0px;
    background: #68bde7;
}

.半月項目.半月項目_等級四 .圓圈 {
    border-color: #68bde7;
}

.半月項目.半月項目_等級四 .文字 {
    color: #68bde7;
}

.半月項目 .線條 {
    height      : 1px;
    width       : 60px;
    margin-right: -16px;
    background  : #000;

    @include rwd {
        margin-right: -10px;
    }
}

.半月項目 .圓圈 {
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

.半月項目 .文字 {
    margin-left: -16px;
    font-weight: bold;
    font-size  : 1.2rem;

    @include rwd {
        display: flex;
    }
}

.半月項目 .文字 .多行 {
    margin-top: 1rem;

    @include rwd {
        margin     : 0;
        margin-left: 1rem;
    }
}
</style>
