<template>
  <div class="row">
    <div class="col-lg-10 m-auto">
      <gallery />
    </div>
  </div>
</template>

<script>
import Gallery from "~/components/Gallery";
export default {
  middleware: "auth",

  components: {
    Gallery,
  },

  metaInfo() {
    return { title: this.$t("home") };
  },
};
</script>
