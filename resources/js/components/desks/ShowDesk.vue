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
        </div>

        <form @submit.prevent="createList">
            <div class="form-group mb-1">
                <input
                    v-model="newListName"
                    type="text"
                    class="form-control"
                    placeholder="Enter List name"
                    :class="{'is-invalid': $v.newListName.$error}"
                >
                <div class="invalid-feedback" v-if="!$v.newListName.required">
                    Field required.
                </div>
                <div class="invalid-feedback" v-if="!$v.newListName.maxLength">
                    Max {{$v.desk.name.$params.maxLength.max}} symbols.
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Create List</button>
        </form>

        <div class="row">
            <div class="col-4" v-for="list in lists" :key="list.id">
                <div class="card mt-3">
                    <div class="card-body">
                        <form
                            v-if="listEditId === list.id"
                            @submit.prevent="updateDeskList(list.id, list.name)"
                            class="d-flex justify-content-between align-items-center"
                        >
                            <input
                                v-model="list.name"
                                type="text"
                                class="form-control"
                                placeholder="Enter Desk name"
                            >
                            <button type="button" class="close" aria-label="Close" @click="listEditId = null">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </form>
                        <h4
                            v-else
                            class="card-title d-flex justify-content-between align-items-center"
                            style="cursor: pointer;"
                            @click="listEditId = list.id"
                        >
                            {{ list.name }}
                            <i class="fas fa-pencil-alt" style="font-size: 15px;"></i>
                        </h4>

                        <button @click="deleteList(list.id)" type="button" class="btn btn-danger mt-3">Remove</button>
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
            newListName: null,
            error: false,
            loading: true,
            lists: [],
            listEditId: null,
        }
    },
    methods: {
        saveName() {
            this.$v.desk.name.$touch();
            if (this.$v.desk.name.$anyError) {
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
        createList(name) {
            this.$v.newListName.$touch();
            if (this.$v.newListName.$anyError) {
                return;
            }

            axios.post('/api/V1/desk-lists', {
                desk_id: this.deskId,
                name:    this.newListName,
            })
                .then(() => {
                    this.getLists();
                    this.newListName = '';
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
        updateDeskList(id, name) {
            axios.patch('/api/V1/desk-lists/' + id, {
                name,
            })
                .then(() => {
                    this.listEditId = null;
                    // this.getLists();
                })
                .catch(err => {
                    this.error = err;
                })
            ;
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
                maxLength: maxLength(50),
            }
        },
        newListName: {
            required,
            maxLength: maxLength(50),
        },
    }
}
</script>

<style scoped>

</style>
