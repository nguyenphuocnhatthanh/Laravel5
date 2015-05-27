@if(isset($selected) && $selected == 'displaydelete')
    {!! Form::select('option', $listRestore, !empty($selected) || Session::has('selected')  ? $selected : null, ['class' => 'form-control', 'id' => 'option' ]) !!}
@else
    {!! Form::select('option', $select, !empty($selected) || Session::has('selected')  ? $selected : null, ['class' => 'form-control', 'id' => 'option' ]) !!}
@endif