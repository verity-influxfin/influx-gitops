<template>
    <news v-if="article_type=='news'" />
    <knowledge v-else-if="article_type=='knowledge'" />
</template>

<script>
import news from './article/news';
import knowledge from './article/knowledge';

export default {
  components: {
    news,
    knowledge,
  },
  computed: {
    article_type() {
        const q = this.$route.query['q'];
        if (q) {
            return q.substr(0, q.indexOf('-')).toLowerCase();
        }
        return '';
    }
  }
};
</script>