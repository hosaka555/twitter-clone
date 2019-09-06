<template>
  <div>
    <div>
      <ProfileIcon class="tweet-container__profileIcon" :user-icon="tweet['profile_icon']" />
      <div class="tweet-head">
        <span>{{tweet.username}}</span>
        <span>{{tweet.account_id}}</span>
        <span>{{ date }}</span>
      </div>
    </div>
    <div class="tweet-body">
      <div class="tweet-body__message">
        <p>{{ tweet.message }}</p>
      </div>
    </div>
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