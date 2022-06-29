<template>
 <pure-case-overview :case-overview="caseOverview" />
</template>

<script>
import pureProgressOverview from './pureProgressOverview'
export default {
    components: {
        pureProgressOverview
    },
  computed: {
    caseOverview() {
      return this.$store.state.enterprise.caseOverview
    }
  },
}
</script>
