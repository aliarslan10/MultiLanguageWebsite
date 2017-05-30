@extends('master')
@section('content')

<!-- kurumsal_tema ve kurumsal_arayuz Özel CSS -->
<link href="{{ asset('css/kurumsal.css') }}" rel="stylesheet">

<div class="breadcrumb">
  <div class="container">
  @if(App::getLocale() == 'ar')

    @yield('breadcrumb')
  	<i class="glyphicon glyphicon-chevron-right"></i>  <a @if(App::getLocale() != 'tr') href="{{ asset(App::getLocale()) }}/@lang('translate.hakkimizda')" @else href="{{asset('hakkimizda')}}" @endif> @lang('translate.Kurumsal')  </a>
  	<i class="glyphicon glyphicon-chevron-right"></i>  <a @if(App::getLocale() != 'tr') href="{{asset(App::getLocale())}}" @else  href="{{asset('/')}}" @endif> @lang('translate.Anasayfa')</a>

	@else

	    <a  @if(App::getLocale() != 'tr') href="{{ asset(App::getLocale()) }}" 
	    	@else href="{{ asset(Lang::get('translate.hakkimizda')) }}" @endif>@lang('translate.Anasayfa')</a>
	    <i class="glyphicon glyphicon-chevron-right"></i>
	    <a @if(App::getLocale() != 'tr') href="{{ asset(App::getLocale()) }}/@lang('translate.hakkimizda')" 
	    	@else href="{{ asset(Lang::get('translate.hakkimizda')) }}" @endif> @lang('translate.Kurumsal')</a>
		<i class="glyphicon glyphicon-chevron-right"></i> @yield('breadcrumb')

	@endif
</div>
</div>

<div class="icerik">
<div class="container">
	<div class="col-md-9 col-sm-9 col-xs-12 yazialani">
		<div class="yazibaslik">
	  		@yield('kurumsal_baslik')
		</div>
		<div class="yazi">
	  		@yield('kurumsal_yazi')
		</div>
	</div>

	<div class="col-md-3 col-sm-3 col-xs-12 menualanimobil">
	<div class="menualani">
		<div class="menubaslik">
	  		<h3> @yield('kurumsal_menu_baslik') </h3>
		</div>
		<div class="menu">
	  		@yield('kurumsal_menu')
		</div>
	</div>
	</div>
</div>
</div>
@stop