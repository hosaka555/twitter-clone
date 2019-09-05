<template>
<div>
  <h1>ツイートの新規作成</h1>
  <PostTweet />
</div>
</template>

<script>
import PostTweet from "@/components/PostTweet";

export default {
 components: {
    PostTweet,
  }
}
</script>