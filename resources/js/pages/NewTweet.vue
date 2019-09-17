<template>
  <div>
    <h1>ツイートの新規作成</h1>
    <PostTweet :isRedirect="true" />
  </div>
</template>

<script>
import PostTweet from "@/components/PostTweet";

export default {
  components: {
    PostTweet
  },
  created() {
    this.setPage();
  },
  methods: {
    setPage() {
      this.$store.dispatch("user/setPage", { page: "new_tweet" });
    }
  }
};
</script>