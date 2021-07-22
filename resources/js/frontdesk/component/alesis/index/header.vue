<template>
    <div class="alesis-xheader">
        <div class="before-dots">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>
        <div class="blocks">
            <slot></slot>
        </div>
        <div class="after-dots">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>
    </div>
</template>

<style scoped lang="scss">
@import "../alesis";

.alesis-xheader {
    position : absolute;
    left     : 50%;
    top      : 0;
    transform: translate(-50%, var(--alesis-xheader-offset, -50%));
    z-index  : 10;

    &.-trailing .after-dots {
        display: none;
    }

    .dot {
        width        : 6px;
        height       : 6px;
        background   : #3670d3;
        border-radius: 100rem;
        margin-bottom: .7rem;
        margin-left  : auto;
        margin-right : auto;
    }

    .blocks {
        text-align      : center;
        background-image: linear-gradient(to right, #1e2973 0%, #319acf 50%, #1e2973 75%);
        background-clip : text;
        width           : 90vw;
        color           : rgba(255, 255, 255, 0);
        margin          : 0px auto;
        font-weight     : bolder;
        font-size       : 2rem;
        line-height     : 1.2;

        .header {


            @include rwd {
                font-size  : 1.3rem;
                // white-space: initial;
                // max-width  : 400px;
                line-height: 1;
            }
        }

        .header + .header {
            margin: .3rem 0;
        }

        .header.-outlined {
            border       : 1px solid #1a5fa2;
            padding      : 0.5rem;
            line-height  : 1;
            border-radius: 0.4rem;
            width        : fit-content;
            margin       : 1rem auto;
        }

        .description {
            font-size  : 1.5rem;
            line-height: 1.7;
            margin     : .6rem 0;

            @include rwd {
                font-size: .9rem;
            }
        }
    }

    .before-dots {
        margin-bottom: .5rem;

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

    .after-dots {
        margin-top: .5rem;

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
</style>