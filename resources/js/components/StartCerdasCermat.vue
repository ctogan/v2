<template>
    <div>
        <div class="container">
            <div class="question-number">
                <p>Pertanyaan Ke:</p>
                <ul>
                    <li v-for="n in page_count" :class="n === page ? 'active' : ''">{{ n }}</li>
                </ul>
            </div>

            <div v-if="is_loading" style="position: fixed;width: 83%;background: #fff;height: 100vh;">
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
                    <input type="hidden" :value="mmses" name="mmses">
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
                list :null,
                mmses: null,
                is_submit:false
            }
        },
        mounted () {
            axios
                .get('/api/cerdas-cermat/question/free' , {
                    params: {
                        mmses: $('meta[name=usr-token]').attr('content'),
                        page: this.page
                    }
                })
                .then(response => {
                    if(response.data.code === "202"){
                        alert('Need Login')
                    }
                    this.is_loading = false;
                    this.page +=1;
                    this.list = response.data.data.question;
                    this.mmses = response.data.data.mmses;
                })
        },
        methods:{
            next(){
                this.is_loading =true;
                axios
                    .get('/api/cerdas-cermat/question/free' , {
                        params: {
                            mmses: $('meta[name=usr-token]').attr('content'),
                            page: this.page
                        }
                    })
                    .then(response => {
                        if(response.data.code === "202"){
                            alert('Need Login')
                        }
                        this.is_loading = false;
                        this.page +=1;
                        var datas = this.list;
                        $('.list-question').addClass('hide');
                        $.each(response.data.data.question , function( key , value){
                            datas.push(value);
                        });
                    })
            },
            submit(){
                this.is_submit = true;
                $("#submit_free_session").submit();
            }
        }
    }

    $(document).ready(function() {
        $('#submit_free_session').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                url:'/api/cerdas-cermat/free/submit',
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
                        alertify.confirm('Selamat, kamu sudah menyelesaikan semua pertanyaan. <br/> Benar : '+ response.data.correct + '<br/>Salah : '+ response.data.wrong)
                            .setting(
                                {
                                    'title':'Hasil Akhir',
                                    'closable' :false,
                                    'onok': function(){
                                        location.reload();
                                    },
                                    'oncancel': function(){
                                        window.location = '/app/cerdas-cermat'
                                    }
                                }
                            ).set('labels', {ok:'Coba Lagi', cancel:'Tutup'});
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