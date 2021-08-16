<template>
  <div class="container">
      <h1>Desks</h1>
      <div class="row">
          <div class="col-lg-4" v-for="desk in desks" :key="desk.id">
              <div class="card mt-3">
                  <a href="#" class="card-body">
                      <h5 class="card-title">{{ desk.name }}</h5>
                  </a>
              </div>
          </div>
      </div>
      <div v-if="error" class="alert alert-danger" role="alert">
          {{ error }}
      </div>
      <div v-if="loading" class="spinner-border" style="width: 4em; height: 4em;" role="status">
      </div>
  </div>
</template>

<script>
export default {
    data() {
        return {
            desks: [],
            error: false,
            loading: true,
        }
    },
    mounted() {
        axios.get('/api/V1/desks')
            .then(resp => {
                this.desks = resp.data.data;
            })
            .catch(err => {
                this.error = err;
            })
            .finally(() => {
                this.loading = false;
            })
        ;

    }
}
</script>

<style scoped>

</style>
