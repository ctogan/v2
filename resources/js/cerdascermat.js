import Vue from 'vue'

import listComponent from './components/ListCerdasCermat'

const list = new Vue({
    el:'#list-cerdas-cermat',
    render: h=>h(listComponent)
});