@extends('layouts.app')

@section('content')
<h1>Specializations</h1>
<a href="{{ route('specializations.create') }}">Create New Specialization</a>
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($specializations as $specialization)
        <tr>
            <td>{{ $specialization->name }}</td>
            <td>{{ $specialization->description }}</td>
            <td>
                <a href="{{ route('specializations.show', $specialization->id) }}">View</a>
                <a href="{{ route('specializations.edit', $specialization->id) }}">Edit</a>
                <form action="{{ route('specializations.destroy', $specialization->id) }}" method="POST"
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