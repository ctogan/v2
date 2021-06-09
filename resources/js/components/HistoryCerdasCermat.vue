<template>
    <div class="container cerdas-cermat">

        <div v-if="is_loading">
            <div class="mb-4">
                <div class="d-flex justify-content-between">
                    <div class="h-25 w-50 bg-placeholder mb-2 flex-1 mr-5"></div>
                    <div class="h-25 w-10 bg-placeholder mb-2 mr-1"></div>
                    <div class="h-25 w-10 bg-placeholder mb-2 mr-1"></div>
                    <div class="h-25 w-10 bg-placeholder mb-2"></div>
                </div>
                <div class="h-10 w-50 bg-placeholder mb-3"></div>
                <div class="d-flex justify-content-between">
                    <div class="h-25 w-25 bg-placeholder mb-3"></div>
                    <div class="h-25 w-25 bg-placeholder mb-3"></div>
                </div>
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between">
                    <div class="h-25 w-50 bg-placeholder mb-2 flex-1 mr-5"></div>
                    <div class="h-25 w-10 bg-placeholder mb-2 mr-1"></div>
                    <div class="h-25 w-10 bg-placeholder mb-2 mr-1"></div>
                    <div class="h-25 w-10 bg-placeholder mb-2"></div>
                </div>
                <div class="h-10 w-50 bg-placeholder mb-3"></div>
                <div class="d-flex justify-content-between">
                    <div class="h-25 w-25 bg-placeholder mb-3"></div>
                    <div class="h-25 w-25 bg-placeholder mb-3"></div>
                </div>
            </div>

            <div>
                <div class="d-flex justify-content-between">
                    <div class="h-25 w-50 bg-placeholder mb-2 flex-1 mr-5"></div>
                    <div class="h-25 w-10 bg-placeholder mb-2 mr-1"></div>
                    <div class="h-25 w-10 bg-placeholder mb-2 mr-1"></div>
                    <div class="h-25 w-10 bg-placeholder mb-2"></div>
                </div>
                <div class="h-10 w-50 bg-placeholder mb-3"></div>
                <div class="d-flex justify-content-between">
                    <div class="h-25 w-25 bg-placeholder mb-3"></div>
                    <div class="h-25 w-25 bg-placeholder mb-3"></div>
                </div>
            </div>
        </div>

        <div v-if="!is_loading">
            <h3 class="mb-3">Daftar Cerdas Cermat yang kamu ikuti</h3>
            <ul  class="session-list">
                <li v-for="(item,index) in list.session">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="session-name">{{item.title}}</div>
                        <!--<div :data-countdown="item.countdown" :class="'timer ' + item.status">{{item.status === 'waiting' ? 'Start' : 'End'}}-->
                            <!--<span data-hours="00" :id="'hour'+item.session_code">00</span>:-->
                            <!--<span data-minutes="00" :id="'minute'+item.session_code">00</span>:-->
                            <!--<span data-seconds="00" :id="'second'+item.session_code">00</span>-->
                        <!--</div>-->
                    </div>
                    <p class="reg-info">Registrasi Poin : <span class="orange">{{item.registration_fee}} P</span></p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <a v-on:click="showprize(index)" class="more" href="javascript:void(0)">Lihat Hadiah</a>
                        </div>
                        <!--<div v-if="item.session.status === 'expired'">-->
                            <!--<a class="btn end_session" href="javascript:void(0)">Selesai</a>-->
                        <!--</div>-->
                        <!--<div v-else-if="item.participant_status === 'completed'">-->
                            <!--<a class="btn end_session" href="javascript:void(0)">Selesai</a>-->
                        <!--</div>-->
                        <!--<div v-else-if="item.status === 'active' && !item.is_registered">-->
                            <!--<a :id="item.session_code" v-on:click="register(item.session_code, item.registration_fee)" class="btn open" href="javascript:void(0)">-->
                                <!--<div class="spinner spinner-border spinner-border-sm hide" role="status">-->
                                    <!--<span class="sr-only">Loading...</span>-->
                                <!--</div>-->
                                <!--<div class="no-spinner">-->
                                    <!--Daftar-->
                                <!--</div>-->
                            <!--</a>-->
                        <!--</div>-->
                        <!--<div v-else-if="item.status === 'active' && item.is_registered">-->
                            <!--<a class="btn start" v-on:click="start(item.session_code)" href="javascript:void(0)">Mulai</a>-->
                        <!--</div>-->
                        <!--<div v-else-if="item.status === 'waiting' && !item.is_registered">-->
                            <!--<a :id="item.session_code" v-on:click="register(item.session_code, item.registration_fee)" class="btn open" href="javascript:void(0)">-->
                                <!--<div class="spinner spinner-border spinner-border-sm hide" role="status">-->
                                    <!--<span class="sr-only">Loading...</span>-->
                                <!--</div>-->
                                <!--<div class="no-spinner">-->
                                    <!--Daftar-->
                                <!--</div>-->
                            <!--</a>-->
                        <!--</div>-->
                        <!--<div v-else-if="item.status === 'waiting' && item.is_registered">-->
                            <!--<a v-on:click="waiting(item.session_code, item.registration_fee, item.start_date)" class="btn open" href="javascript:void(0)">Mulai</a>-->
                        <!--</div>-->
                        <div>
                            <a v-on:click="waiting(item.session_code, item.registration_fee, item.start_date)" class="btn open" href="javascript:void(0)">Lihat Hasil</a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

        <div id="prize_modal" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Daftar Hadiah</h5>
                    </div>
                    <div class="modal-body">
                        <h4 class="mb-3">Hadiah dari sesi ini adalah :</h4>
                        <table v-if="prize" class="table table-striped w-100">
                            <thead>
                            <tr>
                                <th class="text-center">Ranking</th>
                                <td></td>
                                <th>Hadiah</th>
                            </tr>
                            <tr v-for="item in prize">
                                <td align="center">{{item.rank}}</td>
                                <td><img :src="item.img" alt="" width="50"></td>
                                <td>{{item.prize_name}}</td>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button v-on:click="close_modal()" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                is_loading: true,
                list : [],
                is_registering :false,
                prize : []
            }
        },
        mounted () {
            axios
                .get('/api/cerdas-cermat/history' , {
                    params: {
                        mmses: $('meta[name=usr-token]').attr('content')
                    }
                })
                .then(response => {
                    if(response.data.code === "202"){
                        alertify.alert('Need Login').setting({'title':'Cerdas Cermat'});
                    }
                    this.is_loading = false;
                    this.list = response.data.data;
                })
        },
        methods:{
            showprize(index){
                this.prize =  this.list.session[index].prize;
                console.log(this.prize);
                $('#prize_modal').modal('show');
            },
            close_modal(){
                $('#prize_modal').modal('hide');
            }
        }
    }
</script>