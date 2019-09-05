<template>
  <div>
    <h1>New Tweet</h1>
    <div class="newTweet-container">
      <ProfileIcon :user-icon="getAuthUserIcon()"/>
      <textarea class="newTweet__input" placeholder="いまどうしている？" v-model="message" />
      <div class="newTweet-bottomBox">
        <div class="bottomBox-rightSide">
          <div class="bottomBox-rightSide__count" :class="{countover: countOver }">{{ restOfCount }}</div>
          <button class="button bottomBox-rightSide__button" @click.prevent="postTweet" :disabled="countOver || message.length === 0 || isProcessing">投稿</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ProfileIcon from "./ProfileIcon";

export default {
  data() {
    return {
      message: "",
      countOver: false,
      isProcessing: false,
    };
  },
  components: {
    ProfileIcon
  },
  computed: {
    restOfCount() {
      let count = 140 - this.message.length;
      this.countOver = count < 0 ? true : false;

      return count;
    }
  },
  methods: {
    async postTweet() {
      if (!this.countOver && !this.isProcessing) {
        this.isProcessing = true;
        const url = `/api/users/${this.$store.getters["user/me"].account_id}/tweets/tweet`;
        const data = { message: this.message };
        const redirectToName = "home";

        await this.$store.dispatch("tweet/postTweet", {
          url,
          data,
          redirectToName
        });

        this.isProcessing = false;
      }
    },
    getAuthUserIcon() {
      return this.$store.getters['profile/profile_icon'];
    }
  }
};
</script>