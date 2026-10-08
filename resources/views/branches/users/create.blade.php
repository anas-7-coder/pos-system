{{-- @extends('layouts.app')

@section('title', 'Assign User')

@section('content')

<h1>
    Assign User to {{ $branch->name }}
</h1>

<a href="{{ route('branches.users.index', $branch) }}">Back to Branch Users</a>

<hr>

<form method="POST" action="{{ route('branches.users.store', $branch) }}">
    @csrf

    <label>User</label><br>
    <select name="user_id" required>
        <option value="">Select User</option>

        @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->email }}</option>
        @endforeach
    </select>
    <br><br>

    <button type="submit">
        Assign User
    </button>

</form>

@endsection --}}










@extends('layouts.app')

@section('title', 'Assign User')

@section('content')

<div class="page-header">

    <div>
        <h1 class="page-title">
            Assign User
        </h1>

        <p class="page-subtitle">
            Assign a user to the {{ $branch->name }} branch.
        </p>
    </div>

    <div class="page-actions">

        <a
            href="{{ route('branches.users.index', $branch) }}"
            class="btn btn-secondary"
        >
            Back to Branch Users
        </a>

    </div>

</div>


<div class="card form-card">

    <div class="card-header">

        <h2 class="card-title">
            User Assignment
        </h2>

    </div>


    <div class="card-body">

        <form
            method="POST"
            action="{{ route('branches.users.store', $branch) }}"
        >

            @csrf

            <div class="form-group">

                <label
                    for="user_id"
                    class="form-label"
                >
                    User
                    <span class="required">*</span>
                </label>

                <select
                    name="user_id"
                    id="user_id"
                    required
                >

                    <option value="">
                        Select User
                    </option>

                    @foreach($users as $user)

                        <option
                            value="{{ $user->id }}"
                            {{ old('user_id') == $user->id ? 'selected' : '' }}
                        >
                            {{ $user->name }} - {{ $user->email }}
                        </option>

                    @endforeach

                </select>

                @error('user_id')

                    <div class="field-error">
                        {{ $message }}
                    </div>

                @enderror

                <div class="form-help">
                    Select the user who should have access to this branch.
                </div>

            </div>


            <div
                class="card-footer"
                style="margin: 25px -22px -22px;"
            >

                <div class="action-group">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Assign User
                    </button>

                    <a
                        href="{{ route('branches.users.index', $branch) }}"
                        class="btn btn-secondary"
                    >
                        Cancel
                    </a>

                </div>

            </div>

        </form>

    </div>

</div>

@endsection