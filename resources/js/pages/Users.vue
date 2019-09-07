<template>
  <div v-if="users" class="users-container">
    <div v-for="user in users" :key="user.id">
      <ShowUser :user="user" />
    </div>
  </div>
</template>

<script>
import { http } from "@/services/http"
import ShowUser from "@/components/ShowUser";

export default {
  data(){
    return {
      users: [],
    }
  },
  components: {
    ShowUser,
  },
  created() {
    this.getUsers();
  },
  methods: {
    getUsers() {
      const url = `/api/users`;
      const successCB = response => {
        this.users = JSON.parse(response.data);
      };

      const errorCB = error => {
        console.log(error);
      };

      http.get(url, successCB, errorCB);
    }
  }
};
</script>