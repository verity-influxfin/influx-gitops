<template>
    <div class="item">
        <img class="symbol" :src="image">
        <div class="content">
            <div class="header">{{ header }}</div>
            <div class="counter">
                <span class="number">{{ format(displayNumber) }}</span>
                <span class="unit">{{ unit }}</span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name : "Counter",
    props: {
        image : "",
        header: "",
        number: 0,
        unit  : "",
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
.items {
    display        : flex;
    justify-content: center;
}
.item {
    flex           : 1;
    text-align     : center;
    display        : flex;
    align-items    : center;
    justify-content: center;

    @media (max-width: 767px) {
        & {
            flex-direction: column;
        }
    }

    .symbol {
        margin-right: 1rem;

        @media (max-width: 767px) {
            & {
                margin-right: 0;
                width       : 70px;
            }
        }
    }

    .content {
        display        : flex;
        align-items    : flex-start;
        flex-direction : column;
        justify-content: center;

        @media (max-width: 767px) {
            & {
                margin-top : 1rem;
                align-items: center;
            }
        }

        .header {
            font-size  : 1.2rem;
            font-weight: bold;
            color      : #353482;

            @media (max-width: 767px) {
                & {
                    font-size: 1rem;
                }
            }
        }

        .counter {
            font-size: 1.2rem;

            @media (max-width: 767px) {
                & {
                    font-size: 1rem;
                }
            }

            .number {
                color: #646464;
            }

            .unit {
                font-size  : 1.2rem;
                font-weight: bold;
                color      : #353482;

                @media (max-width: 767px) {
                    & {
                        font-size: 1rem;
                    }
                }
            }
        }
    }
}
</style>
