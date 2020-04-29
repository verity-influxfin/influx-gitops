<template>
    <div class="share-btn-wrapper">
        <div class="add-to">
            <div @click="addToFB"><img :src="'./image/facebook.svg'" class="img-fluid"></div>
            <div @click="addToMessenger" class="postion tiny-color"><img :src="'./image/messenger.svg'" class="img-fluid"></div>
            <div @click="addToLINE"><img :src="'./image/line.png'" class="img-fluid"></div>
            <div @click="addToMail" class="postion heavy-color"><img :src="'./image/mail.svg'" class="img-fluid"></div>
            <div @click="showCopyinput" class="postion heavy-color"><img :src="'./image/link.svg'" class="img-fluid"></div>
        </div>
        <div class="share">
            <div @click="fbShare"><i class="fab fa-facebook-square"></i></div>
            <div @click="twitterShare"><i class="fab fa-twitter-square"></i></div>
            <div @click="linkinShare"><i class="fab fa-linkedin"></i></div>
            <div @click="googleShare"><i class="fab fa-google-plus-square"></i></div>
        </div>
        <div class="modal fade" ref="linkModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="input-group">
                        <span class="input-group-addon heavy-color"><img :src="'./image/link.svg'" class="img-fluid"></span>
                        <input type="text" class="form-control" v-model="this.copyLink" @click="copy()">
                    </div>
                    <div v-if="this.isCopyed" class="copyed"><i class="fas fa-check-circle"></i></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props:['link'],
    data:()=>({
        copyLink:'',
        isCopyed:false
    }),
    methods:{
        addToFB(){
            window.open(`https://www.addtoany.com/add_to/facebook?linkurl=${this.$props.link}`,'_blank', 'top=' + (window.outerHeight / 2 - 265) + ', left=' + (window.outerWidth / 2 - 265) + ',height=530,width=530,toolbar=no,resizable=no,location=no');
        },
        addToMessenger(){
            window.open(`https://www.addtoany.com/add_to/facebook_messenger?linkurl=${this.$props.link}`,'_blank', 'top=' + (window.outerHeight / 2 - 265) + ', left=' + (window.outerWidth / 2 - 265) + ',height=530,width=530,toolbar=no,resizable=no,location=no');
        },
        addToLINE(){
            window.open(`https://lineit.line.me/share/ui?url=${this.$props.link}`,'_blank', 'top=' + (window.outerHeight / 2 - 265) + ', left=' + (window.outerWidth / 2 - 265) + ',height=530,width=530,toolbar=no,resizable=no,location=no');
        },
        addToMail(){
            window.open(`https://www.addtoany.com/add_to/email?linkurl=${this.$props.link}`,'_blank', 'top=' + (window.outerHeight / 2 - 265) + ', left=' + (window.outerWidth / 2 - 265) + ',height=530,width=530,toolbar=no,resizable=no,location=no');
        },
        showCopyinput(){
            this.isCopyed = false;
            this.copyLink = this.$props.link.replace('%23','#');
            $(this.$refs.linkModal).modal('show');
        },
        fbShare(){
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${this.$props.link}`);
        },
        twitterShare(){
            window.open(`https://twitter.com/intent/tweet?url=${this.$props.link}`);
        },
        linkinShare(){
            window.open(`https://www.linkedin.com/shareArticle?mini=true&url=${this.$props.link}`);
        },
        googleShare(){
            window.open(`https://plus.google.com/share?url=${this.$props.link}`);
        },
        copy(){
            document.execCommand('selectAll');
            document.execCommand('Copy');
            this.isCopyed = true;
        }
    }
}
</script>

<style lang="scss">
    .share-btn-wrapper{
        overflow: auto;

        .tiny-color{
            background: #0084ff;
        }

        .heavy-color{
            background: #0166ff;
        }

        .modal-dialog{
            margin: 7.5rem 1.5rem;

            @media (min-width: 576px){
                margin: 14.75rem auto;
            }
        }

        .input-group-addon{
            width: 38px;
            height: 38px;
        }
        
        .add-to{
            display: flex;

            div{
                cursor: pointer;
                width: 30px;
                height: 30px;
                margin: 0px 5px;

                &:hover{
                    opacity: 0.7;
                }
            }

            .postion{
                padding: 2px 4px;
                border-radius: 3px;
            }

        }

        .share{
            display: flex;
            float: right;

            div{
                font-size: 30px;
                margin: 0px 5px;
                color: gray;
                cursor: pointer;
                
                &:hover{
                    opacity: 0.7;
                }
            }
        }

        .copyed{
            position: absolute;
            right: 5px;
            font-size: 28px;
            color: #08bb08;
            z-index: 5;
        }
    }
</style>