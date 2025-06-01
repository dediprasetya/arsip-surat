@extends(auth()->user()->role == 'staff' ? 'layouts.staff' : 'layouts.kepala')

@section('content')
<div class="container">
    <h2>Ubah Password</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <div class="col-md-6">
            <label for="new_password" class="form-label">Password Baru</label>
            <input type="password" name="new_password" id="new_password" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
        </div><br>

        <button type="submit" class="btn btn-primary">Update Password</button>
    </form>
</div>
@endsection