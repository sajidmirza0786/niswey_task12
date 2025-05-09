@extends('layout')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form action="{{ route('contacts.update', $contact) }}" method="POST" class="bg-white shadow rounded-4 p-4 border">
                @csrf
                @method('PUT')

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

                @if($errors->any())
                    <div class="alert alert-danger mt-3">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <h4 class="text-center mb-4">Edit Contact</h4>

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $contact->name) }}" required>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label fw-semibold">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $contact->phone) }}" required>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-success">Update</button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
