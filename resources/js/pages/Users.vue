<template>
  <div v-if="users && followees_ids" class="users-container">
    <div v-for="user in users" :key="user.id">
      <ShowUser :user="user" :isFollowing="!!followees_ids.includes(user.id)" />
    </div>
  </div>
</template>

<script>
import { http } from "@/services/http";
import ShowUser from "@/components/ShowUser";

export default {
  data() {
    return {
      users: [],
      followees_ids: []
    };
  },
  components: {
    ShowUser
  },
  created() {
    this.fetchUsers();
    this.fetchFollowees();
  },
  methods: {
    fetchUsers() {
      const url = `/api/users`;
      const successCB = response => {
        this.users = JSON.parse(response.data);
      };

      const errorCB = error => {
        console.log(error);
      };

      http.get(url, successCB, errorCB);
    },
    fetchFollowees() {
      const url = `/api/users/${this.$store.state.user.user.account_id}/followees`;

      const successCB = response => {
        this.followees_ids = response.data;
      };

      const errorCB = error => {
        console.log(error);
      };

      http.get(url, successCB, errorCB);
    }
  }
};
</script>