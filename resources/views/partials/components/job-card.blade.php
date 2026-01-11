{{-- Shared Job Card Component --}}
{{-- Usage: @include('partials.components.job-card', ['job' => $job, 'user' => $user]) --}}

<div class="job-card-modern">
    <div class="job-header">
        <img class="company-logo" src="{{ $job->logo_url }}" alt="{{ $job->company->company_name ?? 'Company' }}" loading="lazy" width="60" height="60">
        <div style="flex: 1; min-width: 0;">
            <h5 class="job-title" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $job->title }}</h5>
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <a href="{{ route('company.detail', $job->company_id) }}" class="company-name text-decoration-none" title="Lihat detail perusahaan">
                    <i class="fa fa-building" aria-hidden="true"></i>
                    {{ $job->company->company_name ?? 'Unknown Company' }}
                </a>
                <span class="company-name" style="pointer-events: none;">
                    <i class="fa fa-map-marker-alt" aria-hidden="true"></i>
                    {{ $job->location ?? 'Indonesia' }}
                </span>
            </div>
        </div>
    </div>
    
    <div class="job-meta">
        <span class="job-meta-item">
            <i class="fa fa-graduation-cap" aria-hidden="true"></i>
            @if(mb_strlen($job->category) > 15)
                {{ mb_substr($job->category, 0, 15) . '...' }}
            @else
                {{ $job->category }}
            @endif
        </span>
        <span class="job-meta-item">
            <i class="fa fa-money-bill-wave" aria-hidden="true"></i>
            Rp {{ number_format((int)$job->salary_min, 0, ',', '.') }} - {{ number_format((int)$job->salary_max, 0, ',', '.') }}
        </span>
    </div>
    <div class="job-footer">
        @if($job->deadline < now())
            <span class="deadline text-danger" style="font-weight: 600;">
                <i class="far fa-calendar-times" aria-hidden="true"></i>
                Tidak menerima pendaftaran lagi
            </span>
        @else
            <span class="deadline">
                <i class="far fa-calendar-alt" aria-hidden="true"></i>
                Deadline: {{ $job->deadline->format('d M Y') }}
            </span>
        @endif
        
        <div class="job-actions">
            <livewire:favorite-button :job-id="$job->id" :key="'fav-card-'.$job->id" />
            
            <a href="{{ route('job.detail', $job->id) }}" class="btn-primary-modern" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                Detail <i class="fa fa-arrow-right ms-1" aria-hidden="true"></i>
            </a>
        </div>
    </div>
</div>

