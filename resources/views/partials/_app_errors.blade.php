@if(isset($errors) && $errors->any())
    <div class="iq-card-header d-flex justify-content-between">
        <div class="alert alert-danger col-md-12 mt-2">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{!! $error !!}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
