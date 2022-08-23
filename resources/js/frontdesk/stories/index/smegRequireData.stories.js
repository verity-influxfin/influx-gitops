import smegRequireData  from '@/component/smegRequireData';

export default {
    title: 'Components/smegRequireData',
    component: smegRequireData,
};

const Template = (args, { argTypes }) => ({
    components: { smegRequireData },
    props: Object.keys(argTypes),
    template: '<smegRequireData :data="data"/>',
});

export const Primary = Template.bind({});
Primary.args = {  };
