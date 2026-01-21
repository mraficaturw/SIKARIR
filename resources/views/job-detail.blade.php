@extends('layouts.app')

@section('content')
<!-- Page Header -->
<section style="background: var(--gradient-hero); padding: 3rem 0;">
    <div class="container">
        <nav aria-label="breadcrumb" data-animate>
            <ol class="breadcrumb mb-2" style="font-size: 0.875rem;">
                <li class="breadcrumb-item"><a href="{{ route('welcome') }}" class="text-white-50 text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('jobs') }}" class="text-white-50 text-decoration-none">Jobs</a></li>
                <li class="breadcrumb-item text-white active" aria-current="page">Detail</li>
            </ol>
        </nav>
        <h1 class="text-white fw-bold mb-0" data-animate>Detail Lowongan</h1>
    </div>
</section>

<!-- Job Detail Content -->
<section class="section-modern" style="padding-top: 2rem;">
    <div class="container">
        <div class="row g-4">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Job Header Card -->
                <div class="card-modern mb-4" data-animate>
                    <div class="d-flex align-items-start gap-4 flex-wrap">
                        <img src="{{ $job->logo_url }}" 
                             alt="{{ $job->company->company_name ?? 'Company' }}" 
                             class="rounded-modern-lg" 
                             style="width: 80px; height: 80px; object-fit: cover; border: 2px solid var(--gray-100);"
                             loading="eager">
                        <div style="flex: 1;">
                            <h2 class="h4 fw-bold mb-2">{{ $job->title }}</h2>
                            <div class="d-flex flex-wrap gap-3 text-muted" style="font-size: 0.9rem;">
                                <a href="{{ route('company.detail', $job->company_id) }}" class="text-decoration-none text-muted">
                                    <i class="fa fa-building text-primary me-2" aria-hidden="true"></i>{{ $job->company->company_name ?? 'Unknown Company' }}
                                </a>
                                <span><i class="fa fa-graduation-cap text-primary me-2" aria-hidden="true"></i>
                                    @if(mb_strlen($job->category) > 20)
                                        {{ mb_substr($job->category, 0, 20) . '...' }}
                                    @else
                                        {{ $job->category }}
                                    @endif
                                </span>
                                <span><i class="fa fa-money-bill-wave text-primary me-2" aria-hidden="true"></i>{{ $job->formatted_salary }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Description -->
                <div class="card-modern mb-4" data-animate>
                    <h3 class="h5 fw-bold mb-3"><i class="fa fa-file-alt text-primary me-2" aria-hidden="true"></i>Deskripsi Pekerjaan</h3>
                    <p class="text-muted" style="white-space: pre-line;">{{ $job->description }}</p>
                </div>
                
                <!-- Responsibility -->
                <div class="card-modern mb-4" data-animate>
                    <h3 class="h5 fw-bold mb-3"><i class="fa fa-tasks text-primary me-2" aria-hidden="true"></i>Tanggung Jawab</h3>
                    <p class="text-muted" style="white-space: pre-line;">{{ $job->responsibility }}</p>
                </div>
                
                <!-- Qualifications -->
                <div class="card-modern mb-4" data-animate>
                    <h3 class="h5 fw-bold mb-3"><i class="fa fa-check-circle text-primary me-2" aria-hidden="true"></i>Kualifikasi</h3>
                    <p class="text-muted" style="white-space: pre-line;">{{ $job->qualifications }}</p>
                </div>
                
                <!-- Apply Section -->
                <div class="card-modern" data-animate style="background: linear-gradient(135deg, rgba(0, 176, 116, 0.05) 0%, rgba(0, 212, 170, 0.05) 100%);">
                    <h3 class="h5 fw-bold mb-3"><i class="fa fa-paper-plane text-primary me-2" aria-hidden="true"></i>Cara Melamar</h3>
                    <p class="text-muted mb-3">Silakan klik link di bawah untuk melamar langsung ke perusahaan:</p>
                    <a href="{{ $job->apply_url }}" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="d-inline-flex align-items-center text-primary text-decoration-none mb-4" 
                       style="word-break: break-all;">
                        <i class="fa fa-external-link-alt me-2" aria-hidden="true"></i>{{ $job->apply_url }}
                    </a>
                    
                    @auth('user_accounts')
                        @if(auth('user_accounts')->user()->hasVerifiedEmail())
                            <livewire:apply-button :job-id="$job->id" :apply-url="$job->apply_url" />
                        @else
                            <a href="{{ route('verification.notice') }}" class="btn-primary-modern w-100" style="padding: 1rem; font-size: 1rem; text-align: center;">
                                <i class="fa fa-envelope me-2" aria-hidden="true"></i>Verifikasi Email untuk Menandai
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn-primary-modern w-100" style="padding: 1rem; font-size: 1rem; text-align: center;">
                            <i class="fa fa-sign-in-alt me-2" aria-hidden="true"></i>Login untuk Menandai Applied
                        </a>
                    @endauth
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Job Summary -->
                <div class="card-modern mb-4" style="position: sticky; top: 100px;" data-animate>
                    <h3 class="h5 fw-bold mb-4"><i class="fa fa-info-circle text-primary me-2" aria-hidden="true"></i>Ringkasan</h3>
                    
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 44px; height: 44px; background: rgba(0, 176, 116, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;" aria-hidden="true">
                                <i class="fa fa-calendar-plus text-primary"></i>
                            </div>
                            <div>
                                <div class="small text-muted">Dipublikasi</div>
                                <div class="fw-medium">{{ $job->created_at->format('d M Y') }}</div>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 44px; height: 44px; background: rgba(0, 176, 116, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;" aria-hidden="true">
                                <i class="fa fa-sync-alt text-primary"></i>
                            </div>
                            <div>
                                <div class="small text-muted">Terakhir Update</div>
                                <div class="fw-medium">{{ $job->updated_at->format('d M Y') }}</div>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 44px; height: 44px; background: rgba(0, 176, 116, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;" aria-hidden="true">
                                <i class="fa fa-money-bill-wave text-primary"></i>
                            </div>
                            <div>
                                <div class="small text-muted">Gaji</div>
                                <div class="fw-medium">{{ $job->formatted_salary }}</div>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 44px; height: 44px; background: rgba(0, 176, 116, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;" aria-hidden="true">
                                <i class="fa fa-map-marker-alt text-primary"></i>
                            </div>
                            <div>
                                <div class="small text-muted">Lokasi</div>
                                <div class="fw-medium">{{ $job->location ?? 'Indonesia' }}</div>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 44px; height: 44px; background: rgba(239, 68, 68, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;" aria-hidden="true">
                                <i class="fa fa-clock text-danger"></i>
                            </div>
                            <div>
                                <div class="small text-muted">Deadline</div>
                                <div class="fw-medium text-danger">{{ $job->deadline->format('d M Y') }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        <a href="{{ route('jobs') }}" class="btn-outline-modern flex-grow-1" style="text-align: center;">
                            <i class="fa fa-arrow-left me-2" aria-hidden="true"></i>Kembali
                        </a>
                        
                        @auth('user_accounts')
                            @if(auth('user_accounts')->user()->hasVerifiedEmail())
                                <div style="width: 52px; height: 52px;">
                                    <livewire:favorite-button :job-id="$job->id" />
                                </div>
                            @else
                                <a href="{{ route('verification.notice') }}" class="btn-favorite" style="width: 52px; height: 52px;" aria-label="Verifikasi email untuk favorit">
                                    <i class="far fa-heart" style="font-size: 1.25rem;" aria-hidden="true"></i>
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn-favorite" style="width: 52px; height: 52px;" aria-label="Login untuk favorit">
                                <i class="far fa-heart" style="font-size: 1.25rem;" aria-hidden="true"></i>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
