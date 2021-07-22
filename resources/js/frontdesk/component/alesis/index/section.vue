<template>
    <div class="sectionx" :class="{'-secondary': secondary, '-multiline': multiline !== undefined, '-topline': topline !== undefined, '-outlined': outlined, '-outlined-multiline': outlinedMulti, '-description-multiline': descriptionMulti}">
        <div class="header" v-if="header">
            <div class="before-dots">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
            </div>
            <div class="text">
                <template v-if="topline !== undefined">
                    <div class="topline">{{ topline }}</div>
                </template>
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
        topline: "",
        header   : "",
        secondary: false,
        multiline: "",
        outlined : false,
        nospace  : false,
        outlinedMulti: false,
        descriptionMulti: false,
    },
};
</script>

<style lang="scss" scoped>
@import "../alesis";

.sectionx {
    position  : relative;
    max-width : 100vw;
    padding   : var(--alesis-xsection-offset-top, 0) 0 var(--alesis-xsection-offset-bottom, 0);

    @include rwd {
        padding-left: 2rem;
        padding-right: 2rem;
    }

    &.-secondary {
        background: #ecedf180;
    }

    &.-outlined {
        > .header {
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
        > .header {
            transform: translate(-50%, -20%);

            .before-dots {
                bottom: 6rem;

                @include rwd {
                    bottom: 5rem;
                }
            }
            .after-dots {
                top: 6.5rem;

                @include rwd {
                    top: 5.5rem;
                }
            }
        }
    }



    &.-description-multiline {
        > .header {
            transform: translate(-50%, -15%);

            .text {
                .multiline {
                    font-size  : 1.5rem;
                    white-space: pre;
                    line-height: 1.7;

                    @include rwd {
                        font-size: .9rem;
                    }
                }
            }
            .before-dots {
                bottom: 8.5rem;

                @include rwd {
                    bottom: 5.5rem;
                }
            }
            .after-dots {
                top: 9rem;

                @include rwd {
                    top: 6rem;
                }
            }
        }
    }

    &.-outlined-multiline {
        > .header {
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

    &.-topline {
        > .header {
            transform: translate(-50%, -40%);

            @include rwd {
                transform: translate(-50%, -32%);
            }

            .before-dots {
                bottom: 9rem;

                @include rwd {
                    bottom: 6rem;
                }
            }

            .after-dots {
                top: 9.9rem;

                @include rwd {
                    top: 6.9rem;
                }
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

            @include rwd {
                bottom: 1.5rem;
            }

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

            @include rwd {
                font-size  : 1.3rem;
                // white-space: initial;
                // max-width  : 400px;
                line-height: 1;
            }
        }

        .multiline {
            margin-top: 1rem;

            @include rwd {
                white-space: initial;

                // &:not(.-outlined)
                width      : 355px;
            }
        }

        .after-dots {
            position : absolute;
            top      : 3rem;
            left     : 50%;
            transform: translateX(-50%);

            @include rwd {
                top: 2rem;
            }

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
