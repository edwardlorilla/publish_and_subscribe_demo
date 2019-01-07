<template>
    <div>
        <el-row>
            <el-col :span="24">
                <el-card shadow="never">
                    <div slot="header">
                        <span>{{$route.params.id ? 'Edit' : 'Create'}} Publication</span>
                        </div>
                    <el-form ref="form" @submit.native.prevent="onSubmit" :model="form" label-width="120px">
                        <el-form-item  :class="errors.user_id ? 'is-error is-required' : ''"  prop="users"  label="User">
                            <el-select
                                v-model="form.user_id"
                                filterable
                                remote
                                reserve-keyword
                                placeholder="Please enter a keyword"
                                :remote-method="search_user"
                                :loading="loading">
                                <el-option
                                    v-for="item in users"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                                    </el-option>
                                </el-select>
                            <div v-if="errors.user_id" v-for="error in errors.user_id" class="el-form-item__error">
                                {{error}}
                                </div>
                            </el-form-item>
                        <el-form-item label="Title" :class="errors.title ? 'is-error is-required' : ''" prop="title">
                            <el-input placeholder="Enter Name" type="text" v-model="form.title"
                                      auto-complete="off"></el-input>
                            <div v-if="errors.title" v-for="error in errors.title" class="el-form-item__error">
                                {{error}}
                                </div>
                            </el-form-item>
                        <el-form-item label="Body" :class="errors.body ? 'is-error is-required' : ''" prop="body">
                            <el-input placeholder="Enter Name" type="text" v-model="form.body"
                                      auto-complete="off"></el-input>
                            <div v-if="errors.body" v-for="error in errors.body" class="el-form-item__error">
                                {{error}}
                                </div>
                            </el-form-item>
                        <el-form-item  prop="tags"  label="Tags">
                            <el-select
                                v-model="form.tags"
                                multiple
                                filterable
                                remote
                                reserve-keyword
                                placeholder="Please enter a keyword"
                                :remote-method="search_tag"
                                :loading="loading">
                                <el-option
                                    v-for="item in tags"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                        <el-form-item>
                            <el-button :disabled="disabled" type="primary" @click="onSubmit('form')">{{$route.params.id
                                ? 'Edit' : 'Create'}} Publication
                                </el-button>
                            <el-button @click="onCancel">Cancel</el-button>
                            </el-form-item>
                        </el-form>
                    </el-card>

                </el-col>
            </el-row>
        </div>
    </template>
<script>
    import tag from '../mixins/search/tag'
    import user from '../mixins/search/user'
    export default {
        mixins: [tag, user],
        data() {
            return {
                form: {
                    user_id: '',
                    tags: [],
                    title: '',
                    body: '',
                },
                errors: {},
                loading: false,
                disabled: false
            }
        },
        beforeRouteEnter (to, from, next) {
            if (to.params.id) {
                axios.get(`/api/publications/${to.params.id}`).then(function (response) {
                    next(vm => vm.setData(response.data))
                }).catch(function (error) {
                    if (error.response.statusText) {
                        next(vm => vm.$message({
                            type: 'error',
                            message: error.response.statusText
                        }))
                    }
                })
            } else {
                next()
            }
        },
        beforeRouteUpdate (to, from, next) {
            var vm = this
            if (to.params.id) {
                axios.get(`/api/publications/${to.params.id}`).then(function (response) {
                    vm.setData(response.data)
                    next()
                }).catch(function (error) {
                    if (error.response.statusText) {
                        vm.$message({
                            type: 'error',
                            message: error.response.statusText
                        })
                    }
                })
            } else {
                next()
            }
        },
        methods: {
            setData(response){
                var vm = this
                vm.tags = response.tags.map(item => {
                    return {value: item.id, label: item.details};
                })
                vm.form = response;
                vm.form.tags = _.map(response.tags, 'id')
                vm.form.users = [{value: response.user.id, label: response.user.name}]
            },
            onCancel(){
                this.$router.push({name: 'view-publication'})
            },
            onSubmit() {
                var vm = this
                this.$refs.form.validate((valid) => {
                    if (valid) {
                        vm.disabled = true
                        vm.errors = {}
                        let id = vm.$route.params.id;
                        axios[id ? 'put' : 'post'](`/api/publications${id ? `/${id}` : ''}`, vm.form).then(function () {
                            vm.disabled = false
                            vm.$message({
                                type: 'success',
                                message: 'Publication has been created'
                            })
                        }).catch(function (error) {
                            vm.disabled = false
                            if (error.response.data.errors && error.response.data.message) {
                                vm.errors = error.response.data.errors;
                                vm.$message({message: error.response.data.message, type: 'error'})
                            }
                        })
                    }
                })
            }
        }
    }
    </script>