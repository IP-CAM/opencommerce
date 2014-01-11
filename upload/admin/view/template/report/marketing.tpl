<?php echo $header; ?>
<div id="container"><?php echo $menu; ?><div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="page-header">
    <div class="container">
      <h1><i class="fa fa-bar-chart-o fa-lg"></i> <?php echo $heading_title; ?></h1>
    </div>
  </div>
  <div class="well">
    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label class="control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
          <input type="date" name="filter_date_start" value="<?php echo $filter_date_start; ?>" id="input-date-start" class="form-control" />
        </div>
        <div class="form-group">
          <label class="control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
          <input type="date" name="filter_date_end" value="<?php echo $filter_date_end; ?>" id="input-date-end" class="form-control" />
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
          <select name="filter_order_status_id" id="input-status" class="form-control">
            <option value="0"><?php echo $text_all_status; ?></option>
            <?php foreach ($order_statuses as $order_status) { ?>
            <?php if ($order_status['order_status_id'] == $filter_order_status_id) { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
        <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
      </div>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table table-bordered">
      <thead>
        <tr>
          <td class="text-left"><?php echo $column_campaign; ?></td>
          <td class="text-left"><?php echo $column_code; ?></td>
          <td class="text-right"><?php echo $column_clicks; ?></td>
          <td class="text-right"><?php echo $column_orders; ?></td>
          <td class="text-right"><?php echo $column_total; ?></td>
        </tr>
      </thead>
      <tbody>
        <?php if ($marketings) { ?>
        <?php foreach ($marketings as $marketing) { ?>
        <tr>
          <td class="text-left"><?php echo $marketing['campaign']; ?></td>
          <td class="text-left"><?php echo $marketing['code']; ?></td>
          <td class="text-right"><?php echo $marketing['clicks']; ?></td>
          <td class="text-right"><?php echo $marketing['orders']; ?></td>
          <td class="text-right"><?php echo $marketing['total']; ?></td>
        </tr>
        <?php } ?>
        <?php } else { ?>
        <tr>
          <td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
  <div class="row">
    <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
  </div>
</div>
<script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=report/marketing&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	
	var filter_order_status_id = $('select[name=\'filter_order_status_id\']').val();
	
	if (filter_order_status_id != 0) {
		url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
	}	

	location = url;
});
//--></script> 
<?php echo $footer; ?>