<template>
    <div class="計數項目">
        <img class="象徵" :src="image">
        <div class="內容">
            <div class="標題">{{ header }}</div>
            <div class="計數器">
                <span class="數字">{{ format(displayNumber) }}</span>
                <span class="單位">{{ unit }}</span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name : "AlesisCounter",
    props: {
        image: {
            default: "",
        },
        header: {
            default: "",
        },
        number: {
            default: 0,
        },
        unit: {
            default: "",
        }
    },
    mounted() {
        if (this.number != 0) {
            this.displayNumber = Math.round(this.number / 1.5)
        }

        var adder = setInterval(() => {
            this.displayNumber += 100

            if(this.displayNumber >= this.number) {
                this.displayNumber = this.number
                clearInterval(adder)
            }
        }, 50)
    },
    methods: {
        // format 會格式化數值成為有千分逗號的格式。
        format(data) {
            data = parseInt(data);
            if (!isNaN(data)) {
                let l10nEN = new Intl.NumberFormat("en-US");
                return l10nEN.format(data.toFixed(0));
            }
            return 0;
        },
    },
    data() {
        return {
            displayNumber: 0,
        }
    }
};
</script>

<style lang="scss" scoped>
@import "./alesis";

.計數項目 {
    flex           : 1;
    text-align     : center;
    display        : flex;
    align-items    : center;
    justify-content: center;

    @include rwd {
        flex-direction: column;
    }
}

.計數項目 .象徵 {
    margin-right: 1rem;

    @include rwd {
        margin-right: 0;
        width       : 70px;
    }
}

.計數項目 .內容 {
    display        : flex;
    align-items    : flex-start;
    flex-direction : column;
    justify-content: center;

    @include rwd {
        margin-top : 1rem;
        align-items: center;
    }
}

.計數項目 .內容 .標題 {
    font-size  : 1.2rem;
    font-weight: bold;
    color      : #353482;

    @include rwd {
        font-size: 1rem;
    }
}

.計數項目 .內容 .計數器 {
    font-size: 1.2rem;

    @include rwd {
        font-size: 1rem;
    }
}

.計數項目 .內容 .計數器 .數字 {
    color: #646464;
}

.計數項目 .內容 .計數器 .單位 {
    font-size  : 1.2rem;
    font-weight: bold;
    color      : #353482;

    @include rwd {
        font-size: 1rem;
    }
}
</style>
