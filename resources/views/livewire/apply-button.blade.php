<div>
    @auth('user_accounts')
        @if(auth('user_accounts')->user()->hasVerifiedEmail())
            <div class="d-flex gap-2 flex-wrap">
                @if($applyUrl)
                    <a href="{{ $applyUrl }}" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="btn-primary-modern"
                       onclick="@this.call('toggle')">
                        <i class="fa fa-external-link-alt me-2" aria-hidden="true"></i>
                        Apply Now
                    </a>
                @endif
                
                <button class="btn-outline-modern {{ $isApplied ? 'active' : '' }}" 
                        wire:click="toggle"
                        wire:loading.attr="disabled"
                        style="{{ $isApplied ? 'background: var(--gradient-primary); color: white; border-color: transparent;' : '' }}">
                    <span wire:loading.remove>
                        <i class="fa {{ $isApplied ? 'fa-check-circle' : 'fa-bookmark' }} me-2" aria-hidden="true"></i>
                        {{ $isApplied ? 'Sudah Apply' : 'Tandai Sudah Apply' }}
                    </span>
                    <span wire:loading>
                        <i class="fa fa-spinner fa-spin me-2" aria-hidden="true"></i>
                        Loading...
                    </span>
                </button>
            </div>
        @else
            <a href="{{ route('verification.notice') }}" class="btn-primary-modern">
                <i class="fa fa-envelope me-2" aria-hidden="true"></i>
                Verifikasi Email untuk Apply
            </a>
        @endif
    @else
        <a href="{{ route('login') }}" class="btn-primary-modern">
            <i class="fa fa-sign-in-alt me-2" aria-hidden="true"></i>
            Login untuk Apply
        </a>
    @endauth
</div>
