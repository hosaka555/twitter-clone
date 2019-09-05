<template>
  <img :src="userIcon" class="icon profile-icon" />
</template>

<script>
export default {
  props: {
    userIcon: String
  }
}
</script>