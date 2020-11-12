<?php echo $header; ?>

<style>

div#tab-images td{

  display: inline-table;

}

div#tab-images .thumbnail{

  margin: 3px;

}

div#alert-view div.alert{

  color : #000;

}

div#alert-view div.col-sm-10{

  padding: 0px;

}

@media (max-width: 767px) {

  div#alert-view .pull-right{

    float: none !important;

  }

}

</style>

<?php echo $column_left; ?>

<div id="content">

  <div class="page-header">

    <div class="container-fluid">

      <div class="pull-right">

        <a href="<?php echo $invoice; ?>" target="_blank" data-toggle="tooltip" title="<?php echo $button_invoice ; ?>" class="btn btn-info"><i class="fa fa-print"></i></a>

        <a href="<?php echo $back; ?>" data-toggle="tooltip" title="<?php echo $button_back; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>

      <h1><?php echo $heading_title; ?></h1>

      <ul class="breadcrumb">

        <?php foreach ($breadcrumbs as $breadcrumb) { ?>

        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>

        <?php } ?>

      </ul>

    </div>

  </div>



  <div class="container-fluid">

    <?php if ($success) { ?>

    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>

      <button type="button" class="close" data-dismiss="alert">&times;</button>

    </div>

    <?php } ?>

    <?php if ($error_warning) { ?>

    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>

      <button type="button" class="close" data-dismiss="alert">&times;</button>

    </div>

    <?php } ?>

    <div class="panel panel-default">

      <div class="panel-heading">

        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>

      </div>



      <div class="panel-body">

        <ul class="nav nav-tabs">

          <li class="<?php if($tab == '') echo 'active'; ?>"><a href="#tab-general" data-toggle="tab"><?php echo $text_admin_basic; ?></a></li>

          <li><a href="#tab-product" data-toggle="tab"><?php echo $text_admin_product; ?></a></li>

          <li class="<?php if($tab == 'messages') echo 'active'; ?>"><a href="#tab-messages" data-toggle="tab"><?php echo $text_admin_msg_tab; ?></a></li>

          <li><a href="#tab-images" data-toggle="tab"><?php echo $text_admin_images; ?></a></li>

          <li class="<?php if($tab == 'shipping') echo 'active'; ?>"><a href="#tab-shipping" data-toggle="tab"><?php echo $text_shipping_label; ?></a></li>

          <?php if (isset($checkIfCustomer) && $checkIfCustomer) { ?>

            <li><a href="#tab-payment" data-toggle="tab"><?php echo $text_tab_payment; ?></a></li>

          <?php } ?>

        </ul>





        <div class="tab-content">

          <?php if (isset($checkIfCustomer) && $checkIfCustomer) { ?>

          <div id="tab-payment" class="tab-pane form-horizontal">

            <table class="table table-bordered">

              <tbody>

                <tr>

                   <td><?php echo $text_voucher_balance ?> :</td>

                   <td id="voucher_bal"><?php echo $voucher_total; ?></td>

                </tr>

              </tbody>

            </table>

            <div id="transaction"></div>

            <br />

            <div class="form-group">

              <label class="col-sm-2 control-label" for="input-transaction-description"><?php echo $entry_description; ?></label>

              <div class="col-sm-10">

                <textarea type="text" name="description" placeholder="<?php echo $entry_description; ?>" id="input-transaction-description" class="form-control" ><?php echo $default_text_desc; ?></textarea>

              </div>

            </div>

            <div class="form-group">

              <label class="col-sm-2 control-label" for="input-amount"><?php echo $entry_amount; ?></label>

              <div class="col-sm-10">

                <input type="text" name="amount" value="" placeholder="<?php echo $entry_amount; ?>" id="input-amount" class="form-control" />

              </div>

            </div>

            <div class="text-right">

              <button type="button" id="button-transaction" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_transaction_add; ?></button>

              <button type="button" id="button-voucher" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_voucher_add; ?></button>

            </div>

          </div>

        <?php } ?>

          <div id="tab-general" class="tab-pane <?php if($tab == '') echo 'active'; ?>">

            <table class="table table-bordered">

              <?php if($result_rmaadmin) { ?>

                <?php $result_rmaadmins = $result_rmaadmin; ?>

              <tbody>

                <tr>

                   <td><?php echo $text_admin_oid ?> :</td>

                   <td><a href="<?php echo $result_rmaadmins['orderurl']; ?>" target="_blank"><?php echo  ucfirst($result_rmaadmins['oid']); ?></a><input type="hidden" name="rma_id" value="<?php echo $result_rmaadmins['id']; ?>"></td>

                </tr>



                <tr>

                  <td><?php echo $text_admin_rmastatus; ?> :</td>

                  <td style="color:<?php echo $result_rmaadmins['color']; ?>;font-weight:bold;"><?php echo $result_rmaadmins['rmastatus']; ?></td>

                </tr>



                <tr>

                  <td><?php echo $text_admin_cname; ?> :</td>

                  <td><?php echo  ucwords($result_rmaadmins['name']); ?></td>

                </tr>



                <tr>

                  <td><?php echo $text_admin_add_info; ?> :</td>

                  <td><?php echo  ucfirst($result_rmaadmins['add_info']); ?></td>

                </tr>

                <tr>

                  <td><?php echo $text_admin_authno ?> :</td>

                  <td><?php echo  $result_rmaadmins['auth_no']; ?></td>

                </tr>

                <?php } ?>

              </tbody>

            </table>

          </div>



          <!-- product tab -->

          <div class="tab-pane" id="tab-product">

            <?php if($result_products){ ?>

            <table class="table table-bordered table-hover">

              <thead>

                <tr>

                    <td></td>

                    <td class="left"><?php echo $entry_name; ?></td>

                    <td class="left"><?php echo $text_admin_model; ?></td>

                    <td class="left"><?php echo $text_admin_return; ?></td>

                    <td class="left"><?php echo $text_admin_reason; ?></td>

                    <td class="left"><?php echo $text_admin_qty_shipped; ?></td>

                    <td class="left"><?php echo $text_admin_unit_price; ?></td>

                    <td class="left"><?php echo $text_admin_shipped_total; ?></td>

                    <td class="left"><?php echo $text_admin_total_price; ?></td>

                </tr>

              </thead>

              <?php $i = 0; ?>

              <?php foreach($result_products as $products){ 
                $i++; 
                ?>

              <tr id="refund_row_<?php echo $i; ?>">

                <td class="left"><input type="checkbox" value="<?php echo $i; ?>" class="product-refund-checkbox" name="product_refund[]"></td>

                <td class="left product-name"><?php echo $products['name']; ?></td>

                <td class="left product-model"><?php echo $products['model']; ?></td>

                <td class="left"><?php echo $products['quantity']; ?></td>

                <td class="left"><?php echo $products['reason']; ?></td>

                <td class="left"><?php echo $products['quantity_shipped']; ?></td>

                <td class="left"><?php echo $products['price']; ?></td>

                <td class="left"><?php echo $products['total_shipped']; ?></td>

                <td class="left total-refund-amount"><input type='number' class="total-refund-amount-raw form-control" value="<?php if ($products['refund_amount'] > 0) echo $products['refund_amount']; else echo $products['total_raw']; ?>"></td>

                <input type="hidden" class="product-id" value="<?php echo $products['product_id']; ?>">

                <input type="hidden" class="quant" value="<?php echo $products['quantity']; ?>">

              </tr>

              <?php } ?>



            </table>

            <?php } ?>





            <div class="panel panel-default">

              <div class="panel-heading">

                <h3 class="panel-title"><i class="fa fa-comment-o"></i> <?php echo $text_history; ?></h3>

              </div>

              <div class="panel-body">

                <ul class="nav nav-tabs">

                  <li class="active"><a href="#tab-history" data-toggle="tab"><?php echo $tab_history; ?></a></li>

                  <li><a href="#tab-additional" data-toggle="tab"><?php echo $tab_additional; ?></a></li>

                  <li><a href="#tab-sales-person" data-toggle="tab"><?php echo $tab_sales_person; ?></a></li>

                  <?php foreach ($tabs as $tab) { ?>

                  <li><a href="#tab-<?php echo $tab['code']; ?>" data-toggle="tab"><?php echo $tab['title']; ?></a></li>

                  <?php } ?>

                </ul>

                <div class="tab-content">

                  <div class="tab-pane active" id="tab-history">

                    <div id="history"></div>

                    <br />

                    <fieldset>

                      <legend><?php echo $text_history_add; ?></legend>

                      <form class="form-horizontal">

                        <div class="form-group">

                          <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>

                          <div class="col-sm-10">

                            <select name="order_status_id" id="input-order-status" class="form-control">

                              <?php foreach ($order_statuses as $order_statuses) { ?>

                              <?php if ($order_statuses['order_status_id'] == $order_status_id) { ?>

                              <option value="<?php echo $order_statuses['order_status_id']; ?>" selected="selected"><?php echo $order_statuses['name']; ?></option>

                              <?php } else { ?>

                              <option value="<?php echo $order_statuses['order_status_id']; ?>"><?php echo $order_statuses['name']; ?></option>

                              <?php } ?>

                              <?php } ?>

                            </select>

                          </div>

                        </div>

                        <div class="form-group">

                          <label class="col-sm-2 control-label" for="input-override"><span data-toggle="tooltip" title=""><?php echo $entry_override; ?></span></label>

                          <div class="col-sm-10">

                            <input type="checkbox" name="override" value="1" id="input-override" />

                          </div>

                        </div>

                        <div class="form-group">

                          <label class="col-sm-2 control-label" for="input-stock"><?php echo $entry_back_to_stock; ?></span></label>

                          <div class="col-sm-10">

                            <input type="checkbox" name="stock" value="1" id="input-stock" />

                          </div>

                        </div>

                        <div class="form-group">

                          <label class="col-sm-2 control-label" for="input-refund"><?php echo $entry_apply_refund; ?></span></label>

                          <div class="col-sm-10">

                            <input type="checkbox" name="refund" value="1" id="input-refund" />

                          </div>

                        </div>

                        <div class="form-group">

                          <label class="col-sm-2 control-label" for="input-notify"><?php echo $entry_notify; ?></label>

                          <div class="col-sm-10">

                            <input type="checkbox" name="notify" value="1" id="input-notify" />

                          </div>

                        </div>

                        <div class="form-group">

                          <label class="col-sm-2 control-label" for="input-comment"><?php echo $entry_comment; ?></label>

                          <div class="col-sm-10">

                            <textarea name="comment" rows="8" id="input-comment" class="form-control"></textarea>

                          </div>

                        </div>

                        <input type="hidden" value="<?=$logedin_user?>" name="username"/>

                      </form>

                    </fieldset>

                    <div class="text-right">

                      <button id="button-history" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_history_add; ?></button>

                    </div>

                  </div>

                  <div class="tab-pane" id="tab-additional">

                    <?php if ( !empty( $account_custom_fields ) ) { ?>

                    <table class="table table-bordered">

                      <thead>

                        <tr>

                          <td colspan="2"><?php echo $text_account_custom_field; ?></td>

                        </tr>

                      </thead>

                      <tbody>

                        <?php foreach ($account_custom_fields as $custom_field) { ?>

                        <tr>

                          <td><?php echo $custom_field['name']; ?></td>

                          <td><?php echo $custom_field['value']; ?></td>

                        </tr>

                        <?php } ?>

                      </tbody>

                    </table>

                    <?php } ?>

                    <?php if ( !empty( $payment_custom_fields ) ) { ?>

                    <table class="table table-bordered">

                      <thead>

                        <tr>

                          <td colspan="2"><?php echo $text_payment_custom_field; ?></td>

                        </tr>

                      </thead>

                      <tbody>

                        <?php foreach ($payment_custom_fields as $custom_field) { ?>

                        <tr>

                          <td><?php echo $custom_field['name']; ?></td>

                          <td><?php echo $custom_field['value']; ?></td>

                        </tr>

                        <?php } ?>

                      </tbody>

                    </table>

                    <?php } ?>

                    <?php if ( isset( $shipping_method ) && !empty( $shipping_custom_fields ) ) { ?>

                    <table class="table table-bordered">

                      <thead>

                        <tr>

                          <td colspan="2"><?php echo $text_shipping_custom_field; ?></td>

                        </tr>

                      </thead>

                      <tbody>

                        <?php foreach ($shipping_custom_fields as $custom_field) { ?>

                        <tr>

                          <td><?php echo $custom_field['name']; ?></td>

                          <td><?php echo $custom_field['value']; ?></td>

                        </tr>

                        <?php } ?>

                      </tbody>

                    </table>

                    <?php } ?>

                    <table class="table table-bordered">

                      <thead>

                        <tr>

                          <td colspan="2"><?php echo $text_browser; ?></td>

                        </tr>

                      </thead>

                      <tbody>

                        <tr>

                          <td><?php echo $text_ip; ?></td>

                          <td><?php echo $ip; ?></td>

                        </tr>

                        <?php if ($forwarded_ip) { ?>

                        <tr>

                          <td><?php echo $text_forwarded_ip; ?></td>

                          <td><?php echo $forwarded_ip; ?></td>

                        </tr>

                        <?php } ?>

                        <tr>

                          <td><?php echo $text_user_agent; ?></td>

                          <td><?php echo $user_agent; ?></td>

                        </tr>

                        <tr>

                          <td><?php echo $text_accept_language; ?></td>

                          <td><?php echo $accept_language; ?></td>

                        </tr>

                      </tbody>

                    </table>

                  </div>

                  

                  <div class="tab-pane" id="tab-sales-person">

                    

                    <div id="sp"></div>

                    

                    <form class="form-horizontal">

                        <div class="form-group">

                          <label class="col-sm-2 control-label" for="input-sales-person"><?php echo $entry_sales_person; ?></label>

                          <div class="col-sm-10">

                            <select name="sales_person" id="input-sales-person" class="form-control">

                              <option value="*"></option>

                              <?php foreach ($sales_persons as $sp) { ?>

                              <?php if ($sp['user_id'] == $sales_person) { ?>

                              <option value="<?php echo $sp['user_id']; ?>" selected="selected"><?php echo $sp['name']; ?></option>

                              <?php } else { ?>

                              <option value="<?php echo $sp['user_id']; ?>"><?php echo $sp['name']; ?></option>

                              <?php } ?>

                              <?php } ?>

                            </select>

                          </div>

                        </div>

                      </form>

                    

                    <div class="text-right">

                      <button id="button-sales-person" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $btn_sales_person; ?></button>

                    </div>

                  </div>

                  

                  

                  <?php foreach ($tabs as $tab) { ?>

                  <div class="tab-pane" id="tab-<?php echo $tab['code']; ?>"><?php echo $tab['content']; ?></div>

                  <?php } ?>

                </div>

              </div>

            </div><!--/.panel -->





          </div>



          <!-- messages tab -->

          <div class="tab-pane <?php if($tab == 'messages') echo 'active'; ?>" id="tab-messages">



            <div class="well">

              <div class="row">

                <div class="col-sm-6">

                  <div class="form-group">

                    <label class="control-label" for="input-assign_to"><?php echo $entry_message_by; ?></label>

                    <select name="filter_name" class="form-control" id="input-assign_to">

                      <option value="*"></option>

                      <option value="me" <?php echo $filter_name=='me' ? 'selected' : ''; ?> ><?php echo $result_rmaadmins['name']; ?></option>

                      <option value="admin" <?php echo $filter_name=='admin' ? 'selected' : ''; ?>><?php echo 'Admin'; ?></option>

                    </select>

                  </div>

                  <div class="form-group" style="display:none">

                    <label class="control-label" for="input-assign_to"><?php echo $text_admin_msg; ?></label>

                    <input type="text" name="filter_message" value="<?php echo $filter_message; ?>" placeholder="<?php echo $text_admin_msg; ?>" id="input-msg" class="form-control" />

                  </div>

                </div>



                <div class="col-sm-6">

                  <div class="form-group date">

                      <label class="control-label" for="input-assign_to"><?php echo $text_admin_date; ?></label>

                      <div class='input-group date'>

                      <input type='text' name="filter_date" value="<?php echo $filter_date; ?>" placeholder="<?php echo $text_admin_date; ?>" id="input-date" class="form-control" />

                      <span class="input-group-btn">

                      <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>

                      </span>

                    </div>

                  </div>

                  <div class="btn-group">

                    <button type="button" id="alert-view-button" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_alert; ?>"><i class="fa fa-th-list"></i></button>

                    <button type="button" id="table-view-button" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_table ; ?>"><i class="fa fa-th"></i></button>

                  </div>



                  <button type="button" onclick="filter();" class="btn btn-primary"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>

                  <button type="button" onclick="clrfilter();" class="btn btn-primary"><i class="fa fa-search"></i> <?php echo $button_clrfilter; ?></button>

                </div>

              </div>

            </div>



            <!-- table view -->

            <div class="table-responsive hide" id="table-view">

              <table class="table table-bordered table-hove">

                <thead>

                  <tr>

                    <td class="text-left">

                      <?php if ($sort == 'wrm.writer') { ?>

                        <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $text_admin_cname; ?></a>

                      <?php } else { ?>

                        <a href="<?php echo $sort_name; ?>" > <?php echo $text_admin_cname; ?> </a>

                      <?php } ?>

                    </td>

                    <td class="text-left">

                      <?php if ($sort == 'wrm.message') { ?>

                        <a href="<?php echo $sort_message; ?>" class="<?php echo strtolower($order); ?>"><?php echo $text_admin_msg; ?></a>

                      <?php } else { ?>

                        <a href="<?php echo $sort_message; ?>" > <?php echo $text_admin_msg; ?> </a>

                      <?php } ?>

                    </td>

                    <td class="text-left">

                      <?php if ($sort == 'wrm.date') { ?>

                        <a href="<?php echo $sort_date; ?>" class="<?php echo strtolower($order); ?>"><?php echo $text_admin_date; ?></a>

                      <?php } else { ?>

                        <a href="<?php echo $sort_date; ?>" > <?php echo $text_admin_date; ?> </a>

                      <?php } ?>

                    </td>

                    <td class="text-left">

                      <?php echo $text_download; ?>

                    </td>

                    <td class="text-left">

                      <?php echo "Customer Notified"; ?>

                    </td>

                  </tr>

                </thead>

                <?php if($results_message) { ?>

                  <?php foreach ($results_message as $results_messages) { ?>

                <tbody>

                  <tr>

                    <td class="text-left"><?php if($results_messages['writer']=='me'){ echo $result_rmaadmins['name']; }else{ echo ucfirst($results_messages['writer']);}?></td>

                    <td class="text-left " ><?php echo $results_messages['message']; ?></td>

                    <td class="text-left"><?php echo $results_messages['date']; ?></td>

                    <td class="text-left">

                      <?php if($results_messages['attachment']){ ?>

                        <a href="<?php echo $attachmentLink.$results_messages['attachment'];?>" data-toggle="tooltip" title="<?php echo $text_download; ?>" class="text-info" target="_blank"><i class="fa fa-download"></i> <?php echo $results_messages['attachment']; ?></a>

                      <?php } ?>

                    </td>

                    <td class="text-left">

                      <?php 
                      if (is_null($results_messages['is_notify']) || $results_messages['is_notify'] == 0)
                        echo "No";
                      else
                        echo "Yes";
                       ?>

                    </td>

                  </tr>

                </tbody>

                <?php } }else{ ?>

                  <tbody>

                    <tr><td class="text-center" colspan="4"><?php echo $text_no_recored; ?></td></tr>

                  </tbody>

                <?php } ?>



              </table>

            </div>





            <!-- alert view -->

            <div id="alert-view" class="hide">

              <?php foreach($results_message as $res_message){ ?>

                <?php if($res_message['writer']!='admin'){ ?>

                  <div class="col-sm-10">

                    <div class="alert alert-success">

                      <label data-toggle="tooltip" title="<?php echo ucfirst($result_rmaadmins['name']); ?>"><i class="fa fa-user"></i> </label>

                      <label class="pull-right" ><i class="fa fa-clock-o"></i> <?php echo $res_message['date']; ?></label>

                      <br/>

                      <?php echo $res_message['message']; ?>

                      <?php if($res_message['attachment']){ ?>

                      <br/>

                        <a href="<?php echo $attachmentLink.$res_message['attachment'];?>" data-toggle="tooltip" title="<?php echo $text_download; ?>" class="text-info" target="_blank"><i class="fa fa-download"></i> <?php echo $res_message['attachment']; ?></a>

                      <?php } ?>

                    </div>

                  </div>

                <?php }else{ ?>

                  <div class="col-sm-10 pull-right">

                    <div class="alert alert-info">

                    <label data-toggle="tooltip" title="<?php echo ucfirst($res_message['writer']); ?>"><i class="fa fa-home"></i></label>

                    <label class="pull-right"><i class="fa fa-clock-o"></i> <?php echo $res_message['date']; ?></label>

                    <br/>

                    <?php echo $res_message['message']; ?>

                    <?php if($res_message['attachment']){ ?>

                    <br/>

                      <a href="<?php echo $attachmentLink.$res_message['attachment'];?>" target="_blank" data-toggle="tooltip" title="<?php echo $text_download; ?>" class="text-success"><i class="fa fa-download"></i> <?php echo $res_message['attachment']; ?></a>

                    <?php } ?>

                  </div>

                  </div>

                <?php } ?>

              <?php } ?>

            </div>





            <div class="row">

              <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>

              <div class="col-sm-6 text-right"><?php echo $results; ?></div>

            </div>



            <form action="<?php echo $save; ?>" method="post" enctype="multipart/form-data" class="form_msg">



              <div class="row required">

                <label class="col-sm-12 text-left control-label"><?php echo $text_admin_adminstatus; ?></label>

                <div class="col-sm-12">

                  <select name="wk_rma_admin_adminstatus" class="form-control">

                  <?php if($admin_status){ ?>

                    <?php foreach($admin_status as $admin_st){ ?>

                      <option <?php if($admin_st['status_id'] == $result_rmaadmins['admin_status']){ echo 'selected'; }?> value="<?php echo $admin_st['status_id']; ?>"><?php echo $admin_st['name']; ?></option>

                    <?php } ?>

                  <?php }else{ ?>

                      <?php echo $text_no_option ?>

                  <?php } ?>

                  </select>

                </div>

              </div>

              <br/>

              <div class="row">

                          <label class="col-sm-2 control-label" for="input-notify_message"><?php echo $entry_notify; ?></label>

                          <div class="col-sm-10">

                            <input type="checkbox" name="input-notify_message" value="1" id="input-notify_message" />

                          </div>

                        </div>

              <div class="row required">

                <label class="col-sm-12 text-left control-label"><?php echo $text_admin_msg; ?></label>

                <div class="col-sm-12">

                  <input type="hidden" name="rma_id" value="<?php echo $vid; ?>">

                  <textarea name="wk_rma_admin_msg" rows="4" class="form-control" required><?php echo $wk_rma_admin_msg; ?></textarea>

                  <br/>





                  <div class="input-group col-sm-12">

                    <span class="input-group-btn">

                      <label type="button" class="btn btn-primary" for="file-upload"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></label>

                    </span>

                    <input type="text" id="input-file-name" class="form-control" disabled/>

                  </div>



                  <input type="file" id="file-upload" name="up_file" class="form-control hide">

                  <br/>



                  <button type="submit" class="btn btn-primary pull-right" data-toggle="tooltip" title="<?php echo $wk_viewrma_msg; ?>"><i class="fa fa-save"></i></button>



                </div>

              </div>

            </form>

          </div>



          <!-- images tab -->

          <div class="tab-pane" id="tab-images">

            <?php if($result_rmaadmin_images){ ?>

            <div class="table-responsive">

              <table>

                <tbody class="thumbnails">

                  <tr>

                    <?php foreach($result_rmaadmin_images as $images){ ?>

                      <?php if($images['image']){ ?>

                        <td><a href="<?php echo $images['image']; ?>" data-toggle="tooltip" title="View Images" class="thumbnail"><img src="<?php echo $images['resize']; ?>"></a></td>

                      <?php } ?>

                    <?php } ?>

                  </tr>

                </tbody>

              </table>

            </div>

            <?php } ?>

          </div>



          <!-- shipping tab -->

          <div class="tab-pane <?php if($tab == 'shipping') echo 'active'; ?>" id="tab-shipping">

            <?php if(isset($text_shipping_info) && $text_shipping_info) { ?>

            <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> <?php echo $text_shipping_info; ?>

            </div>

          <?php } ?>



            <?php if($result_rmaadmin['shipping_label']){ ?>

              <div class="text-center">

                <img src="<?php echo $result_rmaadmin['shipping_label']; ?>" />

              </div>

              <br/><br/>

            <?php } ?>



            <form action="<?php echo $savelabel ;?>" method="post" enctype="multipart/form-data" class="form-horizontal">



              <button type="submit" class="btn btn-primary pull-right" data-toggle="tooltip" title="<?php echo $wk_viewrma_shipping_label; ?>"><i class="fa fa-save"></i></button>



              <div class="input-group col-sm-11">

                <span class="input-group-btn">

                  <label type="button" class="btn btn-primary" for="shipping-upload"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></label>

                </span>

                <input type="text" id="input-shipping-name" class="form-control" disabled/>

                <input type="file" id="shipping-upload" name="shipping_label" class="form-control hide">

              </div>

              <br/>

            </form>

          </div>

        </div>

      </div>

    </div><!--/.panel -->



  </div>

</div>

<?php echo $footer; ?>

<script type="text/javascript">



  var token = '';



// Login to the API

$.ajax({

  url: "<?php echo $store_url; ?>index.php?route=api/login",

  type: 'post',

  dataType: 'json',

  data: 'key=<?php echo $api_key; ?>',

  crossDomain: true,

  success: function(json) {

    $('.alert').remove();



        if (json['error']) {

        if (json['error']['key']) {

          $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['key'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

        }



            if (json['error']['ip']) {

          $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['ip'] + ' <button type="button" id="button-ip-add" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-danger btn-xs pull-right"><i class="fa fa-plus"></i> <?php echo $button_ip_add; ?></button></div>');

        }

        }



        if (json['token']) {

      token = json['token'];

    }

  },

  error: function(xhr, ajaxOptions, thrownError) {

    //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);

  }

});



$(document).delegate('#button-ip-add', 'click', function() {

  $.ajax({

    url: 'index.php?route=user/api/addip&token=<?php echo $token; ?>&api_id=<?php echo $api_id; ?>',

    type: 'post',

    data: 'ip=<?php echo $api_ip; ?>',

    dataType: 'json',

    beforeSend: function() {

      $('#button-ip-add').button('loading');

    },

    complete: function() {

      $('#button-ip-add').button('reset');

    },

    success: function(json) {

      $('.alert').remove();



      if (json['error']) {

        $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

      }



      if (json['success']) {

        $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

      }

    },

    error: function(xhr, ajaxOptions, thrownError) {

      //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);

    }

  });

});



  $('#history').delegate('.pagination a', 'click', function(e) {

  e.preventDefault();



  $('#history').load(this.href);

});



$('#history').load('index.php?route=sale/order/history&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');



$('#button-history').on('click', function() {

  /*if( $('.product-refund-checkbox:checked').length == 0 ) {

    alert('Please select at least one item to refund.');

    return false;

  }*/

  var refund_enable = false;

  var refund_amount = 0;



  if( $('input[name="refund"]').is(':checked') ) {

    refund_enable = true;

    //alert (refund_enable);

      if( $('.product-refund-checkbox:checked').length == 0 ) {

        //alert('Please select at least one item to refund.');

        //return false;

    }else{

      //do update refund details table. Ticket # 272

      var lines_refund = [];

      

      $('.product-refund-checkbox:checked').each(function(itm) {

      var prod_id = $('#refund_row_' + this.value + ' .product-id').val();  

      refund_amount = $('#refund_row_' + this.value + ' .total-refund-amount-raw').val();

      refund_amount = parseFloat(refund_amount);

      lines_refund.push(prod_id + '=' + refund_amount);

    });



      

     // alert(refund_amount);

     var encodedData = lines_refund.join('&');

      $.ajax({

              url: "index.php?route=module/product_labels/addRefund&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>",

              type: 'post',

              dataType: 'json',

              data: encodedData,

              beforeSend: function() {

                $('#button-history').button('loading');

              },

              complete: function() {

                $('#button-history').button('reset');

              },

              success: function(json) {

                //alert(JSON.stringify(json));

              },

              error: function(xhr, ajaxOptions, thrownError) {

                //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);

              }

            });





    } //add refund ends

}

  





  if( $('input[name="stock"]').is(':checked') ) {



    if( $('.product-refund-checkbox:checked').length == 0 ) {

    //alert('Please select at least one item to refund.');

    //return false;

  }



    var comment_txt = 'Added back to stock';



    if( $('input[name="refund"]').is(':checked') ) {

      comment_txt += ' and Applied Refund to';

    }



    comment_txt += ' the following item(s): ' + '\n';



    var tot_refund_amount = 0.00;



    // loop through each checked item

    $('.product-refund-checkbox:checked').each(function(itm) {

      var prod_model = $('#refund_row_' + this.value + ' .product-model').text();

      var prod_name = $('#refund_row_' + this.value + ' .product-name').text();

      var total_refund = $('#refund_row_' + this.value + ' .total-refund-amount-raw').val()
      total_refund = parseFloat(total_refund);

      comment_txt += prod_model + ' - ' + prod_name + ': $' + total_refund + '\n';

      tot_refund_amount += parseFloat($('#refund_row_' + this.value + ' .total-refund-amount-raw').val())

    });


    total_line = '\n' + 'Total for return: $' + parseFloat(tot_refund_amount) + '\n';
    
    comment_txt += total_line;



    $('textarea[name="comment"]').val(comment_txt);



  }



  if (typeof verifyStatusChange == 'function'){

    if (verifyStatusChange() == false){

      return false;

    } else{

      addOrderInfo();

    }

  } else{

    addOrderInfo();

  }

  $.ajax({

    url: "<?php echo $store_url; ?>index.php?route=api/order/history&token="+token+"&order_id=<?php echo $order_id; ?>",

    type: 'post',

    dataType: 'json',

    data: 'order_status_id=' + encodeURIComponent($('select[name=\'order_status_id\']').val()) + '&notify=' + ($('input[name=\'notify\']').prop('checked') ? 1 : 0) + '&override=' + ($('input[name=\'override\']').prop('checked') ? 1 : 0) + '&append=' + ($('input[name=\'append\']').prop('checked') ? 1 : 0) + '&username=' + encodeURIComponent($('input[name=\'username\']').val()) + '&comment=' + encodeURIComponent($('textarea[name=\'comment\']').val()),

    beforeSend: function() {

      $('#button-history').button('loading');

    },

    complete: function() {

      $('#button-history').button('reset');

      // If Add to stock is checked, add quantity back to stock

      if( $('input[name="stock"]').is(':checked') ) {

        // loop through each checked item

        if( $('input[name="stock"]').is(':checked') ) {
          //alert ("ADDING STOCK BACK");

          // loop through each checked item

          var prod_to_add = [];

          $('.product-refund-checkbox:checked').each(function(itm) {

            var prod_id = $('#refund_row_' + this.value + ' .product-id').val();

            var quant = $('#refund_row_' + this.value + ' .quant').val();

            prod_to_add.push(prod_id + '=' + quant);

          });

          var encodedData = prod_to_add.join('&');

          setTimeout(function() {

            $.ajax({

              url: "index.php?route=module/product_labels/addStock&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>",

              type: 'post',

              dataType: 'json',

              data: encodedData,

              beforeSend: function() {

                $('#button-history').button('loading');

              },

              complete: function() {

                $('#button-history').button('reset');

              },

              success: function(json) {
              /*  if (json.code == 101)
                  alert("Stock already exists in system");
                else
                  alert("Stock added to system"); */


              },

              error: function(xhr, ajaxOptions, thrownError) {

                //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);

              }

            });

          }, 500)

        }

      }

    },

    success: function(json) {

      $('.alert').remove();



      if (json['error']) {

        $('#history').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

      }



      if (json['success']) {

        $('#history').load('index.php?route=sale/order/history&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');



        $('#history').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');



        $('textarea[name=\'comment\']').val('');

      }

    },

    error: function(xhr, ajaxOptions, thrownError) {

      //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);

    }

  });

});



function changeStatus(){

  var status_id = $('select[name="order_status_id"]').val();



  $('#openbay-info').remove();



  $.ajax({

    url: 'index.php?route=extension/openbay/getorderinfo&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>&status_id=' + status_id,

    dataType: 'html',

    success: function(html) {

      $('#history').after(html);

    }

  });

}

if (window.top !== window.self) {
    $("#column-left").remove();
    $("#footer").empty(); 
} 

function addOrderInfo(){

  var status_id = $('select[name="order_status_id"]').val();



  $.ajax({

    url: 'index.php?route=extension/openbay/addorderinfo&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>&status_id=' + status_id,

    type: 'post',

    dataType: 'html',

    data: $(".openbay-data").serialize()

  });

}



$('#button-sales-person').on('click', function() {

  



  $.ajax({

    url: 'index.php?route=sale/order/assignsp&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>&sales_person=' + encodeURIComponent($('select[name=\'sales_person\']').val()) + '&username=' + encodeURIComponent($('input[name=\'username\']').val()),

    

    dataType: 'json',     

      success: function(json) {

        $('#sp').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> Success: Order successfully assigned to sales person! <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

        $('#history').load('index.php?route=sale/order/history&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');

      }

    

    

    

  });

});





jQuery('input[name=up_file]').change(function(){

  $('#input-file-name').val(jQuery(this).val().replace(/C:\\fakepath\\/i, ''));

});



jQuery('input[name=shipping_label]').change(function(){

  $('#input-shipping-name').val(jQuery(this).val().replace(/C:\\fakepath\\/i, ''));

});



// Message Alert List

$('#alert-view-button').click(function() {

  localStorage.setItem('display', 'alert');

  $('#alert-view').removeClass('hide');

  $('#table-view').addClass('hide');

});



// Message Table Grid

$('#table-view-button').click(function() {

   localStorage.setItem('display', 'table');

    $('#table-view').removeClass('hide');

    $('#alert-view').addClass('hide');



});



if (localStorage.getItem('display') == 'alert') {

  $('#alert-view').removeClass('hide');

} else {

  $('#table-view').removeClass('hide');

}



$('.date').datetimepicker({

  pickTime: false,

  format:"YYYY-MM-DD"

});



function clrfilter() {

  location = 'index.php?route=catalog/wk_rma_admin/getForm&token=<?php echo $token; ?>&id=<?php echo $result_rmaadmins["id"]; ?>';

  localStorage.setItem('tab', 'tab-messages');

}



function filter() {

  url = 'index.php?route=catalog/wk_rma_admin/getForm&token=<?php echo $token; ?>';



  var filter_name = $('select[name=\'filter_name\']').val();



  if (filter_name != '*') {

    url += '&filter_name=' + encodeURIComponent(filter_name);

  }



  var filter_message = $('input[name=\'filter_message\']').val();



  if (filter_message) {

    url += '&filter_message=' + encodeURIComponent(filter_message);

  }



  var filter_date = $('input[name=\'filter_date\']').val()



  if (filter_date) {

    url += '&filter_date=' + encodeURIComponent(filter_date);

  }



  url += "&id=<?php echo $result_rmaadmins['id']; ?>" ;



  localStorage.setItem('tab', 'tab-messages');



  location = url;

}



$(document).ready(function() {

  if(localStorage.getItem('tab')=='tab-messages'){

    $('a[href="#tab-messages"]').trigger('click');

    localStorage.setItem('tab', '');

  }



  $('.thumbnails').magnificPopup({

    type:'image',

    delegate: 'a',

    gallery: {

      enabled:true

    }

  });

});

<?php if (isset($checkIfCustomer) && $checkIfCustomer) { ?>



$('#transaction').delegate('.pagination a', 'click', function(e) {

  e.preventDefault();

  $('#transaction').load(this.href);

});



$('#transaction').load('index.php?route=catalog/wk_rma_admin/transaction&token=<?php echo $token; ?>&rma_id=<?php echo $result_rmaadmins['id']; ?>');



$('#button-transaction').on('click', function(e) {

  e.preventDefault();

  $.ajax({

    url: 'index.php?route=catalog/wk_rma_admin/addtransaction&token=<?php echo $token; ?>&rma_id=<?php echo $result_rmaadmins['id']; ?>',

    type: 'post',

    dataType: 'json',

    data: 'description=' + encodeURIComponent($('#tab-payment textarea[name=\'description\']').val()) + '&amount=' + encodeURIComponent($('#tab-payment input[name=\'amount\']').val()),

    beforeSend: function() {

      $('.alert.alert-danger').remove();

      $('.alert.alert-success').remove();

      $('#button-transaction').button('loading');

    },

    complete: function() {

      $('#button-transaction').button('reset');

    },

    success: function(json) {

      $('.alert').remove();



      if (json['error']) {

         $('.panel.panel-default').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div></div>');

      }



      if (json['success']) {

        $('.panel.panel-default').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div></div>');



        $('#transaction').load('index.php?route=catalog/wk_rma_admin/transaction&token=<?php echo $token; ?>&rma_id=<?php echo $result_rmaadmins['id']; ?>');



        $('#tab-transaction input[name=\'amount\']').val('');

        $('#tab-transaction input[name=\'description\']').val('');

      }

    }

  });

});

$('#button-voucher').on('click', function(e) {

  e.preventDefault();

  $.ajax({

    url: 'index.php?route=catalog/wk_rma_admin/addVoucher&token=<?php echo $token; ?>&rma_id=<?php echo $result_rmaadmins['id']; ?>',

    type: 'post',

    dataType: 'json',

    data: 'message=' + encodeURIComponent($('#tab-payment textarea[name=\'description\']').val()) + '&amount=' + encodeURIComponent($('#tab-payment input[name=\'amount\']').val()),

    beforeSend: function() {

      $('.alert.alert-danger').remove();

      $('.alert.alert-success').remove();

      $('#button-voucher').button('loading');

    },

    complete: function() {

      $('#button-voucher').button('reset');

    },

    success: function(json) {

      $('.alert').remove();

      if (json['error']) {

         $('.panel.panel-default').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div></div>');

      }

      if (json['success']) {

        $('.panel.panel-default').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div></div>');

        $('#voucher_bal').empty().prepend( json['success']);

        $('#tab-transaction input[name=\'amount\']').val('');

        $('#tab-transaction input[name=\'description\']').val('');

      }

    }

  });

});

<?php } ?>

</script>

