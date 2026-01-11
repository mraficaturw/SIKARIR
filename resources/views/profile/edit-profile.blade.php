@extends('layouts.app')

@section('content')
<!-- Profile Header -->
<section style="background: var(--gradient-hero); padding: 3rem 0;">
    <div class="container">
        <div class="text-center" data-animate>
            <h1 class="text-white fw-bold mb-2">Edit Profil</h1>
            <p class="text-white-50 mb-0">Perbarui informasi dan foto profil kamu</p>
        </div>
    </div>
</section>

<!-- Edit Profile Form -->
<section class="section-modern" style="padding-top: 2rem;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card-modern" data-animate>
                    @if($errors->any())
                        <div class="alert alert-danger mb-4" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="editProfileForm">
                        @csrf
                        
                        <!-- Avatar Section -->
                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block">
                                <!-- Current Avatar Preview -->
                                <div id="avatarPreviewContainer" class="rounded-circle bg-light mx-auto mb-3" 
                                     style="width: 150px; height: 150px; overflow: hidden; border: 4px solid var(--primary); display: flex; align-items: center; justify-content: center;">
                                    @if($user->avatar_url)
                                        <img id="avatarPreview" src="{{ $user->avatar_url }}" alt="Avatar" 
                                             style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <i class="fa fa-user text-muted" id="defaultAvatar" style="font-size: 4rem;"></i>
                                        <img id="avatarPreview" src="" alt="Avatar" 
                                             style="width: 100%; height: 100%; object-fit: cover; display: none;">
                                    @endif
                                </div>
                                
                                <!-- Edit Badge -->
                                <label for="avatarInput" class="position-absolute" 
                                       style="bottom: 10px; right: -5px; background: var(--primary); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 3px solid white;">
                                    <i class="fa fa-camera text-white"></i>
                                </label>
                            </div>
                            
                            <input type="file" id="avatarInput" name="avatar" accept="image/*" class="d-none">
                            
                            <p class="text-muted small mb-0">Klik ikon kamera untuk mengganti foto</p>
                            <p class="text-muted small">Format: JPG, PNG, WebP. Max: 5MB</p>
                            
                            <!-- Cancel Button (Hidden by default) -->
                            <button type="button" id="cancelAvatarBtn" class="btn btn-sm btn-outline-danger d-none">
                                <i class="fa fa-times me-1"></i>Batalkan Perubahan Foto
                            </button>
                        </div>

                        <!-- Name Field -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" class="form-control form-control-lg" id="name" name="name" 
                                   value="{{ old('name', $user->name) }}" required>
                        </div>

                        <!-- Email Field (Read Only) -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control form-control-lg" id="email" 
                                   value="{{ $user->email }}" readonly disabled>
                            <small class="text-muted">Email tidak dapat diubah</small>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3 flex-wrap">
                            <button type="submit" class="btn-primary-modern" style="padding: 0.875rem 2rem;">
                                <i class="fa fa-save me-2"></i>Simpan Perubahan
                            </button>
                            <a href="{{ route('profile.show') }}" class="btn-outline-modern" style="padding: 0.875rem 2rem;">
                                <i class="fa fa-arrow-left me-2"></i>Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const avatarInput = document.getElementById('avatarInput');
    const avatarPreview = document.getElementById('avatarPreview');
    const defaultAvatar = document.getElementById('defaultAvatar');
    const cancelBtn = document.getElementById('cancelAvatarBtn');
    
    let originalSrc = avatarPreview ? avatarPreview.src : '';
    let hasOriginalAvatar = {{ $user->avatar_url ? 'true' : 'false' }};
    
    avatarInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            // Validate file type
            if (!file.type.startsWith('image/')) {
                alert('File harus berupa gambar!');
                return;
            }
            
            // Validate file size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file maksimal 5MB!');
                return;
            }
            
            // Preview the image
            const reader = new FileReader();
            reader.onload = function(e) {
                avatarPreview.src = e.target.result;
                avatarPreview.style.display = 'block';
                if (defaultAvatar) defaultAvatar.style.display = 'none';
                cancelBtn.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }
    });
    
    cancelBtn.addEventListener('click', function() {
        // Reset to original
        avatarInput.value = '';
        
        if (hasOriginalAvatar) {
            avatarPreview.src = originalSrc;
            avatarPreview.style.display = 'block';
            if (defaultAvatar) defaultAvatar.style.display = 'none';
        } else {
            avatarPreview.style.display = 'none';
            if (defaultAvatar) defaultAvatar.style.display = 'block';
        }
        
        cancelBtn.classList.add('d-none');
    });
});
</script>
@endpush
