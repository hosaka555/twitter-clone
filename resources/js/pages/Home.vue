<template>
  <div>
    <h1>Home</h1>
    <div v-if="tweets">
      <div v-for="tweet in tweets" :key="tweet.id">
        <ShowTweet :tweet="tweet" />
      </div>
    </div>
  </div>
</template>

<script>
import { http } from "@/services/http";
import { query } from "@/services/query";
import ShowTweet from "@/components/ShowTweet";

export default {
  computed: {
    tweets() {
      return this.$store.getters["tweet/home"];
    }
  },
  components: {
    ShowTweet
  },
  mounted() {
    this.getTweets();
  },
  methods: {
    getTweets() {
      const params = query.generate({ include_relations: 0 });
      const url = `/api/users/${this.$store.getters["user/me"].account_id}/tweets?${params}`;

      this.$store.dispatch("tweet/getTweets", { url: url, page: "home" });
    }
  }
};
</script>
