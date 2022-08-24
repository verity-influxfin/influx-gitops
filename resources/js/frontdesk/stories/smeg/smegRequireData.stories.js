import smegRequireData  from '@/component/smegRequireData';

export default {
    title: '信保專案/smegRequireData',
    component: smegRequireData,
};

const Template = (args, { argTypes }) => ({
    components: { smegRequireData },
    props: Object.keys(argTypes),
    template: '<smegRequireData :data="data"/>',
});

export const Primary = Template.bind({});
Primary.args = {  };
