<h4 class="fw-bold mt-4">Additional Policies</h4>
@foreach($sections as $section)
    <div class="handbook-entry" id="section-{{ $section->id }}">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="sub-header mt-4">{{ $section->header }}</h5>
            <div>
                 @if(Auth::user()->role === 'super')
                <button class="btn btn-sm btn-outline-primary edit-section-btn"
                        data-id="{{ $section->id }}"
                        data-header="{{ $section->header }}"
                        data-description="{{ $section->description }}">
                    Edit
                </button>
                <button class="btn btn-sm btn-outline-danger delete-section-btn"
                        data-id="{{ $section->id }}">
                    Delete
                </button>
                @endif
            </div>
        </div>

        @php
            $paragraphs = preg_split('/\r\n|\r|\n/', $section->description);
        @endphp

        @foreach($paragraphs as $paragraph)
            @if(trim($paragraph) !== '')
                <p>{{ $paragraph }}</p>
            @endif
        @endforeach
    </div>
@endforeach
