<template>
    <div class="container">
        <div v-if="loading" class="spinner-border" style="width: 4em; height: 4em;" role="status">
        </div>
        <div v-if="error" class="alert alert-danger" role="alert">
            {{ error }}
        </div>
        <div class="form-group" v-if="desk">
            <h1>{{desk.name}}</h1>
            <input
                @blur="saveName"
                v-model="desk.name"
                type="text"
                class="form-control"
                :class="{'is-invalid': $v.desk.name.$error}"
            >
            <div class="invalid-feedback" v-if="!$v.desk.name.required">
                Field required.
            </div>
            <div class="invalid-feedback" v-if="!$v.desk.name.maxLength">
                Max {{$v.desk.name.$params.maxLength.max}} symbols.
            </div>

            <div class="row">
                <div class="col-4" v-for="list in lists" :key="list.id">
                    <div class="card mt-3">
                        <div class="card-body">
                            <h4 class="card-title">{{ list.name }}</h4>
                            <button @click="deleteList(list.id)" type="button" class="btn btn-danger mt-3">Remove</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {required, maxLength} from 'vuelidate/lib/validators';

export default {
    props: ['deskId'],
    data() {
        return {
            desk: null,
            error: false,
            loading: true,
            lists: [],
        }
    },
    methods: {
        saveName() {
            this.$v.$touch();
            if (this.$v.$anyError) {
                return;
            }

            axios.patch('/api/V1/desks/' + this.deskId, {
                name: this.desk.name,
            })
        },
        getDesk() {
            axios.get('/api/V1/desks/' + this.deskId)
                .then(resp => {
                    this.desk = resp.data.data;
                })
                .catch(err => {
                    this.error = err;
                })
                .finally(() => {
                    this.loading = false;
                })
            ;
        },
        getLists() {
            this.loading = true;
            axios.get('/api/V1/desk-lists', {
                params: {
                    desk_id: this.deskId,
                }
            })
                .then(resp => {
                    this.lists = resp.data.data;
                })
                .catch(err => {
                    this.error = err;
                })
                .finally(() => {
                    this.loading = false;
                })
            ;
        },
        deleteList(id) {
            if(confirm('Are you high?')) {
                axios.delete('/api/V1/desk-lists/' + id)
                    .then(() => this.getLists())
                ;
            }
        },
    },
    mounted() {
        this.getDesk();
        this.getLists();
    },
    validations: {
        desk: {
            name: {
                required,
                maxLength: maxLength(10),
            }
        }
    }
}
</script>

<style scoped>

</style>
