@extends('front-organizations.layout.master')

@section('content')
    @livewire('front-organizations.shared.universal-form', ['entityKey' => 'buildings'])
    
@endsection