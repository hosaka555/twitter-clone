<template>
  <div v-if="tweets">
    <div v-for="(tweet,index) in tweets" :key="tweet.id" class="tweet-container">
      <ShowTweet :tweet="tweet" :index="index" />
    </div>
  </div>
</template>

<script>
import ShowTweet from "@/components/ShowTweet";
import { query } from "@/services/query";

export default {
  props: {
    query: Object,
    account_id: String
  },
  components: {
    ShowTweet
  },
  computed: {
    tweets() {
      return this.$store.getters[`tweet/${this.$store.state.user.page}`];
    }
  },
  mounted() {
    this.fetchTweets();
  },
  methods: {
    fetchTweets(id = "") {
      const account_id = id || this.account_id;
      const params = query.generate(this.query);
      const url = `/api/users/${account_id}/tweets?${params}`;

      this.$store.dispatch("tweet/fetchTweets", { url: url });
    }
  }
};
</script>
