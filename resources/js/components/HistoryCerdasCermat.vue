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
                            <a v-on:click="showresult(index, item.session_code, item.status)" class="btn open" href="javascript:void(0)">Lihat Hasil</a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

        <div id="prize_modal" class="modal" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <p>Daftar Hadiah</p>
                        <table v-if="prize" class="table table-striped w-100">
                            <thead>
                            <tr>
                                <th class="text-center">Ranking</th>
                                <th></th>
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

        <div id="ccc_result_modal" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <p class="mb-0">Daftar Pemenang</p>
                        <small>Hadiah dapat diambil setelah sesi selesai</small>
                        <div class="mt-3" v-if="loading_prize">
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
                        </div>
                        <div class="mt-3" v-if="!loading_prize">
                            <table v-if="result" class="table table-sm table-striped w-100 responsive">
                                <thead>
                                <tr>
                                    <th class="text-center">Ranking</th>
                                    <th>Name</th>
                                    <th class="text-center">Score</th>
                                    <th class="text-right">Waktu</th>
                                    <th class="text-center">Hadiah</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="item in result" :class="item.uid === uid ? 'my-position' :'' ">
                                    <td align="center">{{item.rank}}</td>
                                    <td>{{item.name}} <small class="d-block">{{item.phone}}</small></td>
                                    <td align="center">{{item.score}}</td>
                                    <td align="right">{{item.duration}}</td>
                                    <td align="center">
                                        <button v-if="status && item.uid === uid" v-on:click="redeem_prize()" type="button" class="btn-sm btn-redeem">Ambil</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div style="position: absolute;left: 15px;">Kamu Ranking : {{rank}}</div>
                        <button v-on:click="close_modal()" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="ccc_progress_modal" class="modal fade" tabindex="-1" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="text-center">
                            <img src="https://scdn.ctree.id/f/210623/1624430473937_Pulsa%20Mission@2x.webp" style="width: 80%;">
                            <h5>Mohon Tunggu ....</h5>
                            <p>Hadiah kamu sedang disiapkan</p>
                        </div>
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
                prize : [],
                result : [],
                uid : 0,
                rank : 0,
                status : false,
                loading_prize : true,
                session_code : ''
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
                $('#prize_modal').modal('show');
            },
            showresult(index, session_code, status){
                this.rank = 0;
                this.session_code = session_code;
                $('#ccc_result_modal').modal('show');
                this.result = [];
                this.loading_prize = true;
                axios
                    .get('/api/cerdas-cermat/result' , {
                        params: {
                            mmses: $('meta[name=usr-token]').attr('content'),
                            session_code : session_code,
                        }
                    })
                    .then(response => {
                        if(response.data.code === "202"){
                            alertify.alert('Need Login').setting({'title':'Cerdas Cermat'});
                        }
                        this.loading_prize = false;
                        this.result = response.data.data.result;
                        this.uid = response.data.data.uid;
                        this.rank = response.data.data.rank;
                        this.status = status === "expired" ? true : false;
                    })
            },
            redeem_prize(){
                var scode = this.session_code;
                $('#ccc_result_modal').modal('hide');
                var content = '<div class="text-center">\n' +
                    '                            <img src="https://scdn.ctree.id/f/210623/1624430473937_Pulsa%20Mission@2x.webp" style="width: 80%;margin-bottom: 15px">\n' +
                    '                            <h5>Mohon Tunggu ....</h5>\n' +
                    '                            <p>Hadiah kamu sedang disiapkan</p>\n' +
                    '                        </div>'
                alertify.alert(content).setting({'title':'Cerdas Cermat','closable':false,'basic':true});

                axios
                    .get('/api/cerdas-cermat/prize/get' , {
                        params: {
                            mmses: $('meta[name=usr-token]').attr('content'),
                            session_code : scode,
                        }
                    })
                    .then(response => {
                        if(response.data.code !== "200"){
                            alertify.alert('<div style="text-align: center"><img style="width: 80%;margin-bottom: 15px;" src="https://scdn.ctree.id/f/210623/1624438905913_Shop%202@2x.webp"><h4>'+response.data.message +'</h4></div>').setting({'title':'Cerdas Cermat','closable':true,'basic':false});
                        }else{
                            alertify.alert('<div style="text-align: center"><img style="width: 80%;margin-bottom: 15px;" src="https://scdn.ctree.id/f/210623/1624438404738_Leaderboard@2x.webp"><h4>Selamat kamu mendapatkan '+response.data.data.prize +'</h4></div>').setting({'title':'Cerdas Cermat','closable':true,'basic':false});
                        }
                    })
            },
            close_modal(){
                $('.modal').modal('hide');
            }
        }
    }
</script>