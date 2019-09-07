<template>
  <div>
    <BackButton />
    <div class="detailTweet-container">
      <div>
        <router-link :to="{ name: 'profile', params: { account_id: tweet.account_id} }">
          <ProfileIcon
            class="detailTweet-container__profileIcon"
            :user-icon="tweet['profile_icon']"
          />
        </router-link>
        <div class="detailTweet-head">
          <router-link :to="{ name: 'profile', params: { account_id: account_id} }">
            <span>{{tweet.username}}</span>
            <span>{{tweet.account_id}}</span>
          </router-link>

          <span>{{ date }}</span>
        </div>
      </div>
      <div class="detailTweet-body">
        <div class="detailTweet-body__message">
          <p>{{ tweet.message }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { async } from "@/services/http";
import ProfileIcon from "@/components/ProfileIcon";
import moment from "moment-timezone";
import BackButton from "@/components/BackButton";

export default {
  data() {
    return {
      tweet: {},
      account_id: "",
      tweet_id: "",
      date: ""
    };
  },
  components: {
    ProfileIcon,
    BackButton
  },
  async created() {
    this.getAccountId();
    this.getTweetId();
    await this.setTweet();
    this.newDate();
  },
  methods: {
    getAccountId() {
      let pattern = /users\/(.+?)\//;
      let targetUrl = decodeURI(window.location.pathname);
      let result = targetUrl.match(pattern);
      this.account_id = result[1];
    },
    getTweetId() {
      let pattern = /users\/.+\/tweets\/(\d+)/;
      let targetUrl = decodeURI(window.location.pathname);
      let result = targetUrl.match(pattern);
      this.tweet_id = result[1];
    },
    async setTweet() {
      if (this.$route.params.tweet) {
        this.tweet = this.$route.params.tweet;
        return;
      }

      const url = `/api/users/${this.account_id}/tweets/${this.tweet_id}`;
      const successCB = response => {
        this.tweet = JSON.parse(response.data);
      };
      const errorCB = error => {
        console.log(error);
      };

      await async.get(url, successCB, errorCB);
    },
    newDate() {
      let date = moment(new Date(this.tweet.created_at));
      date.locale("ja");
      date.tz("Asia/Tokyo");

      this.date = date.fromNow();
    }
  }
};
</script>