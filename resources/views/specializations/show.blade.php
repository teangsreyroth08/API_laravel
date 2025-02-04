@extends('layouts.app')

@section('content')
<h1>Specialization Details</h1>
<p><strong>Name:</strong> {{ $specialization->name }}</p>
<p><strong>Description:</strong> {{ $specialization->description }}</p>
<a href="{{ route('specializations.index') }}">Back to List</a>
@endsection