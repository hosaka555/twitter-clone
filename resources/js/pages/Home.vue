<template>
  <div>
    <h1>Home</h1>
    <PostTweet />
    <TweetsList :query="query" :account_id="account_id" />
  </div>
</template>

<script>
import TweetsList from "@/components/TweetsList";
import PostTweet from "@/components/PostTweet";

export default {
  data() {
    return {
      query: {
        include_relations: 1
      },
      account_id: ""
    };
  },
  components: {
    TweetsList,
    PostTweet
  },
  created() {
    this.setPage();
    this.setAccountId();
  },
  methods: {
    setPage() {
      this.$store.dispatch("user/setPage", { page: "home" });
    },
    setAccountId() {
      this.account_id = this.$store.state.user.user.account_id;
    }
  }
};
</script>
