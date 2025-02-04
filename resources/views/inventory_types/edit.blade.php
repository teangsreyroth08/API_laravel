@extends('layouts.app')

@section('content')
<h1>Edit Inventory Type</h1>
<form action="{{ route('inventory-types.update', $inventoryType->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div>
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="{{ $inventoryType->name }}" required>
    </div>
    <button type="submit">Update</button>
</form>
@endsection