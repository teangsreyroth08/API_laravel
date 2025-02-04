@extends('layouts.app')

@section('content')
<h1>Edit Specialization</h1>
<form action="{{ route('specializations.update', $specialization->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div>
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="{{ $specialization->name }}" required>
    </div>
    <div>
        <label for="description">Description:</label>
        <textarea name="description" id="description">{{ $specialization->description }}</textarea>
    </div>
    <button type="submit">Update</button>
</form>
@endsection