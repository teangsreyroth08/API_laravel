@extends('layouts.app')

@section('content')
<h1>Inventory Types</h1>
<a href="{{ route('inventory-types.create') }}">Create New Inventory Type</a>
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($inventoryTypes as $inventoryType)
        <tr>
            <td>{{ $inventoryType->name }}</td>
            <td>
                <a href="{{ route('inventory-types.show', $inventoryType->id) }}">View</a>
                <a href="{{ route('inventory-types.edit', $inventoryType->id) }}">Edit</a>
                <form action="{{ route('inventory-types.destroy', $inventoryType->id) }}" method="POST"
                    style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection