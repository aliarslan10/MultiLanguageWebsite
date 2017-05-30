@extends('admin.master')

@section('admin_header')

@stop

@section('admin_content')

{!!$xcrud->render('edit', 1)!!}
  
@stop