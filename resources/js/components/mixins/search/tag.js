export default {
    data(){
        return {
            tags: []
        }
    },
    methods: {
        search_tag(query){
            var vm = this
            if (query !== '') {
                vm.loading = true
                vm.onSearchTag(query, vm)
            } else {
                vm.loading = false
                vm.tags = []
            }
        },
        onSearchTag: _.debounce(function (query, vm) {

            axios.get('/api/tags/search?search=' + query).then(function (q) {
                vm.loading = false
                vm.tags = q.data.map(item => {
                    return {value: item.id, label: item.details};
                })
            }).catch(function () {
                vm.loading = false
            })
        }, 350),
    }

}