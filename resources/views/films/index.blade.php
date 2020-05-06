@extends('layouts.app')

@section('content')
<films-component :categories="{{json_encode($categories)}}"></films-component>
@endsection