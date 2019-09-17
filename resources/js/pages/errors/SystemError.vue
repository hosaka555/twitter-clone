<template>
  <div>
    <h1>500 INTERNAL SERVER ERROR</h1>
    <p>ごめんなさい。サーバー側でエラーが発生しました。</p>
  </div>
</template>

<script>
export default {
  created() {
    this.setPage();
  },
  methods: {
    setPage() {
      this.$store.dispatch("user/setPage", { page: "500" });
    }
  }
};
</script>