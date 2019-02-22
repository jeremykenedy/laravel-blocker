<script>
    $(function() {
        var cardTitle = $('#card_title');
        var usersTable = $('#users_table');
        var resultsContainer = $('#search_results');
        var blockedCount = $('#blocked_count');
        var clearSearchTrigger = $('.clear-search');
        var searchform = $('#search_users');
        var searchformInput = $('#user_search_box');
        var userPagination = $('#user_pagination');
        var searchSubmit = $('#search_trigger');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        searchform.submit(function(e) {
            e.preventDefault();
            resultsContainer.html('');
            usersTable.hide();
            clearSearchTrigger.show();
            let noResulsHtml = '<tr>' +
                                '<td>{!! trans("laravelblocker::laravelblocker.search.no-results") !!}</td>' +
                                '<td></td>' +
                                '<td class="hidden-xs"></td>' +
                                '<td class="hidden-xs"></td>' +
                                '<td class="hidden-xs"></td>' +
                                '<td class="hidden-sm hidden-xs"></td>' +
                                '<td class="hidden-sm hidden-xs hidden-md"></td>' +
                                '<td class="hidden-sm hidden-xs hidden-md"></td>' +
                                '<td></td>' +
                                '<td></td>' +
                                '<td></td>' +
                                '</tr>';

            $.ajax({
                type:'POST',
                url: "{{ route('search-users') }}",
                data: searchform.serialize(),
                success: function (result) {
                    let jsonData = JSON.parse(result);
                    if (jsonData.length != 0) {
                        $.each(jsonData, function(index, val) {
                            let rolesHtml = '';
                            let roleClass = '';
                            let showCellHtml = '<a class="btn btn-sm btn-success btn-block" href="users/' + val.id + '" data-toggle="tooltip" title="{{ trans("laravelblocker::laravelblocker.tooltips.show") }}">{!! trans("laravelblocker::laravelblocker.buttons.show") !!}</a>';
                            let editCellHtml = '<a class="btn btn-sm btn-info btn-block" href="users/' + val.id + '/edit" data-toggle="tooltip" title="{{ trans("laravelblocker::laravelblocker.tooltips.edit") }}">{!! trans("laravelblocker::laravelblocker.buttons.edit") !!}</a>';
                            let deleteCellHtml = '<form method="POST" action="/users/'+ val.id +'" accept-charset="UTF-8" data-toggle="tooltip" title="Delete">' +
                                    '{!! Form::hidden("_method", "DELETE") !!}' +
                                    '{!! csrf_field() !!}' +
                                    '<button class="btn btn-danger btn-sm" type="button" style="width: 100%;" data-toggle="modal" data-target="#confirmDelete" data-title="Delete User" data-message="{!! trans("laravelblocker::laravelblocker.modals.delete_user_message", ["user" => "'+val.name+'"]) !!}">' +
                                        '{!! trans("laravelblocker::laravelblocker.buttons.delete") !!}' +
                                    '</button>' +
                                '</form>';

                            $.each(val.roles, function(roleIndex, role) {
                                if (role.name == "User") {
                                    roleClass = 'primary';
                                } else if (role.name == "Admin") {
                                    roleClass = 'warning';
                                } else if (role.name == "Unverified") {
                                    roleClass = 'danger';
                                } else {
                                    roleClass = 'default';
                                };
                                rolesHtml = '<span class="label label-' + roleClass + '">' + role.name + '</span> ';
                            });
                            resultsContainer.append('<tr>' +
                                '<td>' + val.id + '</td>' +
                                '<td>' + val.name + '</td>' +
                                '<td class="hidden-xs">' + val.email + '</td>' +
                                '<td class="hidden-xs">' + val.first_name + '</td>' +
                                '<td class="hidden-xs">' + val.last_name + '</td>' +
                                '<td class="hidden-sm hidden-xs"> ' + rolesHtml  +'</td>' +
                                '<td class="hidden-sm hidden-xs hidden-md">' + val.created_at + '</td>' +
                                '<td class="hidden-sm hidden-xs hidden-md">' + val.updated_at + '</td>' +
                                '<td>' + deleteCellHtml + '</td>' +
                                '<td>' + showCellHtml + '</td>' +
                                '<td>' + editCellHtml + '</td>' +
                            '</tr>');
                        });
                    } else {
                        resultsContainer.append(noResulsHtml);
                    };
                    blockedCount.html(jsonData.length + " {!! trans('laravelblocker::laravelblocker.search.found-footer') !!}");
                    userPagination.hide();
                    cardTitle.html("{!! trans('laravelblocker::laravelblocker.search.title') !!}");
                },
                error: function (response, status, error) {
                    if (response.status === 422) {
                        resultsContainer.append(noResulsHtml);
                        blockedCount.html(0 + " {!! trans('laravelblocker::laravelblocker.search.found-footer') !!}");
                        userPagination.hide();
                        cardTitle.html("{!! trans('laravelblocker::laravelblocker.search.title') !!}");
                    };
                },
            });
        });
        searchSubmit.click(function(event) {
            event.preventDefault();
            searchform.submit();
        });
        searchformInput.keyup(function(event) {
            if ($('#user_search_box').val() != '') {
                clearSearchTrigger.show();
            } else {
                clearSearchTrigger.hide();
                resultsContainer.html('');
                usersTable.show();
                cardTitle.html("{!! trans('laravelblocker::laravelblocker.blocked-items-title') !!}");
                userPagination.show();
                blockedCount.html("{!! trans_choice('laravelblocker::laravelblocker.blocked-table.caption', 1, ['blockedcount' => $blocked->count()]) !!}");
            };
        });
        clearSearchTrigger.click(function(e) {
            e.preventDefault();
            clearSearchTrigger.hide();
            usersTable.show();
            resultsContainer.html('');
            searchformInput.val('');
            cardTitle.html("{!! trans('laravelblocker::laravelblocker.blocked-items-title') !!}");
            userPagination.show();
            blockedCount.html("{!! trans_choice('laravelblocker::laravelblocker.blocked-table.caption', 1, ['blockedcount' => $blocked->count()]) !!}");
        });
    });
</script>
