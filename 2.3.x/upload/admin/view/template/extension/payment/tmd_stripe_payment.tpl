<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-stripe-payment" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach($breadcrumbs as $breadcrumb){ ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <?php echo $getkeyform; ?>
  <div class="container-fluid">
    <?php if($error_warning){ ?>
    <div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i><?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-stripe-payment" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_pay_title; ?></label>
            <div class="col-sm-10">
              <?php foreach ($languages as $language){ ?>
              <div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                <input type="text" name="payment_tmd_stripe_payment_title[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($payment_tmd_stripe_payment_title[$language['language_id']]) ? $payment_tmd_stripe_payment_title[$language['language_id']]['title'] : ''; ?>" placeholder="<?php echo $entry_pay_title; ?>" class="form-control" />
              </div>
              <?php } ?>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-transaction_mode"><?php echo $entry_transaction_mode; ?></label>
            <div class="col-sm-10">
              <select name="payment_tmd_stripe_payment_transaction_mode" id="input-transaction_mode" class="form-control transaction_mode">
                <?php if ($payment_tmd_stripe_payment_transaction_mode=='test'){ ?>
                <option value="test" selected="selected"><?php echo $text_test; ?></option>
                <option value="live"><?php echo $text_live; ?></option>
                <?php  }else{ ?>
                <option value="test"><?php echo $text_test; ?></option>
                <option value="live" selected="selected"><?php echo $text_live; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group test-mode required">
            <label class="col-sm-2 control-label" for="input-test-public-key"><?php echo $entry_test_p_key;?></label>
            <div class="col-sm-10">
              <input type="text" name="payment_tmd_stripe_payment_test_public_key" value="<?php echo $payment_tmd_stripe_payment_test_public_key; ?>" placeholder="<?php echo $entry_test_p_key;?>" id="input-test-secret-key" class="form-control" />
              <?php if ($error_test_public_key) { ?>
              <div class="text-danger"><?php echo $error_test_public_key; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group test-mode required">
            <label class="col-sm-2 control-label" for="input-test-secret-key"><?php echo $entry_test_s_key; ?></label>
            <div class="col-sm-10">
              <input type="text" name="payment_tmd_stripe_payment_test_secret_key" value="<?php echo $payment_tmd_stripe_payment_test_secret_key; ?>" placeholder="<?php echo $entry_test_s_key; ?>" id="input-transaction_key" class="form-control" />
              <?php if ($error_test_secret_key) { ?>
              <div class="text-danger"><?php echo $error_test_secret_key; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group live-mode required">
            <label class="col-sm-2 control-label" for="input-live-public-key"><?php echo $entry_live_p_key; ?></label>
            <div class="col-sm-10">
              <input type="text" name="payment_tmd_stripe_payment_live_public_key" value="<?php echo $payment_tmd_stripe_payment_live_public_key; ?>" placeholder="<?php echo $entry_live_p_key; ?>" id="input-live-public-key" class="form-control" />
              <?php if ($error_live_public_key) { ?>
              <div class="text-danger"><?php echo $error_live_public_key; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group live-mode required">
            <label class="col-sm-2 control-label" for="input-live-secret-key"><?php echo $entry_live_s_key; ?></label>
            <div class="col-sm-10">
              <input type="text" name="payment_tmd_stripe_payment_live_secret_key" value="<?php echo $payment_tmd_stripe_payment_live_secret_key; ?>" placeholder="<?php echo $entry_live_s_key; ?>" id="input-live-secret-key" class="form-control" />
              <?php if ($error_live_secret_key) { ?>
              <div class="text-danger"><?php echo $error_live_secret_key; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-total"><span data-toggle="tooltip" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="payment_tmd_stripe_payment_total" value="<?php echo $payment_tmd_stripe_payment_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
            <div class="col-sm-10">
              <select name="payment_tmd_stripe_payment_order_status_id" id="input-order-status" class="form-control">
                <?php foreach($order_statuses as $order_status){ ?>
                <?php if ($order_status['order_status_id'] == $payment_tmd_stripe_payment_order_status_id){ ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php }else{ ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>                
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
            <div class="col-sm-10">
              <select name="payment_tmd_stripe_payment_geo_zone_id" id="input-geo-zone" class="form-control">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach($geo_zones as $geo_zone){ ?>
                <?php if ($geo_zone['geo_zone_id'] == $payment_tmd_stripe_payment_geo_zone_id){ ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                <?php }else{ ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_subscription_status; ?></label>
            <div class="col-sm-10">
              <select name="payment_tmd_stripe_payment_subscription_status" id="input-status" class="form-control">
                <?php if ($payment_tmd_stripe_payment_subscription_status){ ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php }else{ ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status?></label>
            <div class="col-sm-10">
              <select name="tmd_stripe_payment_status" id="input-status" class="form-control">
                <?php if ($tmd_stripe_payment_status){ ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php }else{ ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-max_capture_dela"><span data-toggle="tooltip" title="<?php echo $help_capture_delay; ?>"><?php echo $entry_max_capture_delay; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="payment_tmd_stripe_payment_max_capture_delay" value="<?php echo $payment_tmd_stripe_payment_max_capture_delay; ?>" placeholder="<?php echo $entry_max_capture_delay; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="payment_tmd_stripe_payment_sort_order" value="<?php echo $payment_tmd_stripe_payment_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-charge-mode"><span data-toggle="tooltip" title="<?php echo $help_charge_mode; ?>"><?php echo $entry_charge_mode; ?></span></label>
            <div class="col-sm-10">
              <select name="payment_tmd_stripe_payment_charge_mode" id="input-charge-mode" class="form-control">
                <?php if ($payment_tmd_stripe_payment_charge_mode=='capture'){ ?>
                <option value="capture" selected="selected"><?php echo $text_capture; ?></option>
                <option value="authoriz"><?php echo $text_authorize; ?></option>
                <?php }else{ ?>
                <option value="capture"><?php echo $text_capture; ?></option>
                <option value="authoriz" selected="selected"><?php echo $text_authorize; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-custom-desc"><?php echo $entry_custom_desc; ?></label>
            <div class="col-sm-10">
              <input type="text" name="payment_tmd_stripe_payment_custom_description" value="<?php echo $payment_tmd_stripe_payment_custom_description; ?>" placeholder="<?php echo $entry_custom_desc; ?>" id="input-custom-desc" class="form-control" />
              Custom Text Show in Stripe Dashboard Available Variable : {fullname}, {order_id}, {total}, {currency} Leave it empty for default description 'Order #ID'
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).on('change','.transaction_mode',function() {
  var transaction_mode = this.value;
  if(transaction_mode=='live') {
    $('.test-mode').hide();
    $('.live-mode').show();
  } else {
    $('.test-mode').show();
    $('.live-mode').hide();
  }
});
$('.transaction_mode').trigger('change');
</script>
{{ footer }} 