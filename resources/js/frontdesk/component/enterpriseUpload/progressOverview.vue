<template>
    <pureProgressOverview :progressOverview="progressOverview" />
</template>

<script>
import pureProgressOverview from './pureProgressOverview.vue'
export default {
    components: {
        pureProgressOverview,
    },
  computed: {
    progressOverview() {
      return this.$store.state.enterprise.progressOverview
    }
  },
}
</script>
