@if($errors->has())
    <ul class="has-error">
    @foreach($errors->all() as $error)
        <li>{{$error}}</li>
    @endforeach
    </ul>
@endif
