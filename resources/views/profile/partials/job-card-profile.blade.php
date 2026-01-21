<div class="job-card-modern h-100">
    <div class="job-header">
        <img class="company-logo" src="{{ $job->logo_url }}" alt="{{ $job->company->company_name ?? 'Company' }}" loading="lazy" width="60" height="60">
        <div style="flex: 1; min-width: 0;">
            <h5 class="job-title" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $job->title }}</h5>
            <a href="{{ route('company.detail', $job->company_id) }}" class="company-name text-decoration-none" title="Lihat detail perusahaan">
                <i class="fa fa-building" aria-hidden="true"></i>
                {{ $job->company->company_name ?? 'Unknown Company' }}
            </a>
        </div>
    </div>
    <div class="job-meta">
        <span class="job-meta-item">
            <i class="fa fa-graduation-cap" aria-hidden="true"></i>
            @if(mb_strlen($job->category) > 18)
                {{ mb_substr($job->category, 0, 18) . '...' }}
            @else
                {{ $job->category }}
            @endif
        </span>
        <span class="job-meta-item">
            <i class="fa fa-map-marker-alt" aria-hidden="true"></i>
            {{ $job->location ?? 'Indonesia' }}
        </span>
        <span class="job-meta-item">
            <i class="fa fa-money-bill-wave" aria-hidden="true"></i>
            {{ $job->formatted_salary }}
        </span>
        @if($showAppliedDate ?? false)
            <span class="job-meta-item">
                <i class="fa fa-calendar-check" aria-hidden="true"></i>
                Applied: {{ \Carbon\Carbon::parse($job->pivot->applied_at)->format('d M, Y') }}
            </span>
        @endif
        @if($job->deadline < now())
            <span class="job-meta-item text-danger" style="font-weight: 600;">
                <i class="far fa-calendar-times" aria-hidden="true"></i>
                Tidak menerima pendaftaran lagi
            </span>
        @else
            <span class="job-meta-item">
                <i class="far fa-calendar-alt" aria-hidden="true"></i>
                Deadline: {{ $job->deadline->format('d M Y') }}
            </span>
        @endif
    </div>
    <div class="job-footer">
        <div class="d-flex gap-2">
            <a href="{{ route('job.detail', $job->id) }}" class="btn-primary-modern" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                View <i class="fa fa-arrow-right ms-1" aria-hidden="true"></i>
            </a>
            @if($job->apply_url)
                <a href="{{ $job->apply_url }}" target="_blank" class="btn-success-modern" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                    {{ ($showAppliedDate ?? false) ? 'See Progress' : 'Apply' }} <i class="fa fa-external-link-alt ms-1" aria-hidden="true"></i>
                </a>
            @endif
        </div>
    </div>
</div>
