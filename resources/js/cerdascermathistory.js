import Vue from 'vue'
import VueAlertify from "vue-alertify"

Vue.use(VueAlertify,{
    title:'Cashtree Cerdas Cermat',
    closableByDimmer: true,
    autoReset: true,
    closable:false,
    transition:'zoom'
});

import listComponent from './components/HistoryCerdasCermat'

const list = new Vue({
    el:'#list-cerdas-cermat-history',
    render: h=>h(listComponent)
});