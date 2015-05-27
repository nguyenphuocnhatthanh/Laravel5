<div class="col-sm-12 col-md-12 table-responsive">
    <div id="displayModal"></div>
    <table class="table table-striped" id="PersData">
        <thead>
            <th>{!! sortBy('permission','name', 'Name', isset($search) ? $search : null) !!}
                @include('admin.partials.sortIcon', ['column' => 'name'])
            </th>
            <th>{!! sortBy('permission','display_name', 'Display Name', isset($search) ? $search : null) !!}@include('admin.partials.sortIcon', ['column' => 'display_name'])</th>
            <th>{!! sortBy('permission','description', 'Description', isset($search) ? $search : null) !!}@include('admin.partials.sortIcon', ['column' => 'description'])</th>
            <th>Actions</th>
            <th><input type="checkbox" id="checkAll"/></th>
        </thead>
        <tbody>

        @if($permissions != null)
            @foreach($permissions as $permission)
                <tr>
                    <td>{{$permission->name}}</td>
                    <td>{{$permission->display_name}}</td>
                    <td>{{$permission->description}}</td>
                    <td>

                        @if(isset($selected) && $selected == 'displaydelete')
                            {!! link_to_asset('admin/permission/restore/'.$permission->id, 'Enable', ['class' => 'iframe btn btn-xs btn-primary btn-restore cboxElement', 'data-id' => $permission->id]) !!}
                        @else
                            {!! link_to_asset('admin/permission/edit/'.$permission->id, 'Edit',
                            [
                            'class' => 'iframe btn btn-xs btn-default btn-edit cboxElement ',
                            'data-toggle' => 'modal'
                            ])
                            !!}
                            {!! link_to_asset('admin/permission/delete/'.$permission->id, 'Delete', ['class' => 'iframe btn btn-xs btn-danger cboxElement', 'data-id' => $permission->id]) !!}
                        @endif
                    </td>
                    <td><input type="checkbox" name="selectedCheck[]" value="{{$permission->id}}" class="selectedCheck"/></td>
                </tr>
            @endforeach

        @endif
        </tbody>
    </table>
</div>
@include('admin.partials.pagination', ['objects' => $permissions])


