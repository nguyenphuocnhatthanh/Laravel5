

function changeSelect(id, form, urlAddress){
    $(id).change(function() {

        if($(this).val() == "") return;
        if($(this).val() == 'delete') {
            if($('.selectedCheck:checked').length == 0) {
                $('#option option:first-child').attr('selected', 'selected');
                return;
            }
        }
        if($(this).val() == 'restore') {
            if($('.selectedCheck:checked').length == 0) {
                $('#option option:nth-child(3)').attr('selected', 'selected');
                return;
            }
        }
        history.pushState({}, null, urlAddress);
        $(form).submit();

    });
}

function showModalFinal(){
    var checkAjax = false;
    $("#ajaxLoading").hide();
    $(document).on('click', 'tbody tr td a.btn-edit', function(e){
        e.preventDefault();
        url = $(this).attr('href');

        $("#ajaxLoading").show();
        if(checkAjax) return;
        checkAjax = true;
        $.ajax({
            url : url,
            type: 'GET',
            success: function(result) {
                $("#ajaxLoading").hide();
                $('#displayModal').html(result);
                $('#myModal').modal('show');
            }
        }).always(function(){
            checkAjax = false;
        });
    });
}

function deleteAjax(url){

    var checkAjax = false;
    $(document).off('click', 'tbody tr td a:nth-child(2)').on('click', 'tbody tr td a:nth-child(2)', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var child = $(this);

        if(checkAjax) return;

        var answer = confirm('Bạn có chắc chắn muốn xóa ?');
        if( ! answer) return;
        checkAjax = true;
        $.ajax({
            url: url+id,
            //data: {id : id},
            type: 'GET',
            success: function (result) {
                $.each(result, function(key, value){
                    if(value) {
                        child.parents('tr').remove();
                    }
                });

            }
        }).always(function() {
            checkAjax = false;
        });
    });
}
function AjaxPagination(id){

    checkAjax = false;
    $(document).on('click', '#pagination a, table th a', function(e){

        e.preventDefault();
        if($(this).attr('href').indexOf('#') == 0) return;
        if(checkAjax) return;
        checkAjax = true;
        $.ajax({
            url : $(this).attr('href'),
            type : 'GET',
            data : {
                'option' : $('#option').val()
            },
            success: function(result){
                $('#'+id).html(result);
            }
        }).always(function(){
            checkAjax = false;
        });
    });
}

function search(RouteUrl, displayId){
    var globalTimeout = null;
    $(document).on('keyup', '#search',function(){
        if(globalTimeout != null) {
            clearTimeout(globalTimeout);
        }

        globalTimeout = setTimeout(function ()
        {
            var data = {
                option : $('#option').val(),
                search : $('#search').val()
            };

            $.ajax({
                type : 'get',
                data :  data,
                dataType : 'JSON',
                url : RouteUrl,
                success : function (result){
                    $('#'+displayId).html(result);
                }
            });
        }, 1000);
    });
}

function editEntrust(Formid, tableId, urlEdit, urlDelete ){
    $("#loadding").hide();
    var checkAjax = false;
    $(document).off('submit', Formid).on('submit', Formid, function(e){

        var id = url.split('/');
        e.preventDefault();
        $("#loadding").show();
        if(checkAjax) return;

        checkAjax = true;
        $.ajax({
            url : $(this).attr('action'),
            type : 'POST',
            data : $(this).serialize(),
            success : function(result){
                console.log(result);
                if(typeof (result) != 'undefined'){
                    var flashMessage = '<div class="alert alert-success"><button type="button" data-dismiss="alert" aria-hidden="true" class="close">x</button>';
                    flashMessage += 'Edit success </div>';
                    $('#flashMessage').html(flashMessage);

                    $(tableId+' tr td a:first-child').each(function(key, value){
                        var urlPerms = $(this).attr('href');
                        if(urlPerms == url){
                            var thisElement = $(this).closest('tr');
                            $.each(result, function(index, object){
                                var value1 = '';
                                value1 += '<td>'+object.name+'</td>';
                                value1 += '<td>'+object.display_name+'</td>';
                                value1 += '<td>'+object.description+'</td>';
                                value1 += '<td><a href="'+urlEdit+object.id+'" class="iframe btn btn-xs btn-default btn-edit cboxElement"' +
                                ' data-toggle="modal">Edit</a>' +
                                ' <a href="'+urlDelete+object.id+'" class="iframe btn btn-xs btn-danger cboxElement"' +
                                'data-id="'+object.id+'">Delete</a></td>';
                                value1 +=  '<td><input type="checkbox" name="selectedCheck[]" value="'+object.id+'" class="selectedCheck"/></td>';

                                thisElement.html(value1);
                            });
                            $("#loadding").hide();
                            $(Formid+' button:last-child').hide();
                            return;
                        }
                    });
                }
            }
        }).always(function(){
            checkAjax = false;
        });
    });
}


function editEntrustUser(Formid, tableId, urlEdit, urlDelete ){
    $("#loadding").hide();
    var checkAjax = false;
    $(document).off('submit', Formid).on('submit', Formid, function(e){

        var id = url.split('/');

        e.preventDefault();
        $("#loadding").show();
        if(checkAjax) return;

        checkAjax = true;
        $.ajax({
            url : $(this).attr('action'),
            type : 'POST',
            data : $(this).serialize(),
            success : function(result){

                if(typeof (result) != 'undefined'){
                    var flashMessage = '<div class="alert alert-success"><button type="button" data-dismiss="alert" aria-hidden="true" class="close">x</button>';
                    flashMessage += 'Edit success </div>';
                    $('#flashMessage').html(flashMessage);

                    $(tableId+' tr td a:first-child').each(function(key, value){
                        var urlPerms = $(this).attr('href');

                        if(urlPerms == url){
                            var thisElement = $(this).closest('tr');
                            $.each(result, function(index, object){
                                var value1 = '';
                                value1 += '<td>'+object.name+'</td>';
                                value1 += '<td>'+object.email+'</td>';
                                value1 += '<td><select class="form-control" name="roles">';
                                $.each(object.roles, function(key, role){
                                    value1 += '<option value="'+role.id+'">'+role.name+'</option>';
                                });
                                value1 += '</select></td>';
                                value1 += '<td><a href="'+urlEdit+object.id+'" class="iframe btn btn-xs btn-default btn-edit cboxElement"' +
                                ' data-toggle="modal">Edit</a>' +
                                ' <a href="'+urlDelete+object.id+'" class="iframe btn btn-xs btn-danger cboxElement"' +
                                'data-id="'+object.id+'">Delete</a></td>';
                                value1 +=  '<td><input type="checkbox" name="selectedCheck[]" value="'+object.id+'" class="selectedCheck"/></td>';

                                thisElement.html(value1);
                            });
                            $("#loadding").hide();
                            $(Formid+' button:last-child').hide();
                            return;
                        }
                    });
                }
            }
        }).always(function(){
            checkAjax = false;
        });
    });
}



function displayTable(object, table) {
    var value1 = '';
    value1 += '<td>' + object.name + '</td>';
    value1 += table;
    return value1;
}

function restoreAjax(){
    $(document).off('click', 'table tbody tr td a.btn-restore').on('click', 'table tbody tr td a.btn-restore',function(event){
        event.preventDefault();
        var thisElement = $(this);
        $.ajax({
            url : $(this).attr('href'),
            type : 'GET',
            success : function(result){
                if(result){
                    thisElement.parents('tr').remove();
                }
            }
        });
    });
}