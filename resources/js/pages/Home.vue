<template>
  <div>
    <h1>Home</h1>
    <TweetsList :query="query" :page="page"/>
  </div>
</template>

<script>
import TweetsList from "@/components/TweetsList";

export default {
  data: function() {
    return {
      query: {
        include_relations: 1
      },
      page: "home",
    };
  },
  components: {
    TweetsList
  }
};
</script>
