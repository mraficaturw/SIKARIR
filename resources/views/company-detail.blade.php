@extends('layouts.app')

@section('content')
<!-- Page Header -->
<section style="background: var(--gradient-hero); padding: 3rem 0;">
    <div class="container">
        <nav aria-label="breadcrumb" data-animate>
            <ol class="breadcrumb mb-2" style="font-size: 0.875rem;">
                <li class="breadcrumb-item"><a href="{{ route('welcome') }}" class="text-white-50 text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('jobs') }}" class="text-white-50 text-decoration-none">Jobs</a></li>
                <li class="breadcrumb-item text-white active" aria-current="page">{{ $company->company_name }}</li>
            </ol>
        </nav>
        <h1 class="text-white fw-bold mb-0" data-animate>Detail Perusahaan</h1>
    </div>
</section>

<!-- Company Detail Content -->
<section class="section-modern" style="padding-top: 2rem;">
    <div class="container">
        <div class="row g-4">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Company Header Card -->
                <div class="card-modern mb-4" data-animate>
                    <div class="d-flex align-items-start gap-4 flex-wrap">
                        <img src="{{ $company->logo_url }}" 
                             alt="{{ $company->company_name }}" 
                             class="rounded-modern-lg" 
                             style="width: 100px; height: 100px; object-fit: cover; border: 2px solid var(--gray-100);"
                             loading="eager">
                        <div style="flex: 1;">
                            <h2 class="h4 fw-bold mb-2">{{ $company->company_name }}</h2>
                            <div class="d-flex flex-wrap gap-3 text-muted" style="font-size: 0.9rem;">
                                @if($company->official_website)
                                <a href="{{ $company->official_website }}" target="_blank" rel="noopener noreferrer" class="text-primary text-decoration-none">
                                    <i class="fa fa-globe me-1" aria-hidden="true"></i>Website
                                </a>
                                @endif
                                @if($company->email)
                                <span><i class="fa fa-envelope text-primary me-1" aria-hidden="true"></i>{{ $company->email }}</span>
                                @endif
                                @if($company->address)
                                <span><i class="fa fa-map-marker-alt text-primary me-1" aria-hidden="true"></i>{{ $company->address }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Description -->
                <div class="card-modern mb-4" data-animate>
                    <h3 class="h5 fw-bold mb-3"><i class="fa fa-info-circle text-primary me-2" aria-hidden="true"></i>Tentang Perusahaan</h3>
                    @if($company->company_description)
                        <p class="text-muted" style="white-space: pre-line;">{{ $company->company_description }}</p>
                    @else
                        <p class="text-muted fst-italic">Belum ada deskripsi perusahaan.</p>
                    @endif
                </div>
                
                <!-- Job Listings -->
                <div class="card-modern" data-animate>
                    <h3 class="h5 fw-bold mb-4"><i class="fa fa-briefcase text-primary me-2" aria-hidden="true"></i>Lowongan Tersedia ({{ $company->vacancies->count() }})</h3>
                    
                    @if($company->vacancies->count() > 0)
                        <div class="d-flex flex-column gap-3">
                            @foreach($company->vacancies as $job)
                            <a href="{{ route('job.detail', $job->id) }}" class="text-decoration-none">
                                <div class="p-3 border rounded-3 hover-shadow" style="transition: all 0.3s ease;">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h5 class="mb-1 text-dark">{{ $job->title }}</h5>
                                            <div class="d-flex flex-wrap gap-2 text-muted small">
                                                <span><i class="fa fa-map-marker-alt me-1" aria-hidden="true"></i>{{ $job->location }}</span>
                                                <span><i class="fa fa-money-bill-wave me-1" aria-hidden="true"></i>{{ $job->formatted_salary }}</span>
                                            </div>
                                        </div>
                                        <span class="badge bg-success-subtle text-success">{{ $job->category }}</span>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-3">Belum ada lowongan dari perusahaan ini.</p>
                    @endif
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Contact Info -->
                <div class="card-modern mb-4" style="position: sticky; top: 100px;" data-animate>
                    <h3 class="h5 fw-bold mb-4"><i class="fa fa-address-card text-primary me-2" aria-hidden="true"></i>Informasi Kontak</h3>
                    
                    <div class="d-flex flex-column gap-3">
                        @if($company->phone)
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 44px; height: 44px; background: rgba(0, 176, 116, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;" aria-hidden="true">
                                <i class="fa fa-phone text-primary"></i>
                            </div>
                            <div>
                                <div class="small text-muted">Telepon</div>
                                <div class="fw-medium">{{ $company->phone }}</div>
                            </div>
                        </div>
                        @endif
                        
                        @if($company->email)
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 44px; height: 44px; background: rgba(0, 176, 116, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;" aria-hidden="true">
                                <i class="fa fa-envelope text-primary"></i>
                            </div>
                            <div>
                                <div class="small text-muted">Email</div>
                                <div class="fw-medium">{{ $company->email }}</div>
                            </div>
                        </div>
                        @endif
                        
                        @if($company->address)
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 44px; height: 44px; background: rgba(0, 176, 116, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;" aria-hidden="true">
                                <i class="fa fa-map-marker-alt text-primary"></i>
                            </div>
                            <div>
                                <div class="small text-muted">Kantor Pusat</div>
                                <div class="fw-medium">{{ $company->address }}</div>
                            </div>
                        </div>
                        @endif
                        
                        @if($company->official_website)
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 44px; height: 44px; background: rgba(0, 176, 116, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;" aria-hidden="true">
                                <i class="fa fa-globe text-primary"></i>
                            </div>
                            <div>
                                <div class="small text-muted">Website</div>
                                <a href="{{ $company->official_website }}" target="_blank" rel="noopener noreferrer" class="fw-medium text-primary text-decoration-none">
                                    {{ parse_url($company->official_website, PHP_URL_HOST) ?? $company->official_website }}
                                </a>
                            </div>
                        </div>
                        @endif
                        
                        @if(!$company->phone && !$company->email && !$company->address && !$company->official_website)
                        <p class="text-muted text-center py-2 fst-italic">Belum ada informasi kontak.</p>
                        @endif
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Action Buttons -->
                    <a href="{{ route('jobs') }}" class="btn-outline-modern w-100 text-center">
                        <i class="fa fa-arrow-left me-2" aria-hidden="true"></i>Kembali ke Lowongan
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
