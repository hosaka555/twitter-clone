<template>
  <div>
    <div v-if="index != null">
      <span>{{count}}</span>
      <button v-if="isLiked" @click.prevent="unlike()" :disabled="isProcessing">
        <font-awesome-icon :icon="['fas','heart']" />
      </button>
      <button v-else @click.prevent="like()" :disabled="isProcessing">
        <font-awesome-icon :icon="['far','heart']" />
      </button>
    </div>
    <div v-else>
      <span>{{count}}</span>
      <button v-if="isLiked" @click.prevent="unlike(hasStore = false)" :disabled="processing">
        <font-awesome-icon :icon="['fas','heart']" />
      </button>
      <button v-else @click.prevent="like(hasStore = false)" :disabled="processing">
        <font-awesome-icon :icon="['far','heart']" />
      </button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    index: {
      type: Number,
      required: false
    },
    account_id: {
      type: String,
      required: true
    },
    tweet_id: {
      type: Number,
      required: true
    },
    tweet: {
      type: Object,
      required: false
    }
  },
  data() {
    return {
      liked: null,
      likes_count: null,
      processing: false
    };
  },
  created() {
    if (this.tweet != undefined) {
      this.setData();
    }
  },
  computed: {
    isLiked() {
      let liked = "";
      if (this.liked != null) {
        liked = this.liked;
      } else {
        liked = this.$store.state.tweet[this.page][this.index].is_liked;
      }
      return liked;
    },
    count() {
      let count = "";

      if (this.likes_count !== null) {
        count = this.likes_count;
      } else {
        count = this.$store.state.tweet[this.page][this.index].likes_count;
      }
      return count;
    },
    isProcessing() {
      return this.$store.state.tweet[this.page][this.index].isLikingProcessing;
    },
    page() {
      let page = this.$store.state.user.page;
      if (!["home", "profile"].includes(page)) {
        page = this.$store.getters["user/prevPage"] || "home";
      }
      return page;
    }
  },
  methods: {
    setData() {
      this.liked = this.tweet.is_liked;
      this.likes_count = this.tweet.likes_count;
      this.processing = false;
    },
    like(hasStore = true) {
      if (this.processing || this.isLikingProcessing) return;

      const account_id = this.account_id;
      const tweet_id = this.tweet_id;
      const url = `/api/users/${account_id}/tweets/${tweet_id}/like`;
      if (hasStore) {
        this.$store.dispatch("tweet/startLikingProcess", {
          index: this.index
        });

        var successCB = () =>
          this.$store.dispatch("tweet/likeTweet", { id: tweet_id });

        var afterCB = () =>
          this.$store.dispatch("tweet/doneLikingProcess", {
            index: this.index
          });
      } else {
        this.processing = true;

        var successCB = () => {
          this.liked = true;
          this.likes_count += 1;
        };

        var afterCB = () => (this.processing = false);
      }

      axios
        .put(url)
        .then(successCB)
        .catch(error => {
          console.log(error);
        })
        .then(afterCB);
    },
    unlike(hasStore = true) {
      if (this.processing || this.processing) return;
      const account_id = this.account_id;
      const tweet_id = this.tweet_id;
      const url = `/api/users/${account_id}/tweets/${tweet_id}/unlike`;
      if (hasStore) {
        this.$store.dispatch("tweet/startLikingProcess", {
          index: this.index
        });

        var successCB = () =>
          this.$store.dispatch("tweet/unlikeTweet", { id: tweet_id });

        var afterCB = () =>
          this.$store.dispatch("tweet/doneLikingProcess", {
            index: this.index
          });
      } else {
        this.processing = true;

        var successCB = () => {
          this.liked = false;
          this.likes_count -= 1;
        };

        var afterCB = () => (this.processing = false);
      }

      axios
        .delete(url)
        .then(successCB)
        .catch(error => {
          console.log(error);
        })
        .then(afterCB);
    }
  }
};
</script>