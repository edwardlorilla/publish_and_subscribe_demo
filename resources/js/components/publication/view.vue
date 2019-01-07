<template>
    <div>
        <grid-view :columns="columns"
                   :data="data"
                   create-name="Add Publication"
                   on-delete="/api/publications"
                   on-edit-name="edit-publication"
                   on-create-name="create-publication"
                   @delete="data.data.splice($event, 1)"
                   ></grid-view>
        </div>
    </template>
<style>
    </style>
<script>
    import GridView from './../Table/Grid.vue'
    export default{
        data(){
            return {
                columns: [
                    {
                        label: 'User',
                        prop: 'user.name',
                        sort: true
                    }, {
                        label: 'Title',
                        prop: 'title',
                        sort: true
                    }, {
                        label: 'Body',
                        prop: 'body',
                        sort: true
                    },
                ],
                data: []
            }
        },
        components: {
            GridView,
        },
        beforeRouteEnter (to, from, next) {
            axios.get(`/api/publications`, {params: to.query}).then(function (response) {
                next(vm => vm.setData(response.data))
            })
        },
        beforeRouteUpdate (to, from, next) {
            var vm = this
            axios.get(`/api/publications`, {params: to.query}).then(function (response) {
                vm.setData(response.data)
                next()
            })
        },
        methods: {
            setData(response){
                this.data = response
            },
        }
    }
    </script>