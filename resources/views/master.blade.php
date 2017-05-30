<!DOCTYPE html>
<html lang="tr-TR" itemscope="" itemtype="http://schema.org/WebSite"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <title> @yield('sayfatitle') - MultiLanguageWebSite</title>
	
    <!-- SEO -->
    <meta name="author" content="">
    <meta name="description" itemprop="description" content="@yield('desc')"/>
	<link rel="shortlink" href="">
  
    <!-- Mobil Site -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    
    <!-- Favicon-->
    <link rel="shortcut icon" type="image/x-icon" href="#">    
    <!-- Favicon-->
    
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    
    <!-- JS -->
    <script type="text/javascript" src="{{ asset('js/jquery.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>

    <!-- Özelleştirmeler -->
    <link href="{{ asset('css/genel.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/tasarim1.css') }}" rel="stylesheet">

    <!-- FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">

</head>
<body>

    <div class="headerust">
    <div class="container">
        <div class="col-sm-8 col-xs-12 hidden-xs" style="padding : 0;">
        </div>
        <div class="col-sm-4 col-xs-12">
            <ul class="list-inline pull-right uyeislemleri">
                <li><a href='{{ asset("/") }}'> <img src='{{ asset("img/tr.jpg") }}'> </a></li>
                <li><a href='{{ asset("/en") }}'> <img src='{{ asset("img/en.jpg") }}'> </a></li>
                <li><a href='{{ asset("/ar") }}'> <img src='{{ asset("img/ar.jpg") }}'> </a></li>
                <li><a href='{{ asset("/de") }}'> <img src='{{ asset("img/de.jpg") }}'> </a></li>
                <li><a href='{{ asset("/fr") }}'> <img src='{{ asset("img/fr.jpg") }}'> </a></li>
            </ul>
        </div>
    </div>
    </div>

    <div class="clearfix"></div>

    <div class="headerinfo">
      <div class="container">
      <div class="col-sm-12 col-md-12 col-lg-3">
        <div class="logo text-center">
			<a href='{{ asset("/") }}@if(App::getLocale() != "tr"){{ App::getLocale() }} @endif'>
			<img class="logoMobile" src='{{ asset("img/logo.png") }}' alt=""></a>
		</div>
      </div>
      <div class="col-sm-12 col-md-12 col-lg-9  hidden-xs">
          <!-- INFO -->
        <div class="top-info-con">
        <ul>
          
          <!-- Call Us Now -->
          <li>
            <div class="media-left"><i class="fa fa-phone"></i></div>
            <div class="media-body">
              <h6>@lang('translate.Bizi Arayın')</h6>
              <span> (+90) 534 052 10 02 (+90) 538 744 28 93 </span> </div>
          </li>
          
          <!-- Email us -->
          <li>
            <div class="media-left"><i class="fa fa-envelope-o"></i></div>
            <div class="media-body">
              <h6>@lang('translate.Bize Yazın')</h6>
              <span>test@mailadress.com</span> </div>
          </li>
          
          <!-- Opening Time -->
          <li class="lst" style="border-right: 1px solid white;">
            <div class="media-left"><i class="fa fa-clock-o"></i></div>
            <div class="media-body">
              <h6> @lang('translate.Sosyal Medya') </h6>
			  <div class="bosluk5"></div>&nbsp;
              <span><a target="_blank" href='{{ $ayarlar->facebook }}'> <i class="fa fa-2x fa-facebook"></i> </a></span> &nbsp;&nbsp;
              <span><a target="_blank" href='{{ $ayarlar->twitter  }}'> <i class="fa fa-2x fa-twitter"></i> </a></span> &nbsp;&nbsp;
              <span><a target="_blank" href='{{ $ayarlar->instagram}}'> <i class="fa fa-2x fa-instagram"></i> </a></span>
              </div>
          </li>
        </ul>
      </div>
      </div>
    </div>
    </div>


    <div class="clearfix"></div>

    <nav class="navbar navbar-default" style="background-color:#4c4f5e;">
        <div class="container">

            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Açılır Menü</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav sagcizgi">
                  @foreach($kategoriler as $kategori)
                    @if($kategori->menudeki_yeri == 'Sol')
                      @if(($kategori->alt_menu) == null)
                      <li><a href='{{ asset($kategori->link) }}'> {{$kategori->kategori}} </a></li>
                      @else
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{$kategori->kategori}}<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                          @foreach($urunKategorileri as $menu)
                          @if(($kategori->sabit_sayfa_kategori_id) == ($menu->kategori_id))
                          <li> <a href="{{ asset($menu->sayfa_linki) }}">{{$menu->sayfa_adi}}</a></li>
                          @endif
                          @endforeach
                        </ul>
                      </li>
                      @endif
                    @endif             
                  @endforeach
                </ul>
            </div>
      </div>
    </nav>

@yield('content')
@yield('content_homepage')

    <div class="footerback">
        <div class="container-fluid">
            <div class="col-sm-4">
                <div class="bosluk50"></div>
                <div class="fbaslik">@lang('translate.İletişim Adreslerimiz')</div>
                <div class="icerikline">@lang('translate.footeraciklama2')</div>
                <div class="flist">
                  <!--  <div class="col-md-12 ">
                        <div class="index1_adress">
                          <i class="fa fa-map-marker" aria-hidden="true"></i>
                          <p>
                              <span>Sanayi Mah. 60418 Nolu Sokak,</span>
                              <span>Erdoğan Ergönül Cad. </span>
							  <span> Gatem Top Sitesi, Beyaz Ada 2. Blog No:56 Gaziantep</span>
                            </p>
                        </div>
                    </div> -->
                    <div class="col-md-12">
                        <div class="index1_adress">
                          <i class="fa fa-envelope-o" aria-hidden="true"></i>
                          <p>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        
                    </div>
                </div>
            </div>
			<div class="col-sm-5">
                <div class="bosluk50"></div>
                <div class="fbaslik">@lang('translate.Konumumuz')</div>
                <div class="icerikline" style="padding-left: 5px;">
				Molla Gürani Mahallesi, Erseven Sokağı No:2, Blok B, Daire No:3, Fatih/İstanbul <hr>
				Sanayi Mah. 60418 Nolu Sokak, Erdoğan Ergönül Cad. Gatem Top Sitesi, Beyaz Ada 2. Blog No:56 Şehitkâmil/Gaziantep</div>
				<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d795.7140288428092!2d37.43697604087001!3d37.08473367107902!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzfCsDA1JzA0LjkiTiAzN8KwMjYnMTYuMSJF!5e0!3m2!1str!2str!4v1494336327245" width="100%" height="220" frameborder="0" style="border:0" allowfullscreen></iframe>
                <div class="bosluk50"></div>
			</div>
            <div class="col-sm-3 hidden-xs">
            <div class="bosluk50"></div>
                <div class="fbaslik" style="padding-left: 15px;">@lang('translate.Ürünlerimiz')</div>
                <div class="icerikline" style="padding-left: 15px;">@lang('translate.footeraciklama1')</div>

                <?php $sayac=0; ?>
                @foreach($tumurunler as $footerUrun)

                  @if(($sayac++)%8 == '0')
                  <div class="flist col-md-12">
                     <ul class="footermenu">
                  @endif

                    <li class=""><a href='{{ asset("$footerUrun->sayfa_linki") }}'>{{$footerUrun->sayfa_adi}}</a></li>
                  
                  @if(($sayac)%8 == '0')
                    </ul>
                  </div>
                  @endif

                @endforeach
                <div class="bosluk50"></div>
            </div>
        </div>

        <div class="clearfix"></div>
            </div>
         <div class="footerinfo">
            <div class="container">
                <div class="col-sm-8">
                 <div class="bosluk5"></div>
                        <b> Copyright 2017 © Ali ARSLAN ® @lang('translate.Tüm Hakları Saklıdır.') </b>
                </div>
                <div class="col-sm-3">
                    <div class="pull-right">
                        <div class="imza">
                          Ali ARSLAN - Bilgisayar Mühendisi
                        </div>
                    </div>
                </div>
            </div>
        </div>      
      <!-- Go to www.addthis.com/dashboard to customize your tools --> <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-58ecc4518a2c79e1"></script> 
    </body>
</html>