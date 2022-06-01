import certItem from '@/component/enterpriseUpload/certItem';
import imageFile from '@/asset/images/enterpriseUpload/company-1.svg'
export default {
    title: '企金/驗證項目 (certItem)',
    component: certItem,
};
const Template = (args, { argTypes }) => ({
    components: { certItem },
    props: Object.keys(argTypes),
    template: `<certItem v-bind="$props"/>`,
});

export const 預設 = Template.bind({})
預設.args = {
    icon: imageFile,
    iconText: '我是文字',
    certification: {
        'user_status': 1
    }
}
export const 自定義內容 = (args, { argTypes }) => ({
    components: { certItem },
    props: Object.keys(argTypes),
    template: `
    <cert-item v-bind="$props" icon="${imageFile}">
        <template v-slot:content>
            <div class="row no-gutters w-100 h-100">
                <div class="col cert-content-text">
                1.負責人聯徵報告<br />
                2.加查「<span style="color: red">A11、J10、J03</span>」<br />
                3.請上傳「申請信用報告收執聯」<br />
                4.郵寄至：<br />
                <small>
                    10486台北市中山區松江路111號11樓之1<br />
                    普匯金融科技股份有限公司
                </small>
                </div>
            </div>
        </template>
  </cert-item>`
})
自定義內容.args = {
    iconText:'聯合徵信報告'
}
