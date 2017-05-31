@extends('master_icerik')
@section('desc')  Duyurular @stop
@section('sayfatitle')  Duyurular @stop
@section('mi_breadcrumb')<li class="active">Duyurular</li>	@stop	
@section('mi_baslik') Duyurular @stop
@section('mi_icerik')
{!! $xcrud->render() !!}
@stop