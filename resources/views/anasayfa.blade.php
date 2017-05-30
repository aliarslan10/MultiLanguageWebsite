@extends('master')
@section('desc'){{$ayarlar->description}} @stop
@section('sayfatitle') {{$ayarlar->title}} @stop
@section('content_homepage')

    <script src="sliderengine/jquery.js"></script>
    <script src="sliderengine/amazingslider.js"></script>
    <link rel="stylesheet" type="text/css" href="sliderengine/amazingslider-1.css">
    <script src="sliderengine/initslider-1.js"></script>

    <!-- Insert to your webpage where you want to display the slider -->
    <div class="icerikalani">
        <div class="sliderustalan"></div>
        <div id="amazingslider-wrapper-1" style="display:block;position:relative;max-width:100%;margin:0px auto 40px;">
            <div id="amazingslider-1" style="display:block;position:relative;margin:0 auto;">
                <ul class="amazingslider-slides" style="display:none;">
                @foreach($sliders as $key=>$slider)
                @if(($slider->durum) == '1' && ($slider->tur) == 'Manset')
                    @if($slider->link) <a href="{{$slider->link}}"> <li><img src='{{ asset("img/slider/$slider->slider_resim_url") }}' alt="{{$slider->slider_icerik}}"  title="{{$slider->slider_adi}}" /></li> </a>
                    @else <li><img  src='{{ asset("img/slider/$slider->slider_resim_url") }}' alt="{{$slider->slider_icerik}}"  title="{{$slider->slider_adi}}" /></li> @endif
                @endif
                @endforeach
                </ul>
            </div>
        </div>
        </div>
    </div>
    <!-- slider code is over -->

    <!-- BEGIN OF THE CAMPAIGN  CODE -->

    <div class="urunlerimiz">
        <div class="container"> <?php $key=0 ?>
        @foreach($urunKategorileri as $kategori)
        @if(($kategori->kategori_id) == '3' && ($kategori->anasayfada_goster) == '1') <?php $key++; ?>
        <a href="{{$kategori->sayfa_linki}}">
          <div class="col-md-4 col-sm-6" > 
              <div class="urunadi pull-right"  style="position: relative;"> {{$kategori->sayfa_adi}} </div>
              <div class="urun"> <img style="width:100%; height:280px;" src='{{ asset("img/urunler/$kategori->resim_linki") }}'> </div>
              <div class="clearfix"></div>
          </div>
        </a>
        @if($key%3==0) <div class="clearfix"></div> @endif
        @endif
        @endforeach
        </div>
    </div>

   <div class="clearfix"></div>

    <div class="nedenbizheader">
        <div class="container">
            <div class="col-sm-12 text-center"> @lang('translate.Biz Kimiz?') </div>
            <div class="col-sm-12 text-center"> <div class="icerikline_two"> @lang('translate.Bizi seçmek için bir çok sebebiniz var.') </div> </div>
        </div>
    </div>
    <div class="nedenbizbody">
        <div class="container">
            <div class="col-sm-7"> @lang('translate.Hakkımızda')
            <div class="icerikline_two text-justify"> 
            @foreach($urunKategorileri as $aboutus)
                @if(($aboutus->sabit_sayfalar_id) == "1")
                    <?=htmlspecialchars_decode($aboutus->sayfa_icerik,ENT_QUOTES)?>
                @elseif(($aboutus->id) == "1" && (App::getLocale()) == 'tr')
                    <?=htmlspecialchars_decode($aboutus->sayfa_icerik,ENT_QUOTES)?>
                @endif
            @endforeach
                <div class="clearfix"></div>
                <div class="bosluk50"></div>
            </div>

            </div>
            <div class="bosluk40 hidden-xs"></div>
            <div class="col-sm-5 hidden-xs"><img width="100%" src="img/whywechoseimg.png"></div>
        </div>
    </div>

    <div class="clearfix"></div>
@stop