
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import ElementUI from 'element-ui';
import locale from 'element-ui/lib/locale/lang/en'
import VueRouter from 'vue-router'
Vue.use(ElementUI, {locale})
Vue.use(VueRouter)
const routes = [
    {
        path: '/users',
        component: resolve => require(["./components/user/index.vue"], resolve),
        children: [
            {
                path: '',
                name: 'view-user',
                component: resolve => require(["./components/user/view.vue"], resolve),
            },
            {
                path: 'create',
                name: 'create-user',
                component: resolve => require(["./components/user/create.vue"], resolve),
            },
            {
                path: 'edit/:id',
                name: 'edit-user',
                component: resolve => require(["./components/user/create.vue"], resolve),
            },
        ]
    },
    {
        path: '/tags',
        component: resolve => require(["./components/tag/index.vue"], resolve),
        children: [
            {
                path: '',
                name: 'view-tag',
                component: resolve => require(["./components/tag/view.vue"], resolve),
            },
            {
                path: 'create',
                name: 'create-tag',
                component: resolve => require(["./components/tag/create.vue"], resolve),
            },
            {
                path: 'edit/:id',
                name: 'edit-tag',
                component: resolve => require(["./components/tag/create.vue"], resolve),
            },
        ]
    },
    {
        path: '/publications',
        component: resolve => require(["./components/publication/index.vue"], resolve),
        children: [
            {
                path: '',
                name: 'view-publication',
                component: resolve => require(["./components/publication/view.vue"], resolve),
            },
            {
                path: 'create',
                name: 'create-publication',
                component: resolve => require(["./components/publication/create.vue"], resolve),
            },
            {
                path: 'edit/:id',
                name: 'edit-publication',
                component: resolve => require(["./components/publication/create.vue"], resolve),
            },
        ]
    },
];
const router = new VueRouter({
    base: 'home',
    mode: 'history',
    routes
});
$(window).on('load', function () {
    new Vue({
        data(){
            return {
                validate: {
                    required: [
                        {required: true}
                    ]
                },
                store: {
                    state: {
                        user: {
                            notifications: []
                        },
                        loading: false,
                    },
                    mutations: {
                        handleLoading(state, data){
                            state.loading = data
                        },
                        handleDeleteNotification(state, data){
                            state.user.notifications.splice(data, 1)
                        }
                    },
                    dispatch(mutation, data = {}){ //$root.store.dispatch
                        this.mutations[mutation](this.state, data)
                    }
                }
            }
        },
        computed: {
            handleNotification(){
                return !!this.store.state.user.notifications.length
            }
        },
        mounted(){
            var vm = this
            axios.get('/api/users/notification').then(function (response) {
                vm.store.state.user = response.data
            })
        },
        router,
        render: h => h(require('./components/App.vue').default)
    }).$mount('#app')
});