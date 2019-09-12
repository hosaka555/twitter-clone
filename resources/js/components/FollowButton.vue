<template>
  <div>
    <div v-if="following">
      <button @click.prevent="unfollow" :disabled="processing" class="unfollow">アンフォロー</button>
    </div>
    <div v-else>
      <button @click.prevent="follow" :disabled="processing">フォロー</button>
    </div>
  </div>
</template>

<script>
import { http } from "@/services/http";

export default {
  props: {
    isFollowing: false,
    account_id: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      processing: false,
      following: false
    };
  },
  created() {
    this.setFollowCondition();
  },
  methods: {
    setFollowCondition() {
      this.following = this.isFollowing;
    },
    follow() {
      if (!this.processing) {
        this.processing = true;
        const account_id = this.account_id;
        const url = `/api/users/${account_id}/follow`;

        axios
          .put(url)
          .then(response => {
            this.following = true;
          })
          .catch(error => {
            console.log(error);
          })
          .then(() => (this.processing = false));
      }
    },
    unfollow() {
      if (!this.processing) {
        this.processing = true;
        const account_id = this.account_id;
        const url = `/api/users/${account_id}/unfollow`;

        axios
          .delete(url)
          .then(response => {
            this.following = false;
          })
          .catch(error => {
            console.log(error);
          })
          .then(() => (this.processing = false));
      }
    }
  }
};
</script>