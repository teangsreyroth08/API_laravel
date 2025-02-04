@extends('layouts.app')

@section('content')
<h1>Inventory Type Details</h1>
<p><strong>Name:</strong> {{ $inventoryType->name }}</p>
<a href="{{ route('inventory-types.index') }}">Back to List</a>
@endsection