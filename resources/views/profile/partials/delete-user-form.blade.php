<section class="card shadow-sm border-danger-subtle">
    <div class="card-body p-4">
        <h2 class="h5 fw-bold mb-1">Delete Account</h2>
        <p class="text-secondary small mb-4">
            Once your account is deleted, your login is removed permanently. Your past orders stay on record for the gallery, but you will no longer be able to view them.
        </p>

        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#confirm-user-deletion">
            Delete Account
        </button>
    </div>
</section>

<div class="modal fade" id="confirm-user-deletion" tabindex="-1" aria-labelledby="confirm-user-deletion-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="post" action="{{ route('profile.destroy') }}" class="modal-content">
            @csrf
            @method('delete')

            <div class="modal-header">
                <h2 class="modal-title h5" id="confirm-user-deletion-title">Delete your account?</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <p class="small text-secondary">Enter your password to confirm you want to permanently delete your account.</p>
                <label for="delete_password" class="visually-hidden">Password</label>
                <input id="delete_password" name="password" type="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror" placeholder="Password">
                @error('password', 'userDeletion')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete Account</button>
            </div>
        </form>
    </div>
</div>
