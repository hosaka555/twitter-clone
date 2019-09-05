<template>
  <div v-if="tweets">
    <div v-for="tweet in tweets" :key="tweet.id">
      <ShowTweet :tweet="tweet" />
    </div>
  </div>
</template>

<script>
import ShowTweet from "@/components/ShowTweet";
import { query } from "@/services/query";

export default {
  props: {
    query: Object,
    page: String
  },
  components: {
    ShowTweet
  },
  computed: {
    tweets() {
      return this.$store.getters[`tweet/${this.page}`];
    }
  },
  mounted() {
    this.getTweets();
  },
  methods: {
    getTweets() {
      const params = query.generate(this.query);
      const url = `/api/users/${this.$store.getters["user/me"].account_id}/tweets?${params}`;
      this.$store.dispatch("tweet/getTweets", { url: url, page: this.page });
    }
  }
};
</script>
