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
    watch: {
      	number: function(newVal, oldVal) { // watch it
            this.countdown()
        }
      },
    mounted() {
        if (this.number != 0) {
            this.displayNumber = Math.round(this.number / 1.5)
        }
    },
    methods: {
        countdown() {
            var adder = setInterval(() => {
                var adder_number = parseInt(this.number / 40)
                this.displayNumber += adder_number

                if(this.displayNumber >= this.number) {
                    this.displayNumber = this.number
                    clearInterval(adder)
                }
            }, 100)
        },

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
    text-align     : center;
    display        : flex;
    align-items    : center;
    justify-content: center;
    flex-direction: column;
}

.計數項目 .象徵 {
    width: 75px;
    margin-bottom: 15px;
    @include rwd{
        margin-bottom: 8px;
    }
}

.計數項目 .內容 {
    display        : flex;
    align-items    : center;
    flex-direction : column;
    justify-content: center;

    @include rwd {
        margin-top : 0;
    }
}

.計數項目 .內容 .標題 {
    font-size  : 1.2rem;
    font-weight: bold;
    color      : #023D64;

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
    color: #F29500;
}

.計數項目 .內容 .計數器 .單位 {
    font-size  : 1.2rem;
    font-weight: bold;
    color      : #023D64;

    @include rwd {
        font-size: 1rem;
    }
}
</style>
