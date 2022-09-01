import smegSuit  from '@/component/smegSuit';

export default {
    title: '信保專案/smegSuit',
    component: smegSuit,
};

const Template = (args, { argTypes }) => ({
    components: { smegSuit },
    props: Object.keys(argTypes),
    template: '<smegSuit :data="data"/>',
});

export const Primary = Template.bind({});
Primary.args = {  };
