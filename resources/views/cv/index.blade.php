<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css" media="all">
        *{
            margin:0;
            padding:0;
            font-weight: 300;
        }
        h1{
            color:#fff;
        }
        p{
            font-weight: 300;
            line-height: 1.8rem;
        }
        .header{
            height: 250px;width: 100%;background-image: linear-gradient(to right,#b87bf9,#a091fd);
        }
        .cv-container{
            width: 737px;
            margin: auto;
        }
        .row{
            display: flex;
        }
        .col-2{
            width: 20%;
        }
        .col-4{
            width: 40%;
        }
        .col-6{
            width: 60%;
        }
        .p-15{
            padding:15px;
        }
        .bl{
            position: relative;
        }
        .bl:before{
            content: '';
            border-left: 1px solid #ffffff5c;
            height: 220px;
            position: absolute;
            left: -5px;
        }
        .h-100{
            height: 100%;
        }
        img{
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 5px;
        }
        .info-top-left p{
            color:#fff;
        }
        .top-info{
            line-height: 1.6rem!important;
        }
        .top-info p{
            color:#fff;
            font-weight: 300;
            font-size: 0.9rem;
        }
        .period{
            position: relative;
            width: 200px;
        }
        .period:after{
            content: '';
            border-right: 1px solid #b77df9;
            height: 100%;
            position: absolute;
            right: 0px;
            top: 0;
        }
        .experienced{
            position: relative;
            padding-left:35px;
            padding-bottom: 30px;
        }
        .experienced:after{
            content: '';
            height: 15px;
            width: 15px;
            background-color: #b77df9;
            position: absolute;
            left: -8px;
            top: 0;
            border-radius: 50%;
        }
        .experienced h3{
            font-weight:400;
            margin-bottom: 5px;
        }
        .experienced h4{
            margin-bottom: 5px;
        }
        .experienced-item{
            display: flex;
        }
        .experienced-item:last-child{
            border-bottom: 1px solid #b77df9;
        }
        .section{
            padding:15px 35px;margin-top: 10px;
        }
        .section h2{
            margin-bottom: 30px;
        }
        .skill{
            display: flex;
            list-style: none;
            margin-left: 18px;
        }
        .skill li{
            padding:7px 10px;
            text-align: center;
            background-color: #aa89fb;
            margin:5px;
            border-radius: 20px;
            color:#fff;
        }
        .skill li:nth-child(even){
            background-color: #b87bf9;
        }
        .education{
            padding: 5px 25px;
        }
        .education h3{
            font-weight: 400;
        }
        .bb{
            border-bottom: 1px solid #b77df9;
            padding-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="cv-container">
        <div class="header" style="height: 250px;width: 100%;background-image: linear-gradient(to right,#b87bf9,#a091fd);">
            <div class="row h-100">
                <div class="col-6 p-15 h-100">
                    <div class="info-top-left" style="margin: auto;height: 100%;padding:15px 35px;">
                        <h1>{{$user->name}}</h1>
                        <p>{{UtilsHelp::SEX[$user->sex]}}, {{ date_diff(date_create($user->dob), date_create(date("Y-m-d")))->format('%y') }} Tahun</p>
                        <p>{{$user->address}}</p>
                        <p>{{ UtilsHelp::RELIGION_MASTER[$user->religion]['name']}}</p>
                        <br/>
                        <p>
                            {{$user->profile}}
                        </p>
                    </div>
                </div>
                <div class="col-4 p-15 bl">
                    <div style="margin: auto;display: flex;height: 100%;">
                        <div class="top-info">
                            <div style="text-align: center">
                                <img src="{{$user->img}}" alt="">
                            </div>
                            <p>TTL : {{ \App\Helpers\Cache::get_province_by_id($user->pob)->province_name}}, {{$user->dob}}</p>
                            <p>Email : {{$user->email}}</p>
                            <p>HP : {{$user->phone}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="body">
            <div class="section">
                <h2>Pengalaman Kerja</h2>
                @foreach($experience as $e)
                    <div class="experienced-item">
                        <div class="period">{{$e->work_periode}} </div>
                        <div class="experienced">
                            <h3>{{$e->company_name}}</h3>
                            <h4>{{$e->position}}</h4>
                            <p>{{$e->work_description}}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="section">
                <div class="row bb">
                    <div class="col-2"><h2>Keahlian</h2></div>
                    <div>
                        <ul class="skill">
                            <?php
                                $arr_skill = explode(',',$user->skills)
                            ?>

                            @foreach($arr_skill as $skill)
                                <li>{{$skill}}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="section">
                <div class="row bb">
                    <div class="col-2"><h2>Pendidikan</h2></div>
                    <div class="education">
                        <h3>{{$user->school_name}} <small>({{ \App\Helpers\Cache::get_education_by_id($user->last_education)->education}})</small></h3>
                        <h4>{{$user->major}}</h4>
                        <p>{{$user->year_of_entry}} - {{$user->graduated_year}}</p>
                    </div>
                </div>
            </div>
            <br/>
            <br/>
            <p style="text-align: center;padding: 15px;">Dibuat oleh Cashtree Application {{date('Y')}}</p>
            <br/>
            <br/>
        </div>
    </div>
</body>
</html>