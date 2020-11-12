var adminmonitor = {
    init : function(init_settings) {
        var token = init_settings.token;
        var text_loading = init_settings.text_loading;
        var text_confirm = init_settings.text_confirm;
        var module_path = init_settings.module_path;

        var set_body = function(data) {
            $('#events').html(data);
        }

        var render_data = function(data) {
            var html = '';

            for (var i in data.events) {
                var item = data.events[i];

                html += '<tr>';
                html += '<td><input type="checkbox" name="delete_event[]" value="' + item.adminmonitor_id + '" /></td>';
                html += '<td>' + item.user_name + '</td>';
                html += '<td>' + item.message + '</td>';
                html += '<td>' + item.type + '</td>';
                html += '<td>' + item.group + '</td>';
                html += '<td>' + item.date_created + '</td>';
                html += '</tr>';
            }

            set_body(html);

            $('#pagination').html(data.pagination);

            $('#pagination a').click(function(e) {
                e.preventDefault();
                e.stopPropagation();

                load_list($(this).attr('href'));
            });

            $('.select_all').attr('checked', false);
        }
     
        var clear_filters = function(callback) {
            $('select[name="filter_user_id"]').val('');
            $('select[name="filter_type"]').val('');
            $('select[name="filter_group"]').val('');
            $('input[name="filter_start"]').val('');
            $('input[name="filter_end"]').val('');
            $('.select_all').attr('checked', false);

            if (callback) callback();
        }

        var get_filters = function() {
            var result = [];

            if ($('select[name="filter_user_id"]').val() != "") {
                result.push("filter_user_id=" + $('select[name="filter_user_id"]').val());
            }

            if ($('select[name="filter_type"]').val() != "") {
                result.push("filter_type=" + $('select[name="filter_type"]').val());
            }

            if ($('select[name="filter_group"]').val() != "") {
                result.push("filter_group=" + $('select[name="filter_group"]').val());
            }

            if ($('input[name="filter_start"]').val() != "") {
                result.push("filter_start=" + $('input[name="filter_start"]').val());
            }

            if ($('input[name="filter_end"]').val() != "") {
                result.push("filter_end=" + $('input[name="filter_end"]').val() + " 23:59:59");
            }

            return result.length ? '&' + result.join('&') : '';
        }

        var load_list = function(page, callback) {
            page = page ? page : 1;

            $.ajax({
                url: "index.php?route=" + module_path + "/list_events&token=" + token + "&page=" + page + get_filters(),
                type: "GET",
                dataType: "json",
                beforeSend: function() {
                    set_body('<tr><td colspan="6" class="center"><i class="fa fa-spinner fa-pulse"></i> ' + text_loading + '</td></tr>');
                },
                success: function(data) {
                    if (typeof data == 'object') {
                        render_data(data);
                    }
                },
                complete: function() {
                    if (callback) callback();
                }
            });
        }

        var delete_selected = function(callback) {
            $.ajax({
                url: "index.php?route=" + module_path + "/delete_events&token=" + token,
                type: "POST",
                dataType: "json",
                data: $('input[name="delete_event[]"]:checked'),
                beforeSend: function() {
                    set_body('<tr><td colspan="6" class="center"><i class="fa fa-spinner fa-pulse"></i> ' + text_loading + '</td></tr>');
                },
                success: function(data) {
                    if (typeof data.error != 'undefined') {
                        alert(data.error);
                    }
                },
                complete: function() {
                    if (callback) callback();
                }
            });
        }

        $('.date').datetimepicker({
            pickTime: false,
			format: 'YYYY-MM-DD'
        }).on('change', function() {
            load_list();
        });

        $('select[name^="filter_"]').change(function(e) {
            load_list();
        });

        $('#clear_filters').click(function(e) {
            e.preventDefault();
            e.stopPropagation();
            clear_filters(load_list);
        });

        $('#delete_selected').click(function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (confirm(text_confirm)) {
                delete_selected(load_list);
            }
        });

        $('.select_all').change(function(e) {
            var item = this;
            $('input[name="delete_event[]"]').each(function(index, element) {
                element.checked = $(item).is(':checked');
            });
        });

        load_list();
    }
}