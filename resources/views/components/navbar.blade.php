<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid d-flex justify-between">
        <a class="navbar-brand" href="{{route('loggify.view_tag')}}">
            <h4>Loggify</h4>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item dropend">
                    <a class="nav-link dropdown-toggle dropdown-center" href="#" role="button" data-bs-toggle="dropdown"
                       aria-expanded="false">
                        General Information
                    </a>
                    <ul class="dropdown-menu" style="min-width: max-content">
                        <li class="px-2"><span>Total Keys: {{$information['db']['total_keys']}}</span></li>
                        <li class="px-2"><span>Expirable Keys: {{$information['db']['expirable_keys']}}</span></li>
                        <li class="px-2">
                            <hr class="dropdown-divider">
                        </li>
                        <li class="px-2"><span>Used Memory: {{$information['db']['used_memory']}}</span></li>
                        <li class="px-2"><span>Peak Used Memory: {{$information['db']['used_memory_peak']}}</span></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li class="px-2"><span>Redis Version: {{$information['db']['redis_version']}}</span></li>
                    </ul>
                </li>

                <li class="nav-item dropend">
                    <a class="nav-link dropdown-toggle dropdown-center" href="#" role="button" data-bs-toggle="modal"
                       data-bs-target="#tagsModal" aria-expanded="false">
                        Tags Information
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

@include('loggify::components.tags_modal', ['information' => $information])
