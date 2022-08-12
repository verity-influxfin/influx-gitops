import faqRow  from '@/component/faqRow';

export default {
    title: 'Components/faqRow',
    component: faqRow,
};

const Template = (args, { argTypes }) => ({
    components: { faqRow },
    props: Object.keys(argTypes),
    template: `
    <faq-row v-bind="$props">
        <ol>
            <li>具有合法公司登記或商業登記</li>
            <li>具有合法公司登記或商業登記</li>
            <li>具有合法公司登記或商業登記</li>
            <li>具有合法公司登記或商業登記</li>
        </ol>
    </faq-row>
    `,
});

export const Primary = Template.bind({});
Primary.args = {
    title: '•普匯信保融資專案貸款額度與借款期間？',
    bgText:'ISSUE01'
};
