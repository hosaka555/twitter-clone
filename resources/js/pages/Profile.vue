<template>
  <div class="profile-container">
    <h1>Porifle</h1>
    <ShowProfile
      :currentUser="currentUser()"
      :profile="profile"
      :isFollowing="profile.isFollowing"
    />

    <div v-if="currentUser()">
      <PostTweet />
    </div>

    <TweetsList :query="query" :account_id="account_id" ref="tweets" />
  </div>
</template>

<script>
import ShowProfile from "@/components/ShowProfile";
import TweetsList from "@/components/TweetsList";
import PostTweet from "@/components/PostTweet";
import { http } from "@/services/http";
const initialProfile = {
  username: "",
  introduction: "",
  header_icon: "",
  profile_icon: "",
  isFollowing: false
};
export default {
  data() {
    return {
      account_id: "",
      query: {
        include_relations: 0
      },
      profile: initialProfile
    };
  },
  components: {
    ShowProfile,
    TweetsList,
    PostTweet
  },
  created() {
    this.setPage();
    this.clearTweets();
    this.getAccountId();
    this.fetchUserProfile();
  },
  beforeRouteUpdate(to, from, next) {
    this.clearProfile();
    this.clearTweets();
    next();

    this.getAccountId();
    this.fetchUserProfile();
    this.$refs.tweets.fetchTweets(this.account_id);
  },
  methods: {
    setPage() {
      this.$store.dispatch("user/setPage", { page: "profile" });
    },
    getAccountId() {
      let pattern = /users\/(.+)/;
      let targetUrl = decodeURI(window.location.pathname);
      let result = targetUrl.match(pattern);
      this.account_id = result[1];
    },
    currentUser() {
      return this.$store.state.user.user.account_id === this.account_id;
    },
    fetchUserProfile() {
      const url = `/api/users/${this.account_id}`;
      const successCB = response => {
        this.profile = response.data;

        if (this.currentUser()) {
          this.$store.dispatch("profile/setProfile", {
            profile: response.data
          });
        }
      };
      const errorCB = error => {
        console.log(error);
      };
      http.get(url, successCB, errorCB);
    },
    clearProfile() {
      this.profile = initialProfile;
    },
    clearTweets() {
      this.$store.dispatch("tweet/clearTweets");
    }
  }
};
</script>