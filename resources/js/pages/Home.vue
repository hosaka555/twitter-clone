<template>
  <div>
    <h1>Home</h1>
    <PostTweet :pageName="page" />
    <TweetsList :query="query" :page="page" :account_id="account_id" />
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
      page: "home",
      account_id: ""
    };
  },
  components: {
    TweetsList,
    PostTweet,
  },
  created() {
    this.setAccountId();
  },
  methods: {
    setAccountId() {
      this.account_id = this.$store.getters["user/me"].account_id;
    }
  }
};
</script>
