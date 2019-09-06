<template>
  <div class="profile-container">
    <h1>Porifle</h1>
    <ShowProfile :currentUser="currentUser()" :profile="profile" />

    <div v-if="currentUser()">
      <PostTweet :pageName="page" />
    </div>

    <TweetsList :query="query" :page="page" :account_id="account_id"  ref="tweets"/>
  </div>
</template>

<script>
import ShowProfile from "@/components/ShowProfile";
import TweetsList from "@/components/TweetsList";
import PostTweet from "@/components/PostTweet";
import { http } from "@/services/http";

export default {
  data() {
    return {
      account_id: "",
      query: {
        include_relations: 0
      },
      page: "profile",
      profile: {
        username: "",
        introduction: "",
        header_icon: "",
        profile_icon: ""
      }
    };
  },
  components: {
    ShowProfile,
    TweetsList,
    PostTweet
  },
  created() {
    this.getAccountId();
    this.getUserProfile();
  },
  beforeRouteUpdate(to, from, next) {
    next();
    // TODO stateを初期化したい
    this.getAccountId();
    this.getUserProfile();
    this.$refs.tweets.getTweets(this.account_id);
  },
  methods: {
    getAccountId() {
      let pattern = /users\/(.+)/;
      let targetUrl = decodeURI(window.location.pathname);
      let result = targetUrl.match(pattern);
      this.account_id = result[1];
    },
    currentUser() {
      return this.$store.getters["user/me"].account_id === this.account_id;
    },
    getUserProfile() {
      const url = `/api/users/${this.account_id}`;
      const successCB = response => {
        this.profile = response.data;

        if (this.currentUser) {
          this.$store.dispatch("profile/setProfile", {
            profile: response.data
          });
        }
      };
      const errorCB = error => {
        console.log(error);
      };

      http.get(url, successCB, errorCB);
    }
  }
};
</script>