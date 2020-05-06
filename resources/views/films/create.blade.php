@extends('layouts.app')

@section('content')
<films-create-component :categories="{{json_encode($categories)}}"></films-create-component>
@endsection