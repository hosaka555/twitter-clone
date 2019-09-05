<template>
  <div class="profile-container">
    <div v-if="profile['header_icon']">
      <img :src="profile['header_icon']" class="profile__headerIcon" />
      <ProfileIcon :user-icon="profile['profile_icon']" class="profile__profileIcon" />

      <div class="profile-aciton">
        <div v-if="currentUser">
          <router-link
            :to="{name: 'edit-profile',params: { account_id: this.$store.getters['user/me'].account_id} }"
          >
            <button>編集</button>
          </router-link>
        </div>
        <div v-else>
          <button>Follow</button>
        </div>
      </div>
    </div>
    <div v-else>
      <div>
        <div class="profile__headerIcon"></div>
        <div class="icon profile__profileIcon"></div>
      </div>
    </div>

    <p class="profile__text-username">{{ profile.username }}</p>
    <p class="profile__text-account_id">{{ profile.account_id }}</p>
    <p class="profile__text-introduction">{{ profile.introduction }}</p>
  </div>
</template>

<script>
import ProfileIcon from "@/components/ProfileIcon";
import { http } from "@/services/http";
export default {
  components: {
    ProfileIcon
  },
  data() {
    return {
      profile: {
        username: "",
        introduction: "",
        header_icon: "",
        profile_icon: "",
        account_id: ""
      }
    };
  },
  mounted() {
    this.getUserProfile();
  },
  methods: {
    getUserProfile() {
      const url = `/api/users/${this.$store.getters["user/me"].account_id}`;
      const successCB = response => {
        this.profile = response.data;

        if (this.currentUser) {
          this.$store.dispatch("profile/setProfile", {
            profile: response.data
          });
        }
      };
      const errorCB = error => {
        console.log(error);
      };

      http.get(url, successCB, errorCB);
    },
    currentUser() {
      return (
        this.$store.getters["user/me"].account_id === this.profile.account_id
      );
    }
  }
};
</script>