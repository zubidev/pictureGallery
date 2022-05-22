<template>
  <div class="row">
    <div v-if="hasImages" class="col-md-1 align-self-center">
      <button class="btn btn-primary" @click="prev()">Prev</button>
    </div>
    <div class="col-md-10">
      <div v-for="chunk in row">
        <div class="row">
          <div class="col" v-for="image in chunk">
            <img :src="image.thumbnailUrl" />
            <i
              :data-id="image.id"
              @click="favourite"
              v-bind:class="getClass(image.id)"
            ></i>
          </div>
        </div>
      </div>
    </div>
    <div v-if="hasImages" class="col-md-1 align-self-center">
      <button class="btn btn-primary" @click="next()">Next</button>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Button from "./Button.vue";
export default {
  components: { Button },
  middleware: "auth",

  data() {
    return {
      current: 1,
      pageSize: 25,
      rowCount: 5,
      images: [],
      favourites: [],
      authenticated: false,
    };
  },
  created() {
    this.loadUserFavourites();
    this.loadimages();
  },
  computed: {
    indexStart() {
      return (this.current - 1) * this.pageSize;
    },
    indexEnd() {
      return this.indexStart + this.pageSize;
    },
    paginated() {
      return this.hasImages
        ? this.images.slice(this.indexStart, this.indexEnd)
        : [];
    },
    row() {
      return this.hasImages ? _.chunk(this.paginated, this.rowCount) : [];
    },
    hasImages() {
      return this.images.length;
    },
  },
  methods: {
    async loadimages() {
      await axios
        .get("api/image")
        .then(({ data }) => (this.images = data.data));
    },
    async loadUserFavourites() {
      await axios
        .get("api/user/favourites")
        .then(({ data }) => (this.favourites = data.data));
    },
    favouriteImage(imageId) {
      axios
        .put("api/user/" + imageId, { isFav: true })
        .then(this.loadUserFavourites());
    },
    unfavouriteImage(imageId) {
      axios
        .put("api/user/" + imageId, { isFav: false })
        .then(this.loadUserFavourites());
    },
    favourite(event) {
      const element = event.target;
      if (element.classList.contains("fa-heart")) {
        element.classList.remove("fa-heart");
        element.classList.add("fa-heart-o");
        this.unfavouriteImage(element.dataset.id);
      } else {
        element.classList.remove("fa-heart-o");
        element.classList.add("fa-heart");
        this.favouriteImage(element.dataset.id);
      }
    },
    getClass(imageId) {
      const parsedFavourites = JSON.parse(JSON.stringify(this.favourites));
      return parsedFavourites.hasOwnProperty(imageId)
        ? "fa fa-heart"
        : "fa fa-heart-o";
    },
    prev() {
      if (this.current === 1) return;
      this.current--;
      this.loadUserFavourites();
    },
    next() {
      if (this.indexEnd > this.images.length) return;
      this.current++;
      this.loadUserFavourites();
    },
  },

  metaInfo() {
    return { title: this.$t("home") };
  },
};
</script>
