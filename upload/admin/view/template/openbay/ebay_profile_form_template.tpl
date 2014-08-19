<?php echo $header; ?><?php echo $menu; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right">
        <a data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn" onclick="$('#form').submit();"><i class="fa fa-check-circle"></i></a>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn"><i class="fa fa-reply"></i></a>
      </div>
      <h1 class="panel-title"><i class="fa fa-file-text fa-lg"></i> <?php echo $text_title_list; ?></h1>
    </div>
    <div class="panel-body">
      <form action="<?php echo $btn_save; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
        <input type="hidden" name="type" value="<?php echo $type; ?>" />
        <input type="hidden" name="ebay_profile_id" value="<?php echo $ebay_profile_id; ?>" />
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
          <li><a href="#tab-template" data-toggle="tab"><?php echo $text_tab_template; ?></a></li>
          <li><a href="#tab-gallery" data-toggle="tab"><?php echo $text_tab_gallery; ?></a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab-general">
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $text_profile_default; ?></label>
              <div class="col-sm-10">
                <input type="hidden" name="default" value="0" />
                <input type="checkbox" name="default" value="1" <?php if ($default == 1){ echo 'checked="checked"'; } ?> />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="name"><?php echo $text_profile_name; ?></label>
              <div class="col-sm-10">
                <input type="text" name="name" value="<?php if (isset($name)){ echo $name; } ?>" placeholder="<?php echo $text_profile_name; ?>" id="name" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="description"><?php echo $text_profile_desc; ?></label>
              <div class="col-sm-10">
                <textarea name="description" class="form-control" rows="3" id="description"><?php if (isset($description)){ echo $description; } ?></textarea>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="tab-template">
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $text_template_choose; ?></label>
              <div class="col-sm-10">
                <select name="data[ebay_template_id]" class="form-control">
                  <option value="None">None</option>
                  <?php foreach($templates as $template){ ?>
                  <?php echo '<option value="'.$template['template_id'].'"'.(($template['template_id'] == $data['ebay_template_id'])?' selected':'').'>'.$template['name'].'</option>'; ?>
                  <?php } ?>
                </select>
                <span class="help-block"><?php echo $text_template_choose_help; ?></span>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="tab-gallery">
            <div class="form-group">
              <label class="col-sm-2 control-label" for="name"><?php echo $text_image_gallery; ?></label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-sm-3">
                    <div class="input-group">
                      <span class="input-group-addon"><?php echo $text_width; ?></span>
                      <input type="text" name="data[ebay_gallery_height]" value="<?php if (isset($data['ebay_gallery_height'])){ echo $data['ebay_gallery_height']; }?>" maxlength="4" class="form-control" />
                      <span class="input-group-addon"><?php echo $text_px; ?></span>
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="input-group">
                      <span class="input-group-addon"><?php echo $text_height; ?></span>
                      <input type="text" name="data[ebay_gallery_width]" value="<?php if (isset($data['ebay_gallery_width'])){ echo $data['ebay_gallery_width']; }?>" maxlength="4" class="form-control" />
                      <span class="input-group-addon"><?php echo $text_px; ?></span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <span class="help-block"><?php echo $text_image_gallery_help; ?></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="name"><?php echo $text_image_thumb; ?></label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-sm-3">
                    <div class="input-group">
                      <span class="input-group-addon">Width</span>
                      <input type="text" name="data[ebay_thumb_height]" value="<?php if (isset($data['ebay_thumb_height'])){ echo $data['ebay_thumb_height']; }?>" maxlength="4" class="form-control" />
                      <span class="input-group-addon">px</span>
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="input-group">
                      <span class="input-group-addon">Height</span>
                      <input type="text" name="data[ebay_thumb_width]" value="<?php if (isset($data['ebay_thumb_width'])){ echo $data['ebay_thumb_width']; }?>" maxlength="4" class="form-control" />
                      <span class="input-group-addon">px</span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <span class="help-block"><?php echo $text_image_thumb_help; ?></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $text_image_super; ?></label>
              <div class="col-sm-10">
                <input type="hidden" name="data[ebay_supersize]" value="0" />
                <input type="checkbox" name="data[ebay_supersize]" value="1" <?php if (isset($data['ebay_supersize']) && $data['ebay_supersize'] == 1){ echo 'checked="checked"'; } ?> />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $text_image_gallery_plus; ?></label>
              <div class="col-sm-10">
                <input type="hidden" name="data[ebay_gallery_plus]" value="0" />
                <input type="checkbox" name="data[ebay_gallery_plus]" value="1" <?php if (isset($data['ebay_gallery_plus']) && $data['ebay_gallery_plus'] == 1){ echo 'checked="checked"'; } ?> />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $text_image_all_ebay; ?></label>
              <div class="col-sm-10">
                <input type="hidden" name="data[ebay_img_ebay]" value="0" />
                <input type="checkbox" name="data[ebay_img_ebay]" value="1" <?php if (isset($data['ebay_img_ebay']) && $data['ebay_img_ebay'] == 1){ echo 'checked="checked"'; } ?> />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $text_image_all_template; ?></label>
              <div class="col-sm-10">
                <input type="hidden" name="data[ebay_img_template]" value="0" />
                <input type="checkbox" name="data[ebay_img_template]" value="1" <?php if (isset($data['ebay_img_template']) && $data['ebay_img_template'] == 1){ echo 'checked="checked"'; } ?> />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $text_image_exclude_default; ?></label>
              <div class="col-sm-10">
                <input type="hidden" name="data[default_img_exclude]" value="0" />
                <input type="checkbox" name="data[default_img_exclude]" value="1" <?php if (isset($data['default_img_exclude']) && $data['default_img_exclude'] == 1){ echo 'checked="checked"'; } ?> />
                <span class="help-block"><?php echo $text_image_exclude_default_help; ?></span>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>