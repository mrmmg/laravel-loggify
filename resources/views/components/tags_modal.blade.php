<div class="modal fade" id="tagsModal" tabindex="-1" aria-labelledby="tagsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="tagsModalLabel">Tags Information</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-responsive table-hover table-bordered table-sm align-middle">
                    <thead>
                    <tr>
                        <td>Tag</td>
                        <td>Items Count</td>
                    </tr>
                    </thead>

                    <tbody class="table-group-divider">
                    @foreach($information['tags'] as $tag_name => $count_items)
                        <tr>
                            <td><a href="{{route('loggify.view_tag', ['tag' => $tag_name])}}" class="text-decoration-none">{{$tag_name}}</a></td>
                            <td>{{$count_items}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <ul class="" style="min-width: max-content">

                </ul>
            </div>
        </div>
    </div>
</div>
