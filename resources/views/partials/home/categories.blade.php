<!-- Categories Section -->
<section id="categories" class="section-modern bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title" data-animate>Cari Sesuai Jurusan</h2>
            <p class="section-subtitle" data-animate>Temukan lowongan magang yang sesuai dengan bidang studimu</p>
        </div>
        
        <div class="row g-4">
            @foreach($faculties as $key => $name)
                @php
                    $count = $category_counts[$key] ?? 0;
                @endphp
                <div class="col-lg-3 col-md-4 col-sm-6" data-animate>
                    <a href="{{ route('jobs', ['category' => $key]) }}" class="category-card-modern" aria-label="{{ $name }} - {{ $count }} Lowongan">
                        <div class="icon-wrapper" aria-hidden="true">
                            <i class="fa {{ $icons[$key] ?? 'fa-graduation-cap' }}"></i>
                        </div>
                        <h6>
                            @if(mb_strlen($name) > 20)
                                {{ mb_substr($name, 0, 20) . '...' }}
                            @else
                                {{ $name }}
                            @endif
                        </h6>
                        <span class="vacancy-count">{{ $count }} Lowongan</span>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
