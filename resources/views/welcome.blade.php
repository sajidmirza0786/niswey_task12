@extends('layout')

@section('content')

<div class="container mt-5">
    <div class="row justify-content-center mb-4">
        <div class="col-md-6">
            <form action="{{ route('contacts.upload') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow rounded-4 p-4 border">
                @csrf

                <h4 class="text-center mb-4">Upload Contacts XML File</h4>

                <div class="mb-3">
                    <label for="xml_file" class="form-label fw-semibold">Select XML File</label>
                    <input type="file" class="form-control" id="xml_file" name="xml_file" accept=".xml" required>
                    <small class="text-muted">Only .xml files are supported.</small>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>

                @if(session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
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
            </form>
        </div>
    </div>
</div>
@endsection
