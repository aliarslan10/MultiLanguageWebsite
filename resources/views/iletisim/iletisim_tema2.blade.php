@extends('master')
@section('content')

<!-- iletisim_tema ve iletisim_arayuz Özel CSS -->
<link href="{{ asset('css/iletisim.css') }}" rel="stylesheet">

<div class="breadcrumb">
  <div class="container">
  @if(App::getLocale() == 'ar')

    @yield('breadcrumb')
  	<i class="glyphicon glyphicon-chevron-right"></i>  <a @if(App::getLocale() != 'tr') href="{{asset(App::getLocale())}}" @else  href="{{asset('/')}}" @endif> @lang('translate.Anasayfa')</a>

	@else

	    <a  @if(App::getLocale() != 'tr') href="{{ asset(App::getLocale()) }}" 
	    	@else href="{{ asset(Lang::get('translate.hakkimizda')) }}" @endif>@lang('translate.Anasayfa')</a>
		<i class="glyphicon glyphicon-chevron-right"></i> @yield('breadcrumb')

	@endif
</div>
</div>

<div class="icerik">
<div class="container">
	<div class="col-sm-8">
		<div class="col-sm-12">
	  		@yield('iletisim_sol_baslik')
		</div>
		<div class="col-sm-12">
	  		@yield('iletisim_sol_icerik')
		</div>
	</div>

	<div class="col-sm-4 menualanimobil">
		<div class="menubaslik">
	  		 @yield('iletisim_sag_baslik') 
		</div>
		<div class="menu">
	  		@yield('iletisim_sag_icerik')
		</div>
	</div>
</div>
</div>

@stop