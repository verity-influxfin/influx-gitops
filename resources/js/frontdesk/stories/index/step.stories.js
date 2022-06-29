import stepItem from '@/component/index/stepItem';
import img1 from '@/asset/images/index/invest-step-1.png'

export default {
    title: 'Components/stepItem',
    component: stepItem,
};

const Template = (args, { argTypes }) => ({
    components: { stepItem },
    props: Object.keys(argTypes),
    template: '<stepItem v-bind="$props"/>',
});

export const 預設 = Template.bind({});
預設.args = {
    step: {
        title: 'Step 1. 選擇額度',
        content: '額度最高15萬<br/>3-24期任你選',
        img: {
            src: img1,
            alt: '',
        }
    }
};

export const withSlot = (args, { argTypes }) => ({
    components: { stepItem },
    props: Object.keys(argTypes),
    template: '<stepItem v-bind="$props"><h1>Test</h1></stepItem>',
});
withSlot.args = {
    step: {
        img: {
            src: img1,
        }
    }
};
