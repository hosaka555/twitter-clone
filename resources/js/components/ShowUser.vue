<template>
  <router-link :to="{name: 'profile', params: { account_id: user.account_id} }">
    <div class="showUser-container">
      <ProfileIcon class="showUser-container__profileIcon" :user-icon="user.profile_icon" />
      <div class="showUser-head">
        <span class="showUser__text">{{user.profile.username}}</span>
        <span class="showUser__text">{{user.account_id}}</span>
      </div>
      <div class="showUser-body">{{user.profile.introduction}}</div>
    </div>
  </router-link>
</template>

<script>
import ProfileIcon from "./ProfileIcon";
export default {
  props: {
    user: {
      type: Object
    }
  },
  components: {
    ProfileIcon
  }
};
</script>