<template>
  <div class="profileBox">
    <div v-if="profile['header_icon']">
      <img :src="profile['header_icon']" class="profile__headerIcon" />
      <ProfileIcon :user-icon="profile['profile_icon']" class="profile__profileIcon" />

      <div class="profile-aciton">
        <div v-if="currentUser">
          <router-link
            :to="{name: 'edit-profile',params: { account_id: this.$store.state.user.user.account_id} }"
          >
            <button>編集</button>
          </router-link>
        </div>
        <div v-else>
          <FollowButton :isFollowing="profile.isFollowing" :account_id="profile.account_id" />
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
import FollowButton from "./FollowButton";

export default {
  props: {
    currentUser: {
      type: Boolean,
      default: false
    },
    profile: {
      type: Object
    }
  },
  components: {
    ProfileIcon,
    FollowButton
  }
};
</script>