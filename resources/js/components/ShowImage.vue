<template>
  <img :src="uri" class="tweet__image"/>
</template>


<script>
export default {
  props: {
    uri: {
      type: String,
      required: true
    }
  }
};
</script>