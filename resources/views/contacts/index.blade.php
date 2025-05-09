@extends('layout')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center">Contact List</h2>

    <div class="row justify-content-center mb-3">
        <div class="col-md-6">
            <form method="GET" action="{{ route('contacts.index') }}">
                <div class="input-group">
                    <input type="text" name="phone" class="form-control" placeholder="Search by phone number" value="{{ request('phone') }}">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <a href="{{ route('contacts.index') }}" class="btn btn-secondary">Clear Filters</a>

                    <a href="{{ route('contacts.index', ['trash'=>true]) }}" class="btn btn-warning">Trashed Records</a>
                </div>
            </form>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10">
            @if(session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger mt-3">
                        {{ session('error') }}
                    </div>
                @endif

            <div class="card shadow-sm rounded-3">
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped align-middle text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>#ID</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($contacts as $contact)
                                <tr>
                                    <td>{{ $contact->id ?? 0 }}</td>
                                    <td>{{ $contact->name ?? 'NA' }}</td>
                                    <td>{{ $contact->phone ?? 'NA' }}</td>
                                    @if($contact->trashed())
                                    <td>
                                        <a href="{{ route('contacts.recover', $contact) }}" class="btn btn-warning btn-sm">Recover</a>
                                    </td>
                                    @else
                                    <td>
                                        <a href="{{ route('contacts.edit', $contact) }}" class="btn btn-warning btn-sm">Edit</a>

                                        <form action="{{ route('contacts.destroy', $contact) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Soft delete this contact?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-dark btn-sm">Soft Delete</button>
                                        </form>

                                        <form action="{{ route('contacts.forceDelete', $contact) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Permanently delete this contact? This cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Force Delete</button>
                                        </form>
                                    </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-muted">No contacts found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {!! $contacts->links() !!}
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
