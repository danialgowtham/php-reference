
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<?php echo $html->css('/kra/lib/jquery-toggles/css/toggles-full.css');
?>

<style>
    .elatest {
        width: 14%;
        left: 84%;
        position: absolute;
        top: 33%;
    }

</style>
<div class="section-wrapper mg-t-20">
    <label class="section-title">Client Feedback</label>
    <form name="ClientFeedbackForm" id="ClientFeedbackForm">
        <div class="row ">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mg-b-25">
                            <div class="col-lg-5">
                                <div class="form-group mg-b-10-force">
                                    <label class="form-control-label">Customer Type<span class="tx-danger">*</span></label>
                                    <select class="form-control select selectCustomer scrollable-menu" size="10" id = 'customerlist' data-placeholder="Choose " tabindex="-1" aria-hidden="true" name="data[ClientFeedback][customer_id]">
                                        <?php foreach ($customers as $key => $customerName) { ?>
                                            <option value="<?php echo $key; ?>"><?php echo $customerName; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div><!-- col-4 -->
                            <div class="col-lg-5 project_div">
                                <div class="form-group mg-b-10-force">
                                    <label class="form-control-label">Project</label>
                                    <select class="form-control select selectProject" id = "projectlist" name="data[ClientFeedback][project_id]"  placeholder="Select Project">
                                    </select>
                                </div>
                            </div><!-- col-4 -->
                            <div class="col-lg-5 project_div">
                                <div class="form-group mg-b-10-force">
                                    <label class="form-control-label">Customer Mail</label>
                                    <input class="form-control" name="data[ClientFeedback][customer_mail]" list="mail_ids" id="customer_mail"  placeholder="Enter Mail">
                                    <datalist id="mail_ids">
                                    </datalist>
                                </div>
                            </div><!-- col-4 -->
                            <div class="col-lg-7">
                                <div class="form-group mg-t-30-force float-right">
                                    <button class="btn btn-primary bd-0 mg-r-10 " id="send_mail" name="data[ClientFeedback][action]">
                                        <i class="fa fa-envelope mg-r-3" style="font-size:18px"></i> 
                                        Send Mail</button>
                                </div>
                            </div>
                        </div><!-- row -->
                        <!--</div>-->
                    </div><!-- card -->
                </div><!-- col -->
            </div><!-- row -->
        </div><!-- section-wrapper --> 
    </form>
</div>
<div id="modaldemo3" class="modal fade new_page">
    <div class="modal-dialog wd-30p" role="document">
        <div class="modal-content tx-size-sm">
            <div class="modal-body tx-center pd-y-20 pd-x-20">
                <!--                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <span aria-hidden="true">&times;</span>
                                                </button>-->
                <i class="icon ion-ios-checkmark-outline tx-50 tx-success lh-1 mg-t-1 d-inline-block" id="icon"></i>
                <h4 class=" tx-14 mg-b-20" id="msg_text_success"></h4>
                <!--<p class="mg-b-20 mg-x-20">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration.</p>-->
                <button type="button" class="btn btn-success pd-x-25" data-dismiss="modal" aria-label="Close" id="ok_btn_success_new">Ok</button>
            </div><!-- modal-body -->
        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div><!-- modal -->
<div id="modaldemo5" class="modal fade">
    <div class="modal-dialog wd-30p" role="document">
        <div class="modal-content tx-size-sm">
            <div class="modal-body tx-center pd-y-20 pd-x-20">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <i class="icon icon ion-ios-close-outline tx-50 tx-danger lh-1 mg-t-1 d-inline-block"></i>
                <h4 class=" tx-14 mg-b-20" id="msg_text_failure"></h4>
                <!--<p class="mg-b-20 mg-x-20">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration.</p>-->
                <button type="button" class="btn btn-danger pd-x-25" data-dismiss="modal" aria-label="Close" id="ok_btn_failure">OK</button>
            </div><!-- modal-body -->
        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div><!-- modal -->
<?php echo $javascript->link('/kra/js/client_feedback_creation.js'); ?>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

