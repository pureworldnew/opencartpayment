<div class="table-responsive">
    <div class="pull-right spacer20">
        <a id="clear_filters" class="btn btn-info"><i class="fa fa-remove"></i> <?php echo $button_clear_filters; ?></a>
        <a id="delete_selected" class="btn btn-danger"><i class="fa fa-trash"></i> <?php echo $button_delete_selected; ?></a>
    </div>
    <table class="table table-bordered table-hover">
        <thead>
            <tr class="active">
                <th class="center"><input type="checkbox" class="select_all" /></th>
                <th><?php echo $table_user; ?></th>
                <th><?php echo $table_message; ?></th>
                <th><?php echo $table_event_type; ?></th>
                <th><?php echo $table_event_group; ?></th>
                <th><?php echo $table_date_created; ?></th>
            </tr>
            <tr class="info">
                <td></td>
                <td><select class="form-control" name="filter_user_id">
                    <option value=""></option>
                    <?php foreach ($filter_users as $filter_user) : ?>
                        <option value="<?php echo $filter_user['user_id']; ?>"><?php echo $filter_user['user_name']; ?></option>
                    <?php endforeach; ?>
                </select></td>
                <td></td>
                <td><select class="form-control" name="filter_type">
                    <option value=""></option>
                    <?php foreach ($filter_types as $filter_type) : ?>
                        <option value="<?php echo $filter_type['type_id']; ?>"><?php echo $filter_type['type_name']; ?></option>
                    <?php endforeach; ?>
                </select></td>
                <td><select class="form-control" name="filter_group">
                    <option value=""></option>
                    <?php foreach ($filter_groups as $filter_group) : ?>
                        <option value="<?php echo $filter_group['group_id']; ?>"><?php echo $filter_group['group_name']; ?></option>
                    <?php endforeach; ?>
                </select></td>
                <td>
                    
                    <div class="input-horizontal input-group date">
                    <input type="text" name="filter_start" placeholder="<?php echo $placeholder_start; ?>" data-format="YYYY-MM-DD" class="form-control" />
                    <span class="input-group-btn">
                    <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                    </span></div>
                    
                    <div class="input-horizontal">
                        &nbsp;<?php echo $adminmonitor_text_to; ?>&nbsp;
                    </div>

                    <div class="input-horizontal input-group date">
                    <input type="text" name="filter_end" placeholder="<?php echo $placeholder_end; ?>" data-format="YYYY-MM-DD" class="form-control" />
                    <span class="input-group-btn">
                    <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                    </span></div>
                    
                </td>
            </tr>
        </thead>
        <tbody id="events">

        </tbody>
    </table>
    <div id="pagination" class="pagination"></div>
</div>
<script type="text/javascript">
    $(window).load(function() {
        adminmonitor.init({
            token: '<?php echo $token; ?>',
            module_path: '<?php echo $module_path; ?>',
            text_loading: '<?php echo $adminmonitor_text_loading; ?>',
            text_confirm: '<?php echo $adminmonitor_text_confirm; ?>'
        });
    });
</script>