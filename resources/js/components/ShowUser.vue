<template>
  <router-link :to="{name: 'profile', params: { account_id: user.account_id} }">
    <div class="showUser-container">
      <ProfileIcon class="showUser-container__profileIcon" :user-icon="user.profile_icon" />
      <div class="showUser-head">
        <span class="showUser__text">{{user.profile.username}}</span>
        <span class="showUser__text">{{user.account_id}}</span>
        <FollowButton :isFollowing="isFollowing" :account_id='user.account_id' />
      </div>

      <div class="showUser-body">{{user.profile.introduction}}</div>
    </div>
  </router-link>
</template>

<script>
import ProfileIcon from "./ProfileIcon";
import FollowButton from "./FollowButton";
import { http } from "@/services/http";

export default {
  props: {
    user: {
      type: Object
    },
    isFollowing: {
      type: Boolean,
      default: false
    }
  },
  components: {
    ProfileIcon,
    FollowButton
  }
};
</script>