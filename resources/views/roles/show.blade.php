@extends('layouts.app')

@section('content')
<h1>Role Details</h1>
<p><strong>Name:</strong> {{ $role->name }}</p>
<p><strong>Description:</strong> {{ $role->description }}</p>
<a href="{{ route('roles.index') }}">Back to List</a>
@endsection