import stepGroup from '@/component/index/stepGroup.vue';

export default {
    title: 'Components/stepGroup',
    component: stepGroup,
};

const Template = (args, { argTypes }) => ({
    components: { stepGroup },
    props: Object.keys(argTypes),
    template: '<div style="max-width:620px"><stepGroup v-bind="$props"/></div>',
});

export const Default = Template.bind({});
Default.args = {};
export const second = Template.bind({});
second.args = {
    steps: [
        {
            img: require('@/asset/images/index/invest-step-1.png'),
            title: '步驟一',
            info: '<div>申請「信保專案融資」</div><div>完成法人註冊、負責人實名認證</div>'
        },
        {
            img: require('@/asset/images/index/invest-step-2.png'),
            title: '步驟二',
            info: '確認借款期間與額度，同意申請'
        },
        {
            img: require('@/asset/images/index/invest-step-3.png'),
            title: '步驟三',
            info: '<div>完成負責人、公司資料提供</div><div>(包含負責人配偶、新增保證人)</div>'
        },
        {
            img: require('@/asset/images/index/invest-step-4.png'),
            title: '步驟四',
            info: '<div>等待系統審核並媒合資金方(銀行)</div><div>銀行最終核准後、簽約對保、立即撥款</div>'
        }
    ],
    contentClass: 'test'

};

export const swiperMode = Template.bind({});
swiperMode.args = {
    swiperMode: true
};
