<template>
  <div class="container">
      <h1>Desks</h1>
      <form @submit.prevent="createDesk">
          <div class="form-group mb-1">
              <input
                  v-model="newDeskName"
                  type="text"
                  class="form-control"
                  placeholder="Enter Desk name"
                  :class="{'is-invalid': $v.newDeskName.$error}"
              >
              <div class="invalid-feedback" v-if="!$v.newDeskName.required">
                  Field required.
              </div>
              <div class="invalid-feedback" v-if="!$v.newDeskName.maxLength">
                  Max {{$v.desk.name.$params.maxLength.max}} symbols.
              </div>
          </div>
          <button type="submit" class="btn btn-primary">Submit</button>
      </form>
      <div class="row">
          <div class="col-4" v-for="desk in desks" :key="desk.id">
              <div class="card mt-3">
                  <router-link :to="{name: 'showDesk', params: {deskId: desk.id}}" class="card-body">
                      <h4 class="card-title">{{ desk.name }}</h4>
                  </router-link>
                  <button @click="deleteDesk(desk.id)" type="button" class="btn btn-danger mt-3">Remove</button>
              </div>
          </div>
      </div>
      <div v-if="error" class="alert alert-danger mt-3" role="alert">
          {{ error }}
      </div>
      <div v-if="loading" class="spinner-border" style="width: 4em; height: 4em;" role="status">
      </div>
  </div>
</template>

<script>
import {required, maxLength} from 'vuelidate/lib/validators';

export default {
    data() {
        return {
            desks: [],
            error: false,
            loading: true,
            newDeskName: '',
        }
    },
    methods: {
        getDesks() {
            this.loading = true;
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
        },
        createDesk() {
            this.$v.$touch();
            if (this.$v.$anyError) {
                return;
            }

            axios.post('/api/V1/desks', {
                name: this.newDeskName,
            })
                .then(() => {
                    this.getDesks();
                    this.newDeskName = '';
                })
                .catch(err => {
                    if (err.response.data.errors.name) {
                        this.error = err.response.data.errors.name[0];
                    }
                    else {
                        this.error = err;
                    }
                })
            ;
        },
        deleteDesk(id) {
            if(confirm('Are you high?')) {
                axios.delete('/api/V1/desks/' + id)
                    .then(() => this.getDesks())
                ;
            }
        },
    },
    mounted() {
        this.getDesks();
    },
    validations: {
        newDeskName: {
            required,
            maxLength: maxLength(50),
        }
    }
}
</script>

<style scoped>

</style>
