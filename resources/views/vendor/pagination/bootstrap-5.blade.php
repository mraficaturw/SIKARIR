@if ($paginator->hasPages())
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center" style="margin-top: 20px;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" style="background-color: #f8f9fa; color: #6c757d; border-color: #dee2e6;">Sebelumnya</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Sebelumnya" style="color: #00B074; border-color: #00B074;">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Sebelumnya</span>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link" style="background-color: #f8f9fa; color: #6c757d; border-color: #dee2e6;">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link" style="background-color: #00B074; border-color: #00B074; color: white;">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}" style="color: #00B074; border-color: #00B074;">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Selanjutnya" style="color: #00B074; border-color: #00B074;">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Selanjutnya</span>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" style="background-color: #f8f9fa; color: #6c757d; border-color: #dee2e6;">Selanjutnya</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
