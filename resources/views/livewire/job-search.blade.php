<div>
    <!-- Search Box -->
    <section style="margin-top: -30px; position: relative; z-index: 10;">
        <div class="container">
            <div class="card-modern" style="padding: 1.5rem;">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label for="search-input" class="form-label small text-muted mb-1">Kata Kunci</label>
                        <input type="text" 
                               id="search-input"
                               wire:model.live.debounce.300ms="search" 
                               class="form-control" 
                               placeholder="Posisi, perusahaan...">
                    </div>
                    <div class="col-md-5">
                        <label for="category-select" class="form-label small text-muted mb-1">Jurusan</label>
                        <select id="category-select" 
                                wire:model.live="category" 
                                class="form-select">
                            <option value="">Semua Jurusan</option>
                            @foreach($faculties as $key => $name)
                                <option value="{{ $key }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <div class="btn-primary-modern w-100 text-center" style="padding: 0.75rem 1rem; opacity: 0.7;" wire:loading>
                            <i class="fa fa-spinner fa-spin" aria-hidden="true"></i>
                        </div>
                        <div wire:loading.remove class="btn-primary-modern w-100 text-center" style="padding: 0.75rem 1rem; cursor: default;">
                            <i class="fa fa-search me-2" aria-hidden="true"></i>Cari
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Jobs List Section -->
    <section class="section-modern" style="padding-top: 2rem;">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h5 fw-bold mb-1">Hasil Pencarian</h2>
                    <p class="text-muted small mb-0">{{ $jobs->total() }} lowongan ditemukan</p>
                </div>
            </div>
            
            <div wire:loading.delay class="text-center py-4">
                <div class="spinner-border text-success" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            
            <div wire:loading.remove>
                @if($jobs->count() > 0)
                    <div class="row g-4">
                        @foreach($jobs as $job)
                            <div class="col-lg-6" data-animate>
                                <div class="job-card-modern">
                                    <div class="job-header">
                                        <img class="company-logo" src="{{ $job->logo_url }}" alt="{{ $job->company->company_name ?? 'Company' }}" loading="lazy" width="60" height="60">
                                        <div style="flex: 1; min-width: 0;">
                                            <h5 class="job-title" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $job->title }}</h5>
                                            <a href="{{ route('company.detail', $job->company_id) }}" class="company-name text-decoration-none">
                                                <i class="fa fa-building" aria-hidden="true"></i>
                                                {{ $job->company->company_name ?? 'Unknown Company' }}
                                            </a>
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
                                            <i class="fa fa-map-marker-alt" aria-hidden="true"></i>
                                            {{ $job->location ?? 'Indonesia' }}
                                        </span>
                                        <span class="job-meta-item">
                                            <i class="fa fa-money-bill-wave" aria-hidden="true"></i>
                                            Rp {{ number_format((int)$job->salary_min, 0, ',', '.') }} - {{ number_format((int)$job->salary_max, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    
                                    <div class="job-footer">
                                        <span class="deadline">
                                            <i class="far fa-calendar-alt" aria-hidden="true"></i>
                                            Deadline: {{ $job->deadline->format('d M Y') }}
                                        </span>
                                        
                                        <div class="job-actions">
                                            <livewire:favorite-button :job-id="$job->id" :wire:key="'fav-'.$job->id" />
                                            
                                            <a href="{{ route('job.detail', $job->id) }}" class="btn-primary-modern" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                                                Detail <i class="fa fa-arrow-right ms-1" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <nav class="d-flex justify-content-center mt-5" aria-label="Job pagination">
                        {{ $jobs->links('pagination::bootstrap-5') }}
                    </nav>
                @else
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fa fa-search text-muted" style="font-size: 4rem; opacity: 0.3;" aria-hidden="true"></i>
                        </div>
                        <h4 class="text-muted">Tidak ada lowongan ditemukan</h4>
                        <p class="text-muted">Coba ubah kata kunci atau filter pencarian</p>
                        <button wire:click="$set('search', ''); $set('category', '')" class="btn-outline-modern">
                            <i class="fa fa-refresh me-2" aria-hidden="true"></i>Reset Filter
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>
