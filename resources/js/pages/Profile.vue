<template>
  <div>
    <h1>Porifle</h1>
    <ShowProfile />
    <TweetsList :query="query" :page="profile"/>
  </div>
</template>

<script>
import ShowProfile from '@/components/ShowProfile';
import TweetsList from '@/components/TweetsList';

export default {
  data: function(){
    return {
      query: {
        include_relations: 0,
      },
      profile: "profile"
    }
  },
  components: {
    ShowProfile,
    TweetsList,
  },
};
</script>