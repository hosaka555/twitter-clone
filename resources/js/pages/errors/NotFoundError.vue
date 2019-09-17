<template>
  <div>
    <h1>404 NOT FOUND ERROR</h1>
    <p>ごめんなさい。ページが見つかりません。</p>
  </div>
</template>

<script>
export default {
  created() {
    this.setPage();
  },
  methods: {
    setPage() {
      this.$store.dispatch("user/setPage", { page: "404" });
    }
  }
};
</script>