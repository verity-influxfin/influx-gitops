import pureCaseOverview from '@/component/enterpriseUpload/pureCaseOverview';
export default {
    title: '企金/案件總覽 (caseOverview)',
    component: pureCaseOverview,
};
const Template = (args, { argTypes }) => ({
    components: { pureCaseOverview },
    props: Object.keys(argTypes),
    template: `<pure-case-overview v-bind="$props"/>`,
});

export const 預設 = Template.bind({})
預設.args = {
    caseOverview: {
        title: '我的申貸',
        productTitle: '普匯學生貸',
        caseNum: 'N1234456',
        startDate: '2020/01/01',
        loanMoney: '10000000',
        loanDur: '2年'
    },
}
