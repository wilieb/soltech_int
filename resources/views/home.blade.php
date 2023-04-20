<?php 
session_start();
$session = '';
if(isset($_SESSION['session'])){
    $session = $_SESSION['session'];
}
else{
    $session = '';
}
?> 
<!-- extending layout -->
@extends('layout.app')
<!-- app title -->
@section('title','CRUD | SOLUTECH')

<!-- main section -->
@section('main-content')
    @if ($session)
    <app :user="{{$session}}" ></app>
    @else
        <app :user="false"></app>
    @endif
@endsection