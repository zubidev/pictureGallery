<template>
  <div class="row">
    <div v-if="hasdata" class="col-md-1 align-self-center">
      <button class="btn btn-primary" @click="prev()">Prev</button>
    </div>
    <div class="col-md-10">
      <table class="table">
        <tbody
          class="searchable tbody"
          style="max-height: 200px; min-height: 200px"
        >
          <template v-if="areImages">
            <tr class="tr" v-for="chunk in row">
              <td class="td" v-for="data in chunk">
                <img :src="data.thumbnailUrl" />
              </td>
            </tr>
          </template>
          <template v-else>
            <tr class="tr">
              <th class="th">Name</th>
              <th class="th">Favourites Count</th>
            </tr>
            <tr class="tr" v-for="data in paginated">
              <td class="td"><span v-text="data.name"></span></td>
              <td class="td"><span v-text="data.fav_count"></span></td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
    <div v-if="hasdata" class="col-md-1 align-self-center">
      <button class="btn btn-primary" @click="next()">Next</button>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      current: 1,
      pageSize: 20,
      rowCount: 5,
      data: [],
      favourites: [],
    };
  },
  created() {
    this.loadData();
  },
  computed: {
    indexStart() {
      return (this.current - 1) * this.pageSize;
    },
    indexEnd() {
      return this.indexStart + this.pageSize;
    },
    paginated() {
      return this.data.length
        ? this.data.slice(this.indexStart, this.indexEnd)
        : [];
    },
    row() {
      return this.hasdata ? _.chunk(this.paginated, this.rowCount) : [];
    },
    hasdata() {
      return this.data.length;
    },
    areImages() {
      return this.hasdata && this.data[0].hasOwnProperty("title");
    },
  },
  methods: {
    loadData() {
      axios.get("api/image").then(({ data }) => (this.data = data.data));
    },
    prev() {
      if (this.current === 1) return;
      this.current--;
    },
    next() {
      if (this.indexEnd > this.data.length) return;
      this.current++;
    },
  },
};
</script>
