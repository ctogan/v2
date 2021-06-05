<template>
    <div>
        <div class="container">
            <div class="question-number">
                <p>Pertanyaan Ke:</p>
                <ul>
                    <li v-for="n in page_count" :class="n === page ? 'active' : ''">{{ n }}</li>
                </ul>
                <div class="stopwatch d-flex justify-content-between align-items-center">
                    <div>Waktu Anda</div>
                    <div><span data-minute="0" class="minute">00</span> : <span data-second="0" class="second">0{{second}}</span> : <span data-second="0" class="milisecond">0{{milisecond}}</span></div>
                </div>
                <div class="progress-ccc">
                    <div class="status" :style="{'width':progress+'%'}"></div>
                </div>
            </div>
            <div v-if="is_loading" class="ph-loading">
                <div class="mb-4">
                    <div class="d-block justify-content-between">
                        <div class="h-25 w-100 bg-placeholder mb-2 flex-1 mr-5"></div>
                        <div class="h-25 w-100 bg-placeholder mb-2 mr-1"></div>
                        <div class="h-25 w-50 bg-placeholder mb-2 mr-1"></div>
                    </div>
                </div>

                <div class="mb-5">
                    <div class="d-flex justify-content-between">
                        <div class="h-15 w-10 bg-placeholder mb-2 mr-1"></div>
                        <div class="h-15 w-50 bg-placeholder mb-2 flex-1 mr-5"></div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="h-15 w-10 bg-placeholder mb-2 mr-1"></div>
                        <div class="h-15 w-50 bg-placeholder mb-2 flex-1 mr-5"></div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="h-15 w-10 bg-placeholder mb-2 mr-1"></div>
                        <div class="h-15 w-50 bg-placeholder mb-2 flex-1 mr-5"></div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-block justify-content-between">
                        <div class="h-25 w-100 bg-placeholder mb-2 flex-1 mr-5"></div>
                        <div class="h-25 w-100 bg-placeholder mb-2 mr-1"></div>
                        <div class="h-25 w-50 bg-placeholder mb-2 mr-1"></div>
                    </div>
                </div>

                <div>
                    <div class="d-flex justify-content-between">
                        <div class="h-15 w-10 bg-placeholder mb-2 mr-1"></div>
                        <div class="h-15 w-50 bg-placeholder mb-2 flex-1 mr-5"></div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="h-15 w-10 bg-placeholder mb-2 mr-1"></div>
                        <div class="h-15 w-50 bg-placeholder mb-2 flex-1 mr-5"></div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="h-15 w-10 bg-placeholder mb-2 mr-1"></div>
                        <div class="h-15 w-50 bg-placeholder mb-2 flex-1 mr-5"></div>
                    </div>
                </div>
            </div>

            <div>
                <form id="submit_free_session" action="/submit">
                    <input type="hidden" id="duration" name="duration" value="0">
                    <input type="hidden" :value="mmses" name="mmses">
                    <input type="hidden" :value="session" name="session_code">
                    <div class="list-question" v-for="item in list" :id="item.id">
                        <div class="question mb-4">
                            <p>Pertanyaan:</p>
                            <p>{{ item.question }}</p>
                            <input type="hidden" :value="item.id" :name="'item['+item.id+'][question]'">
                            <img v-if="item.question_image !== null" :src="item.question_image" alt="" width="100%">
                        </div>
                        <div class="answer">
                            <ul>
                                <li v-for="answer in item.answer">
                                    <input :id="answer.id" :name="'item['+item.id+'][answer]'" type="radio" :value="answer.id"><label :for="answer.id">{{ answer.answer }}</label>
                                </li>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="button-action">
            <button type="submit" v-if="page === page_count && !is_submit" v-on:click="submit()" class="btn-submit-answer">Kirim</button>
            <button id="btn_loading" v-if="is_submit" class="btn btn-primary" type="button" disabled>
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Jawaban sedang diperiksa ...
            </button>
            <a v-if="page !== page_count && !is_loading" v-on:click="next()" class="btn-next-question" href="javascript:void(0)">Berikutnya</a>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                is_loading: true,
                page : 0,
                page_count : 10,
                list :[],
                mmses: null,
                is_submit:false,
                minute : 0,
                second : 0,
                milisecond: 0,
                x :null,
                arr_question : null,
                timeout : 0,
                progress: 0,
                session:null
            }
        },
        methods:{
            next(){
                this.stop();
                this.is_loading =true;
                axios
                    .get('/api/cerdas-cermat/question' , {
                        params: {
                            mmses: $('meta[name=usr-token]').attr('content'),
                            page: this.page,
                            session_code : $('input[name=session_code]').val(),
                        }
                    })
                    .then(response => {
                        if(response.data.code === "202"){
                            alert('Need Login')
                        }
                        this.is_loading = false;
                        $('.list-question').addClass('hide');
                        this.list.push(response.data.data.question);
                        this.page +=1;
                        this.timeout =0;
                        this.progress =0;
                        this.start();
                    })
            },
            submit(){
                this.is_submit = true;
                this.stop();
                $("#duration").val(this.minute + ':' + this.second);
                $("#submit_free_session").submit();
            },
            start(){
                const self = this;
                let ms = this.milisecond;
                let s = this.second;
                let m = this.minute;
                let to = this.timeout;
                let p = this.progress;
                this.x = setInterval(function() {
                    let msec = '00';
                    let sec = '00';
                    let min = '00';
                    ms++;

                    if(ms === 100){
                        s++;
                        to++;
                        ms = 0;
                        p = p + 1.65;
                    }

                    if(ms<10){
                        msec = '0'+ms;
                    }else{
                        msec = ms;
                    }

                    if(s<10){
                        sec = '0'+s;
                    }else{
                        sec = s;
                    }
                    if(s === 60){
                        s=0;
                        m++;
                        if(m < 10){
                            min = '0'+m;
                        }else{
                            min = m;
                        }
                        $('.minute').html(min);
                        sec = '00';
                    }
                    $('.second').html(sec);
                    $('.milisecond').html(msec);
                    self.second = s;
                    self.minute = m;
                    self.milisecond = ms;
                    self.timeout = to;
                    self.progress = p;
                    if(to === 60){
                        if(self.page !== self.page_count){
                            self.next();
                            self.progress = 0;
                        }else{
                            self.submit();
                        }
                    }
                }, 10);
            },
            stop(){
                clearTimeout(this.x);
            }
        },
        mounted () {
            axios
                .get('/api/cerdas-cermat/question' , {
                    params: {
                        mmses: $('meta[name=usr-token]').attr('content'),
                        session_code : $('input[name=session_code]').val(),
                        page: this.page
                    }
                })
                .then(response => {
                    if(response.data.code === "202"){
                        alert('Need Login')
                    }
                    this.is_loading = false;
                    this.page_count = response.data.data.session.displayed_question;
                    this.list.push(response.data.data.question);
                    this.mmses = response.data.data.mmses;
                    this.session = response.data.data.session.session_code;
                    this.page +=1;
                    this.start();
                });
        }
    }

    $(document).ready(function() {
        $('#submit_free_session').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                url:'/api/cerdas-cermat/submit',
                method:"POST",
                async:true,
                data : new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success:function(response)
                {
                    if(response.code === 200) {
                        $("#btn_loading").hide();
                        alertify.alert('Selamat, kamu sudah menyelesaikan semua pertanyaan. <br/> Benar : '+ response.data.correct + '<br/>Salah : '+ response.data.wrong + '<br/> Durasi : ' + response.data.duration +
                            ' <br/><br/><b>Pemenang akan diumumkan pada akhir sesi.</b>'
                        )
                            .setting(
                                {
                                    'title':'Hasil Akhir',
                                    'closable' :false,
                                    'onok': function(){
                                        window.location = '/app/cerdas-cermat'
                                    }
                                }
                            ).set('labels', {ok:'Oke'});
                    }else{
                        alert('Mohon maaf, sedang terjadi kesalahan teknis.');
                        $(".btn-submit-answer").show();
                    }
                }
            })
        });
    });
</script>

<style scoped>

</style>