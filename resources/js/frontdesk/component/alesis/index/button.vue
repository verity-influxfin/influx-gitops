<template>
    <div class="button">
        <slot></slot>
    </div>
</template>

<script>
export default {
    name : "Button",
    props: {
        text: "",
    }
};
</script>

<style lang="scss" scoped>
.button {
    position: relative;
    padding: 1rem 1.5rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    line-height: 1;
    font-size: 1rem;
    background-image: url(/images/alesis-button-lines.svg);
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
    font-size: 1.1rem;
    font-weight: bold;
    color: #036eb7;
}
</style>
