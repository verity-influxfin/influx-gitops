import fileUploadInput from '@/component/enterpriseUpload/fileUploadInput';

export default {
    title: '企金/檔案上傳(fileUploadInput)',
    component: fileUploadInput,
};
const Template = (args, { argTypes }) => ({
    components: { fileUploadInput },
    props: Object.keys(argTypes),
    template: `<fileUploadInput v-bind="$props"/>`,
});

export const 預設 = Template.bind({})
預設.args = {}
export const 多檔案 = Template.bind({})
多檔案.args = {
    multiple:true
}
export const 僅限圖片 = Template.bind({})
僅限圖片.args = {
    accept:'image/*'
}

