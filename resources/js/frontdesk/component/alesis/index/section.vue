<template>
    <div class="section" :class="{'-secondary': secondary, '-multiline': multiline !== undefined, '-outlined': outlined, '-outlined-multiline': outlinedMulti}">
        <div class="header" v-if="header">
            <div class="before-dots">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
            </div>
            <div class="text">
                {{ header }}
                <template v-if="multiline !== undefined">
                    <div class="multiline">{{ multiline }}</div>
                </template>
            </div>
            <div class="after-dots">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
            </div>
        </div>
        <div class="spacer" v-if="!nospace && header"></div>
        <slot></slot>
        <div class="spacer" v-if="!nospace"></div>
    </div>
</template>

<script>
export default {
    name : "Section",
    props: {
        header   : "",
        secondary: false,
        multiline: "",
        outlined : false,
        nospace  : false,
        outlinedMulti: false,
    },
};
</script>

<style lang="scss" scoped>
.section {
    position: relative;

    &.-secondary {
        background: #ecedf180;
    }

    &.-outlined {
        .header {
            border       : 1px solid #1a5fa2;
            padding      : 5px;
            border-radius: 7px;

            .before-dots {
                bottom: 3rem;
            }
            .after-dots {
                display: none;
            }
        }
    }


    &.-multiline {
        .header {
            transform: translate(-50%, -20%);

            .before-dots {
                bottom: 6rem;
            }
            .after-dots {
                top: 6.5rem;
            }
        }
    }


    &.-outlined-multiline {
        .header {
            .text {
                .multiline {
                    border       : 1px solid #1a5fa2;
                    padding      : .5rem;
                    line-height  : 1;
                    border-radius: .4rem;
                }
            }

            .after-dots {
                top: 6.9rem;
            }
        }
    }

    > .header {
        position : absolute;
        left     : 50%;
        top      : 0;
        transform: translate(-50%, -50%);

        .dot {
            width        : 6px;
            height       : 6px;
            background   : #3670d3;
            border-radius: 100rem;
            margin-bottom: .7rem;
            margin-left  : auto;
            margin-right : auto;
        }

        .before-dots {
            position : absolute;
            bottom   : 2rem;
            left     : 50%;
            transform: translateX(-50%);

            .dot:nth-child(1) {
                opacity: 0.2;
                width  : 3px;
                height : 3px;
            }
            .dot:nth-child(2) {
                opacity: 0.4;
                width  : 4px;
                height : 4px;
            }
            .dot:nth-child(3) {
                opacity: 0.6;
            }
            .dot:nth-child(4) {
                opacity: 0.75;
            }
        }

        .text {
            text-align      : center;
            background-image: linear-gradient(to right, #1e2973 0%, #319acf 50%, #1e2973 75%);
            background-clip : text;
            width           : fit-content;
            color           : rgba(255, 255, 255, 0);
            margin          : 0px auto;
            font-weight     : bolder;
            font-size       : 2rem;
            line-height     : 1.2;
            white-space     : nowrap;
        }

        .multiline {
            margin-top: 1rem;
        }

        .after-dots {
            position : absolute;
            top      : 3rem;
            left     : 50%;
            transform: translateX(-50%);

            .dot:nth-child(1) {
                opacity: 0.75;
            }
            .dot:nth-child(2) {
                opacity: 0.6;
            }
            .dot:nth-child(3) {
                opacity: 0.4;
                width  : 4px;
                height : 4px;
            }
            .dot:nth-child(4) {
                opacity: 0.2;
                width  : 3px;
                height : 3px;
            }
        }
    }

    .spacer {
        height: 8rem;
    }
}
</style>
