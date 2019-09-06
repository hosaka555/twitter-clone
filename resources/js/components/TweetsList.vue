<template>
  <div v-if="tweets">
    <div v-for="tweet in tweets" :key="tweet.id" class="tweet-container">
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
    page: String,
    account_id: String,
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
    getTweets(id='') {

      const account_id = id || this.account_id;
      const params = query.generate(this.query);
      const url = `/api/users/${account_id}/tweets?${params}`;

      this.$store.dispatch("tweet/getTweets", { url: url, page: this.page });
    }
  }
};
</script>
