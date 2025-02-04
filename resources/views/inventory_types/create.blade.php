@extends('layouts.app')

@section('content')
<h1>Create Inventory Type</h1>
<form action="{{ route('inventory-types.store') }}" method="POST">
    @csrf
    <div>
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required>
    </div>
    <button type="submit">Save</button>
</form>
@endsection