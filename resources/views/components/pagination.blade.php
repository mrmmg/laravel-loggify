@if(!empty($result['logs']))
    <div class="container my-4">

        <div class="btn-group btn-group-lg d-flex justify-content-center" role="group">
            <button type="button" class="btn btn-outline-primary {{$result['pagination']['is_first_page'] ? 'disabled' : ''}}">
                <a
                    href="{{$result['pagination']['previous_page']}}"
                    class="text-decoration-none text-white"
                >Previous Page</a>
            </button>

            <button type="button" class="btn btn-outline-primary {{$result['pagination']['has_more'] ? '' : 'disabled'}}">
                <a
                    href="{{$result['pagination']['next_page']}}"
                    class="text-decoration-none text-white"
                >Next Page</a>
            </button>
        </div>

    </div>
@endif
