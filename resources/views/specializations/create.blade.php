@extends('layouts.app')

@section('content')
<h1>Create Specialization</h1>
<form action="{{ route('specializations.store') }}" method="POST">
    @csrf
    <div>
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required>
    </div>
    <div>
        <label for="description">Description:</label>
        <textarea name="description" id="description"></textarea>
    </div>
    <button type="submit">Save</button>
</form>
@endsection