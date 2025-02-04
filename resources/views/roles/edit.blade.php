@extends('layouts.app')

@section('content')
<h1>Edit Role</h1>
<form action="{{ route('roles.update', $role->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div>
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="{{ $role->name }}" required>
    </div>
    <div>
        <label for="description">Description:</label>
        <textarea name="description" id="description">{{ $role->description }}</textarea>
    </div>
    <button type="submit">Update</button>
</form>
@endsection