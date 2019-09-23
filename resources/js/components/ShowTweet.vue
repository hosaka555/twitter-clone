<template>
  <div>
    <div>
      <router-link :to="{ name: 'profile', params: { account_id: tweet.account_id} }">
        <ProfileIcon class="tweet-container__profileIcon" :user-icon="tweet['profile_icon']" />
      </router-link>
      <div class="tweet-head">
        <router-link :to="{ name: 'profile', params: { account_id: tweet.account_id} }">
          <span>{{tweet.username}}</span>
          <span>{{tweet.account_id}}</span>
        </router-link>

        <span>{{ date }}</span>
      </div>
    </div>
    <router-link
      :to="{name: 'detail-tweet',params:{account_id: tweet.account_id,tweet_id: tweet.id,tweet: tweet, index: index}}"
    >
      <div class="tweet-body">
        <div class="tweet-body__message">
          <p>{{ tweet.message }}</p>
        </div>
      </div>
    </router-link>
    <div class="tweet-bottom">
      <div class="tweet-bottom__likes">
        <Likes :index="index" :account_id="tweet.account_id" :tweet_id="tweet.id" />
      </div>

      <div v-if="tweet.image_url_lists.length" class="tweet-bottom--if">
        <div v-for="(url,index) in tweet.image_url_lists" :key="index" class="tweet-bottom__images">
          <ShowImage :uri="url" :class="imageClass" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ProfileIcon from "@/components/ProfileIcon";
import moment from "moment-timezone";
import Likes from "@/components/Likes";
import ShowImage from "@/components/ShowImage";

export default {
  props: {
    tweet: Object,
    index: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      date: this.newDate()
    };
  },
  components: {
    ProfileIcon,
    Likes,
    ShowImage
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
    newDate() {
      let date = moment(new Date(this.tweet.created_at));
      date.locale("ja");
      date.tz("Asia/Tokyo");

      return date.fromNow();
    }
  }
};
</script>