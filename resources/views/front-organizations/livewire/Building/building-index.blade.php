@extends('front-organizations.layout.master')

@section('content')
    @livewire('front-organizations.shared.generic-grid', ['entityKey' => 'buildings'])
    
@endsection