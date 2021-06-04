<template>
    <div class="container cerdas-cermat">

        <div v-if="is_loading">
            <div class="image-banner bg-placeholder mb-4"></div>

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
            <div class="image-banner mb-4 bg-placeholder">
                <img src="https://scdn.ctree.id/f/210525/1621912843335_Dummy.webp" alt="">
            </div>
            <ul v-if="list !== null" class="session-list">
                <li v-for="item in list.session">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="session-name">{{item.title}}</div>
                        <div :class="'timer ' + item.status">{{item.status === 'waiting' ? 'Start' : 'End'}} <span :id="'hour'+item.session_code">00</span>:<span :id="'minute'+item.session_code">00</span>:<span :id="'second'+item.session_code">00</span></div>
                    </div>
                    <p class="reg-info">Registrasi Poin : <span class="orange">{{item.registration_fee}} P</span></p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <a class="more" href="javascript:void(0)">Lihat Hadiah</a>
                        </div>
                        <div v-if="item.status === 'expired'">
                            <a class="btn end_session" href="javascript:void(0)">Selesai</a>
                        </div>
                        <div v-else-if="item.status === 'active' && !item.is_registered">
                            <a :id="item.session_code" v-on:click="register(item.session_code, item.registration_fee)" class="btn open" href="javascript:void(0)">
                                <div class="spinner spinner-border spinner-border-sm hide" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <div class="no-spinner">
                                    Daftar
                                </div>
                            </a>
                        </div>
                        <div v-else-if="item.status === 'active' && item.is_registered">
                            <a class="btn start" v-on:click="start(item.session_code)" href="javascript:void(0)">Mulai</a>
                        </div>
                        <div v-else-if="item.status === 'waiting' && !item.is_registered">
                            <a :id="item.session_code" v-on:click="register(item.session_code, item.registration_fee)" class="btn open" href="javascript:void(0)">
                                <div class="spinner spinner-border spinner-border-sm hide" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <div class="no-spinner">
                                    Daftar
                                </div>
                            </a>
                        </div>
                        <div v-else-if="item.status === 'waiting' && item.is_registered">
                            <a v-on:click="waiting(item.session_code, item.registration_fee)" class="btn open" href="javascript:void(0)">Mulai</a>
                        </div>
                    </div>
                </li>
            </ul>

            <div class="btn-footer">
                <a href="/app/cerdas-cermat/free">Coba Secara GRATIS</a>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        data () {
            return {
                is_loading: true,
                list : null,
                is_registering :false
            }
        },
        mounted () {
            axios
                .get('/api/cerdas-cermat' , {
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
            waiting(){
                alertify.alert('Mohon menunggu, sesi ini belum dimulai.').setting({'title':'Cerdas Cermat'});
            },
            start(code){
                alertify.confirm('Kamu sudah siap? Waktu akan dimulai setelah memilih <b>Mulai</b>.')
                    .setting(
                        {
                            'autoReset' :false,
                            'title':'Mulai Cerdas Cermat',
                            'closable' :false,
                            'onok': function(){
                                window.location = '/app/cerdas-cermat/start/'+code;
                            }
                        }
                    ).set('labels', {cancel:'Batalkan', ok:'Mulai'});
            },
            register(code, point) {
                $("#"+code+" .spinner").show();
                $("#"+code+" .no-spinner").hide();
                alertify.confirm("Point yang dibutuhan untuk mengikuti event ini <b>" + point + "P</b>. Apakah kamu bersedia?")
                    .setting(
                        {
                            'autoReset': false,
                            'title': 'Pendaftaran',
                            'closable': false,
                            'onok': function () {
                                axios
                                    .post('/api/cerdas-cermat/register' , {
                                        mmses: $('meta[name=usr-token]').attr('content'),
                                        session_code : code
                                    })
                                    .then(response => {
                                        let code = response.data.code;
                                        if(code === "216"){
                                            alertify.alert('Point Tidak Cukup').setting({'title':'Cerdas Cermat'});
                                        }else if(code === '217'){
                                            alertify.alert('Kamu Sudah Terdaftar. Mohon menunggu sesi ini dimulai').setting({'title':'Cerdas Cermat'});
                                        }else if(code === '218'){
                                            alertify.alert('Sesi ini sudah berakhir').setting({'title':'Cerdas Cermat'});
                                        }else{
                                            alertify.alert('Pendaftaran Berhasil').setting({'title':'Cerdas Cermat'});;
                                            location.reload();
                                        }
                                    })
                            },
                            'oncancel':function () {
                                $("#"+code+" .spinner").hide();
                                $("#"+code+" .no-spinner").show();
                            }
                        }
                    ).set('labels', {cancel: 'Batalkan', ok: 'Ya Daftar Sekarang'});
            }
        }
    }
</script>