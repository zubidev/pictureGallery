<template>
  <div>
    <div class="top-right links">
      <template v-if="authenticated">
        <router-link :to="{ name: 'home' }">
          {{ $t("home") }}
        </router-link>
      </template>
      <template v-else>
        <router-link :to="{ name: 'login' }">
          {{ $t("login") }}
        </router-link>
        <router-link :to="{ name: 'register' }">
          {{ $t("register") }}
        </router-link>
      </template>
    </div>

    <guest-gallery />
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import GuestGallery from "../components/GuestGallery.vue";

export default {
  components: { GuestGallery },

  metaInfo() {
    return { title: this.$t("home") };
  },

  data: () => ({
    title: window.config.appName,
  }),

  computed: mapGetters({
    authenticated: "auth/check",
  }),
};
</script>

<style scoped>
.top-right {
  position: absolute;
  right: 10px;
  top: 18px;
}

.title {
  font-size: 85px;
}
</style>
