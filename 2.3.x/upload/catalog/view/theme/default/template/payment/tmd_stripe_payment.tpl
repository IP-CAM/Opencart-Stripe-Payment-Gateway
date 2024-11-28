<form method="post" id="payment-form" class="form-horizontal">
    <div class="form-row">
        <legend><?php echo $text_credit_card; ?></legend>
        <div id="card-element">
        </div>
        <div id="card-errors" role="alert"></div>
    </div><br></br>
    <div class="buttons">
      <div class="pull-right">
        <button type="button" data-loading-text="<?php echo $text_loading; ?>" id="confirm-order" class="btn btn-primary"><?php echo $button_confirm;?></button>
      </div>
    </div>
</form>

<script type="text/javascript">
var publishable_key = '<?php echo $publishable_key; ?>';
</script>
<script type="text/javascript" src="catalog/view/javascript/jquery/jquery-tmdstripe.js"></script>

