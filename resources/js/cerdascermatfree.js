import Vue from 'vue'
import VueAlertify from "vue-alertify"

Vue.use(VueAlertify,{
    title:'Cashtree Cerdas Cermat',
    closableByDimmer: true,
    autoReset: true,
    closable:false,
    transition:'zoom'
});

import listComponent from './components/CerdasCermatFree'

const list = new Vue({
    el:'#cerdas-cermat-free',
    render: h=>h(listComponent)
});