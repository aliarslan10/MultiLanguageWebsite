@extends('master')
@section('content')

<!-- urunler_tema ve urunler_arayuz Özel CSS -->
<link href="{{ asset('css/urunler.css') }}" rel="stylesheet">

<div class="breadcrumb">
  <div class="container">
  	@if(App::getLocale() == 'ar')

  	@yield('breadcrumb')
  	<i class="glyphicon glyphicon-chevron-right"></i>  <a @if(App::getLocale() != 'tr') href="{{ asset(App::getLocale()) }}/@lang('translate.urunlerimiz')" @else href="{{asset('urunlerimiz')}}" @endif> @lang('translate.Ürünlerimiz')  </a>
  	<i class="glyphicon glyphicon-chevron-right"></i>  <a @if(App::getLocale() != 'tr') href="{{asset(App::getLocale())}}" @else  href="{{asset('/')}}" @endif>@lang('translate.Anasayfa')</a>

  	@else
	    <a @if(App::getLocale() != 'tr') href="{{asset(App::getLocale())}}" @else  href="{{asset('/')}}" @endif>@lang('translate.Anasayfa')</a>
	    <i class="glyphicon glyphicon-chevron-right"></i> 
	    <a @if(App::getLocale() != 'tr') href="{{ asset(App::getLocale()) }}/@lang('translate.urunlerimiz')" @else href="{{asset('urunlerimiz')}}" @endif> @lang('translate.Ürünlerimiz')  </a>
		<i class="glyphicon glyphicon-chevron-right"></i> @yield('breadcrumb')
	@endif
</div>
</div>

<div class="icerik">
<div class="container">
	<div class="col-sm-9 yazialani">
		<div class="col-sm-12 yazibaslik">
	  		@yield('yazi_baslik')
		</div>
		<div class="col-sm-12 yazi">
	  		@yield('yazi')
		</div>
	</div>

	<div class="col-sm-3 menualanimobil">
	<div class="menualani">
		<div class="menubaslik">
	  		<h3> @yield('menu_baslik') </h3>
		</div>
		<div class="menu">
	  		@yield('menu')
		</div>
	</div>
	</div>
</div>
</div>
@stop