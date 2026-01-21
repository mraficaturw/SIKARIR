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
                        <button type="button" 
                                wire:click="performSearch" 
                                wire:loading.attr="disabled"
                                class="btn-primary-modern w-100 text-center" 
                                style="padding: 0.75rem 1rem; border: none; cursor: pointer;">
                            <span wire:loading.remove wire:target="performSearch, search, category">
                                <i class="fa fa-search me-2" aria-hidden="true"></i>Cari
                            </span>
                            <span wire:loading wire:target="performSearch, search, category">
                                <i class="fa fa-spinner fa-spin" aria-hidden="true"></i>
                            </span>
                        </button>
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
            
            {{-- Loading Overlay - Centered on search results area --}}
            <div wire:loading.delay wire:target="performSearch, search, category" 
                 class="search-loading-overlay">
                <div class="search-loading-container">
                    <div class="search-loading-spinner">
                        <div class="spinner-ring"></div>
                        <div class="spinner-ring"></div>
                        <div class="spinner-ring"></div>
                    </div>
                    <p class="search-loading-text">Mencari lowongan...</p>
                </div>
            </div>
            
            <style>
                /* Search Loading Overlay - Responsive untuk semua device */
                .search-loading-overlay {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    min-height: 300px;
                    width: 100%;
                    padding: 2rem;
                }
                
                .search-loading-container {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    padding: 2rem 3rem;
                    background: rgba(255, 255, 255, 0.95);
                    backdrop-filter: blur(10px);
                    -webkit-backdrop-filter: blur(10px);
                    border-radius: 1rem;
                    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
                    animation: pulse-container 2s ease-in-out infinite;
                }
                
                @keyframes pulse-container {
                    0%, 100% { transform: scale(1); }
                    50% { transform: scale(1.02); }
                }
                
                /* Triple ring spinner */
                .search-loading-spinner {
                    position: relative;
                    width: 60px;
                    height: 60px;
                    margin-bottom: 1rem;
                }
                
                .spinner-ring {
                    position: absolute;
                    width: 100%;
                    height: 100%;
                    border-radius: 50%;
                    border: 3px solid transparent;
                    animation: spin-ring 1.2s linear infinite;
                }
                
                .spinner-ring:nth-child(1) {
                    border-top-color: #198754;
                    animation-delay: 0s;
                }
                
                .spinner-ring:nth-child(2) {
                    width: 80%;
                    height: 80%;
                    top: 10%;
                    left: 10%;
                    border-right-color: #20c997;
                    animation-delay: -0.4s;
                    animation-direction: reverse;
                }
                
                .spinner-ring:nth-child(3) {
                    width: 60%;
                    height: 60%;
                    top: 20%;
                    left: 20%;
                    border-bottom-color: #0d9668;
                    animation-delay: -0.8s;
                }
                
                @keyframes spin-ring {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
                
                .search-loading-text {
                    margin: 0;
                    font-size: 0.95rem;
                    font-weight: 500;
                    color: #198754;
                    letter-spacing: 0.025em;
                }
                
                /* Tablet (768px - 1024px) */
                @media (max-width: 1024px) {
                    .search-loading-overlay {
                        min-height: 250px;
                        padding: 1.5rem;
                    }
                    
                    .search-loading-container {
                        padding: 1.75rem 2.5rem;
                    }
                    
                    .search-loading-spinner {
                        width: 55px;
                        height: 55px;
                    }
                    
                    .search-loading-text {
                        font-size: 0.9rem;
                    }
                }
                
                /* Mobile (< 768px) */
                @media (max-width: 767px) {
                    .search-loading-overlay {
                        min-height: 200px;
                        padding: 1rem 0;
                        margin: 0 auto;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        width: 100%;
                        box-sizing: border-box;
                    }
                    
                    .search-loading-container {
                        padding: 1.5rem 2rem;
                        border-radius: 0.75rem;
                        margin: 0 auto;
                        text-align: center;
                    }
                    
                    .search-loading-spinner {
                        width: 50px;
                        height: 50px;
                        margin: 0 auto 0.75rem auto;
                    }
                    
                    .spinner-ring {
                        border-width: 2.5px;
                    }
                    
                    .search-loading-text {
                        font-size: 0.85rem;
                        text-align: center;
                    }
                }
                
                /* Small mobile (< 480px) */
                @media (max-width: 480px) {
                    .search-loading-overlay {
                        min-height: 180px;
                        padding: 1rem 0;
                    }
                    
                    .search-loading-container {
                        padding: 1.25rem 1.5rem;
                        width: auto;
                        min-width: 200px;
                        max-width: 80%;
                        margin: 0 auto;
                    }
                    
                    .search-loading-spinner {
                        width: 45px;
                        height: 45px;
                        margin: 0 auto 0.75rem auto;
                    }
                    
                    .search-loading-text {
                        font-size: 0.8rem;
                    }
                }
                
                /* Dark mode support */
                @media (prefers-color-scheme: dark) {
                    .search-loading-container {
                        background: rgba(30, 30, 30, 0.95);
                    }
                    
                    .search-loading-text {
                        color: #4ade80;
                    }
                }
            </style>
            
            <div wire:loading.remove wire:target="performSearch, search, category">
                @if($jobs->count() > 0)
                    <div class="row g-4">
                        @foreach($jobs as $job)
                            <div class="col-lg-6">
                                <div class="job-card-modern">
                                    <div class="job-header">
                                        <img class="company-logo" src="{{ $job->logo_url }}" alt="{{ $job->company->company_name ?? 'Company' }}" loading="lazy" width="60" height="60">
                                        <div style="flex: 1; min-width: 0;">
                                            <h5 class="job-title" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $job->title }}</h5>
                                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                                <a href="{{ route('company.detail', $job->company_id) }}" class="company-name text-decoration-none">
                                                    <i class="fa fa-building" aria-hidden="true"></i>
                                                    {{ $job->company->company_name ?? 'Unknown Company' }}
                                                </a>
                                                <span class="company-name" style="pointer-events: none; margin-top: 0.25rem;">
                                                    <i class="fa fa-map-marker-alt" aria-hidden="true"></i>
                                                    {{ $job->location ?? 'Indonesia' }}
                                                </span>
                                            </div>
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
                                            <i class="fa fa-money-bill-wave" aria-hidden="true"></i>
                                            {{ $job->formatted_salary }}
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
