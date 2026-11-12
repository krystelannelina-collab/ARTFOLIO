<section class="card shadow-sm h-100">
    <div class="card-body p-4">
        <h2 class="h5 fw-bold mb-1">Profile Information</h2>
        <p class="text-secondary small mb-4">Update your account's name and email address.</p>

        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <form method="post" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autocomplete="name">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <p class="small mt-2 mb-0">
                        Your email address is unverified.
                        <button form="send-verification" class="btn btn-link btn-sm p-0 align-baseline">Click here to re-send the verification email.</button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="small text-success mt-2 mb-0">A new verification link has been sent to your email address.</p>
                    @endif
                @endif
            </div>

            <div class="d-flex align-items-center gap-3">
                <button type="submit" class="btn btn-dark">Save</button>
                @if (session('status') === 'profile-updated')
                    <span class="small text-success" role="status">Saved.</span>
                @endif
            </div>
        </form>
    </div>
</section>
