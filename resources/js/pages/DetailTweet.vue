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
      <div class="detailTweet-bottom">
        <div v-if="isReady">
          <div v-if="index != null">
            <Likes :index="index" :account_id="account_id" :tweet_id="Number(tweet_id)" />
          </div>
          <div v-else>
            <Likes :tweet="tweet" :account_id="account_id" :tweet_id="Number(tweet_id)" />
          </div>
        </div>
      </div>
      <div class="tweet-bottom">
        <div v-if="tweet.image_url_lists" class="tweet-bottom--if">
          <div
            v-for="(url,index) in tweet.image_url_lists"
            :key="index"
            class="tweet-bottom__images"
          >
            <ShowImage :uri="url" :class="imageClass" />
          </div>
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
import Likes from "@/components/Likes";
import store from "@/store";
import ShowImage from "@/components/ShowImage";

export default {
  data() {
    return {
      tweet: {},
      account_id: "",
      tweet_id: "",
      date: "",
      isReady: false,
      index: null
    };
  },
  components: {
    ProfileIcon,
    BackButton,
    Likes,
    ShowImage
  },
  beforeRouteEnter(to, from, next) {
    store.dispatch("user/setPrevPage", { page: from.name });
    next();
  },
  async created() {
    this.setPage();
    this.getAccountId();
    this.getTweetId();
    await this.setTweet();
    this.newDate();
  },
  computed: {
    imageClass() {
      switch (this.tweet.image_url_lists.length) {
        case 1:
          return "tweet__image--one";
          break;
        case 2:
          return "tweet__image--two";
          break;
        case 3:
          return "tweet__image--three";
          break;
        case 4:
          return "tweet__image--four";
          break;
        default:
          return "";
          break;
      }
    }
  },
  methods: {
    setPage() {
      this.$store.dispatch("user/setPage", { page: "detail_tweet" });
    },
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
        this.index = this.$route.params.index;
        this.isReady = true;
        return;
      }

      const url = `/api/users/${this.account_id}/tweets/${this.tweet_id}`;
      const successCB = response => {
        this.tweet = JSON.parse(response.data);
      };
      const errorCB = error => {
        console.log(error);
      };

      const afterCB = () => (this.isReady = true);

      await async.get(url, successCB, errorCB, afterCB);
    },
    newDate() {
      let date = moment(new Date(this.tweet.created_at));
      date.locale("ja");
      date.tz("Asia/Tokyo");

      this.date = date.fromNow();
    },
    getIndex(tweet) {
      const page = this.$store.state.user.page;
      const index = this.$store.state[page].tweets.findIndex(
        value => value.id === tweet.id
      );

      this.index = index;
    }
  }
};
</script>