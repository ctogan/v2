@extends('layouts.webapp')

@section('content')
<div class="accordion" id="accordionExample">
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingOne">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
        Accordion Item #1
      </button>
    </h2>
    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
      <div class="accordion-body">
        <strong>This is the first item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingTwo">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
        Accordion Item #2
      </button>
    </h2>
    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
      <div class="accordion-body">
        <strong>This is the second item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingThree">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
        Accordion Item #3
      </button>
    </h2>
    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
      <div class="accordion-body">
        <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
      </div>
    </div>
  </div>
</div>
<div class="body ">
    <div class="justify-content-center " style="text-align:center;">
        <input type="text" placeholder="Search" class="text-search-list"/>
    </div>
    <div class="content-section container-body">
        <div class="container">
            <div class="filter-list">
                <div class="row">
                    <div class="row-inline-list">
                    <span>Jakarta
                    </span>
                    <span class="list-icon-right">&#9657
                    </span>
                    </div>
                </div>    
                <div class="row">
                    <div class="row-inline-list">
                    <span>Jakarta
                    </span>
                    <span class="list-icon-right"> &#9663
                    </span>
                    </div>
                </div>    
            </div>

        </div>
    </div>
    <div class="btn-gradient-content">
        <div class="btn-center">
            <div class="btn-gradient text-center">Simpan</div>
        </div>
    </div>
</div>
    <style>
        .btn-gradient-content{
            padding: 10px 40px 10px 40px;
        }
        .btn-center{
            padding: 10px 10px 10px 10px;
        }
        .btn-gradient{
            padding: 15px;
            background: linear-gradient(60deg, #B87CF9 ,#929CFF);
            color: #ffffff;
            border-radius: 6px;
        }
        .header-content{
            text-align: center;
            background-image: linear-gradient(120deg, #60C4C4, #8B90FF);
            color: white;
            font-family: Avenir Next;
        }
        .color-blue{
            color: #9F8FFF;
        }
        .opcity-72{
            opacity: 72%;
        }
        .content-container{
            padding: 10px 0px 0px 0px;
        }
        .font12{
            font-size: 12px !important;
        }
        .container-body{
            background-color: #ffffff !important;
        }
        .content-section{
            background-color: #ffffff;
            margin-top: 20px;
            padding: 10px 0px 0px 0px;
        }
        body{
            background-color: #F9F7FE;
        }

        .row-inline-list{
            padding: 15px;
            width: 100%;
            border-bottom: 1px solid #D8D8D8;
            color: #555555;
            font-size: 16px;
        }
        .list-icon-right{
            float: right;
        }
        .text-search-list{

            width: 80%;
            padding: 20px;
            margin: 10px;
            border: 2px solid var(--primary-color);
            border-radius: 50px;
            font-family: inherit;
            font-size: 1rem;
            padding: 0.5rem 1rem;
            color: #555555;
        }
    </style>
@endsection


@section('js')
<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
@endsection