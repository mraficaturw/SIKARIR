<div>
    @auth('user_accounts')
        @if(auth('user_accounts')->user()->hasVerifiedEmail())
            <button class="btn-favorite {{ $isFavorite ? 'active' : '' }}" 
                    wire:click="toggle"
                    wire:loading.attr="disabled"
                    aria-label="{{ $isFavorite ? 'Remove from favorites' : 'Add to favorites' }}">
                <i class="{{ $isFavorite ? 'fa-solid' : 'fa-regular' }} fa-heart" 
                   wire:loading.class="fa-spin"
                   aria-hidden="true"></i>
            </button>
        @else
            <a href="{{ route('verification.notice') }}" 
               class="btn-favorite"
               title="Verifikasi email untuk menambahkan favorit">
                <i class="far fa-heart" aria-hidden="true"></i>
            </a>
        @endif
    @else
        <a href="{{ route('login') }}" 
           class="btn-favorite"
           title="Login untuk menambahkan favorit">
            <i class="far fa-heart" aria-hidden="true"></i>
        </a>
    @endauth
</div>
