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
      :to="{name: 'detail-tweet',params:{account_id: tweet.account_id,tweet_id: tweet.id,tweet: tweet}}"
    >
      <div class="tweet-body">
        <div class="tweet-body__message">
          <p>{{ tweet.message }}</p>
        </div>
      </div>
    </router-link>
  </div>
</template>

<script>
import ProfileIcon from "@/components/ProfileIcon";
import moment from "moment-timezone";

export default {
  props: {
    tweet: Object
  },
  data() {
    return {
      date: this.newDate()
    };
  },
  components: {
    ProfileIcon
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