<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-pp-pro-uk" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
            </div>
            <div class="panel-body">
                <div class="col-md-offset-2 col-md-10">
                    <p><?php echo $desc_configure; ?></p>
                    <p><?php echo $desc_details; ?></p>
                    <p><?php echo $desc_questions; ?></p>
                    <p><?php echo $desc_account; ?></p>
                </div>
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-pp-pro-uk" class="form-horizontal">
                    <div class="form-group required">
                        <label class="col-sm-2 control-label" for="entry-clientCode"><?php echo $entry_clientCode; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="coinzone_clientCode" value="<?php echo $coinzone_clientCode; ?>" placeholder="<?php echo $entry_clientCode; ?>" id="entry-clientCode" class="form-control"/>
                            <?php if ($error_clientCode) { ?>
                            <div class="text-danger"><?php echo $error_clientCode; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group required">
                        <label class="col-sm-2 control-label" for="entry-apiKey"><?php echo $entry_apiKey; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="coinzone_apiKey" value="<?php echo $coinzone_apiKey ?>" placeholder="<?php echo $entry_apiKey; ?>" id="entry-apiKey" class="form-control"/>
                            <?php if ($error_apiKey) { ?>
                            <div class="text-danger"><?php echo $error_apiKey; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                        <div class="col-sm-10">
                            <select name="coinzone_status" id="input-status" class="form-control">
                                <?php if ($coinzone_status) { ?>
                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                <option value="0"><?php echo $text_disabled; ?></option>
                                <?php } else { ?>
                                <option value="1"><?php echo $text_enabled; ?></option>
                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
                        <div class="col-sm-10">
                            <select name="coinzone_paid_status_id" id="input-order-status" class="form-control">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                <?php if ($order_status['order_status_id'] == $coinzone_paid_status_id) { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>
