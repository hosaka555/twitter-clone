<template>
  <div>
    <Header />
    <main>
      <div class="container">
        <RouterView />
      </div>
    </main>
  </div>
</template>

<script>
import Header from "@/components/Header";

export default {
  components: {
    Header
  },
  mounted() {
    this.setToken();
  },
  methods: {
    async setToken() {
      const token = document
        .getElementById("token")
        .textContent.replace(/^\s*/, ""); // index.blade.phpからjwt-tokenを取得

      await this.$store.dispatch("user/setToken", { token: token });
      await this.$store.dispatch("user/me");
    }
  }
};
</script>