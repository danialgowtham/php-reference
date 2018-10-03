<style>
    .ui-state-disabled{
        opacity: 0.3;
    }

</style>
<div class="slim-pageheader">
    <ol class="breadcrumb slim-breadcrumb">
    </ol>
    <h6 class="slim-pagetitle">Create/Map KRA TEMPLATE</h6>
</div><!-- slim-pageheader -->
<!--<div class="float-right mg-t-20 mg-r-40">
    <a href="javascript:;" onclick="javascript:back_button()"><i class="fa fa-arrow-left"></i>Back</a>
</div>-->
<div class="section-wrapper">
    <div class="report-summary-header mg-b-5 mg-t-0">
        <div class="back_button">
            <div><label class="section-title">Map Kra</label></div>
        </div>
        <div>
            <a href="javascript:;" onclick="javascript:back_button()"><i class="fa fa-arrow-left"></i>Back</a>
        </div>
    </div>
    <div class="form-layout  mg-b-20">
        <form name="KraMapping" id="KraMapping">
            <div class="row mg-b-25" id="kra_mapping_header">
                <div class="col-lg-4">
                    <div class="form-group mg-b-10-force">
                        <label class="form-control-label">Create/Assign<span class="tx-danger">*</span></label>
                        <select class="form-control select2" name="data_map"  id="create_assign">
                            <option value="">Select create/assign</option>
                            <option value="c">Create</option>
                            <option value="a">Assign</option>
                        </select>
                    </div>
                </div><!-- col-4 -->
                <div class="col-lg-4">
                    <div class="form-group mg-b-10-force">
                        <label class="form-control-label">Kra Start Date <span class="tx-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="icon ion-calendar tx-16 lh-0 op-6"></i>
                                        </div>
                                    </div>
                                    <input type="text" readonly="readonly" class="form-control bg-white select2 date_validation_field" kra_min_date="<?php echo $kra_from_date; ?>" placeholder="DD-MM-YYYY" name="kra_start_date" id="kra_start_date">
                                </div>
                    </div>
                </div><!-- col-4 -->
                <div class="col-lg-4">
                    <div class="form-group mg-b-10-force">
                        <label class="form-control-label">Mapping Type<span class="tx-danger">*</span></label>
                        <select class="form-control select2 mapping_validation_field" name="data[mapping][type]" id="mapping_type_select">
                            <option value="">Select Mapping Type</option>
                            <?php foreach ($mapping_array as $key => $value) { ?>
                                <option value="<?php echo $key ?>"><?php echo $value ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div><!-- col-4 -->
            </div><!-- form-layout -->
        </form>
        <div id="map_kra_div" class="body_kra_div">
            <div class="row mg-b-25" id="kra_mapping_header">
                <div class="col-lg-4">
                    <div class="form-group mg-b-10-force">
                        <label class="form-control-label">Kra Name<span class="tx-danger">*</span></label>
                        <select class="form-control select2" name="data_map"  id="kra_name_mapping">
                            <option value="">Select Kra</option>
                            <?php foreach ($kraTemplates as $key => $value) { ?>
                                <option value="<?php echo $key ?>"><?php echo $value ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-12 mg-t-20" id="preview_template">
                </div>
                <div class="form-layout-footer col-lg-12 text-center mg-t-20" id="button_div_apply">
                    <button class="btn btn-primary bd-0" id="assign_template">Apply</button>
                </div><!-- form-layout-footer -->
            </div>
        </div>
    </div>
    <div id="add_new_kra_div" class="body_kra_div">
        <div class="report-summary-header mg-b-5 mg-t-0">
            <div class="back_button">
                <div><label class="section-title">Add New Kra</label></div>
            </div>
        </div>
        <div class="form-layout">
            <div class="row mg-b-25">
                <div class="col-lg-4 mg-b-20-force">
                    <div class="form-group mg-b-10-force">
                        <label class="form-control-label">Kra Template Name<span class="tx-danger">*</span></label>
                        <input class="form-control validation_field" type="text" name="data_new[KraTemplate][kra_master_id]" id="kra_master_id" value="" placeholder="Enter Kra Template Name">
                    </div>
                </div><!-- col-8 -->

                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-control-label">Custom Template</label>
                        <select class="form-control select2" name="custom_template"  id="select_custom">
                            <option value="">Select Custom</option>
                            <option value="y">Yes</option>
                            <option value="n">No</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4"></div>
                <div class="col-lg-4">
                    <div class="form-group mg-b-10-force">
                        <label class="form-control-label">Kra Name<span class="tx-danger">*</span></label>
                        <div class="input-group">
                            <select name="data_new[KraTemplate][kra_id]" class="form-control select2 validation_field clear_all" id="kra_id">
                                <option value="">--Select Kra Name--</option>
                                <?php foreach ($kra_name as $key => $value) { ?>
                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                <?php } ?>
                            </select>
                            <span class="input-group-btn">
                                <button class="btn bd bd-l-0 bg-white tx-gray-600 add_button" type="button" id="add_kra_name"><i class="fa fa-plus"></i></button>
                            </span>
                        </div>
                    </div>
                </div><!-- col-4 -->
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-control-label">Kra Description<span class="tx-danger">*</span></label>
                        <input class="form-control validation_field clear_all" type="text" name="data_new[KraTemplate][kra_description]" id="kra_description" value="" placeholder="Enter Description" >
                    </div>
                </div><!-- col-4 -->
                <div class="col-lg-4">

                    <div class="form-group mg-b-10-force">
                        <label class="form-control-label">Kra UOM</label>
                        <div class="input-group">
                            <select name="data_new[KraTemplate][uom_id]" class="form-control select2 clear_all" id="uom_id">
                                <option value="">--Select UOM--</option>
                                <?php foreach ($kra_uom_name as $key => $value) { ?>
                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                <?php } ?>
                            </select>
                            <span class="input-group-btn">
                                <button class="btn bd bd-l-0 bg-white tx-gray-600 add_button" type="button" id="add_kra_uom"><i class="fa fa-plus"></i></button>
                            </span>
                        </div>
                    </div>
                </div><!-- col-4 -->
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-control-label">Weightage<span class="tx-danger">*</span></label>
                        <input class="form-control checknumber validation_field clear_all" type="text" name="data_new[KraTemplate][weightage]" id="weightage" placeholder="Enter Weightage" >
                    </div>
                </div><!-- col-4 -->
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-control-label">Target</label>
                        <input class="form-control clear_all" type="text" name="data_new[KraTemplate][target]" id="target" value="" placeholder="Enter Target">
                    </div>
                </div><!-- col-4 -->
                <!--                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label">Actual</label>
                                            <input class="form-control clear_all" type="text" name="data_new[KraTemplate][actual]" id="actual" value="" placeholder="Enter Actual">
                                        </div>
                                    </div> col-4 -->
            </div><!-- row -->

            <div class="form-layout-footer text-center">
                <!--<button class="btn btn-primary bd-0 mg-r-10" id="submit">Submit </button>-->
                <button class="btn btn-primary bd-0" id="add_into_table">Add</button>
            </div><!-- form-layout-footer -->
        </div><!-- form-layout -->
        <form name="KraTemplate" id="EmployeeKraForm">
            <div class="table-responsive pd-0-force mg-t-20" id="table-responsive">
                <label class="section-title mg-b-20">Preview Template</label>
                <h5 class="card-title tx-dark tx-14 mg-b-15 mg-sm-b-15 d-flex">Template Name :
                    <p class="text-secondary pd-l-10" id="kra_name"></p>
                </h5>
                <!--<p class="mg-b-20 mg-sm-b-20" id="kra_name"></p>-->
                <input type="hidden" name='kra_master_name' id="hidden_name">
                <table class="table mg-b-0 table-bordered" id="table_template">
                    <thead class="text-center">
                        <tr>
                            <th class="wd-15p">Kra Name</th>
                            <th class="wd-15p">Kra Description</th>
                            <th class="wd-15p uom_header d-none-normal">Kra UOM</th>
                            <th class="wd-5p">Weightage</th>
                            <th class="wd-15p target_header d-none-normal">Target</th>
                            <th class="wd-15p actual_header_new d-none-normal">Actual</th>
                            <th class="wd-15p">Employee Rating</th>
                            <th class="wd-15p">Manager Rating</th>
                            <th class="wd-15p">Employee Comments</th>
                            <th class="wd-15p">Manager Comments</th>
                            <th class="wd-15p">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <div class="form-layout-footer text-center mg-t-20">
                    <button class="btn btn-primary bd-0 mg-r-10 " id="submit_form_data">Submit </button>
                    <!--<button type="button" class="btn btn-secondary" id="cancel">Cancel</button>-->
                </div><!-- form-layout-footer -->
            </div><!-- bd -->
        </form>
    </div><!-- section-wrapper -->
</div><!-- section-wrapper -->
</div><!-- section-wrapper -->
<div id="modaldemo2" class="modal fade ">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content bd-0 tx-14 wd-300 ht-250">
            <div class="modal-header">
                <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold" id='add_header'></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-20">
                <form name="adding_kra_template" id="adding_kra_template">
                    <p class="mg-b-5" id="add_content"></p>
                </form>
                <div class="mg-t-10 tx-left"><span id="error_msg" class="text-danger"></span></div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary" id="add_button_modal">Add</button>
            </div>
        </div>
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

<div id="modaldemo3" class="modal fade">
    <div class="modal-dialog wd-30p" role="document">
        <div class="modal-content tx-size-sm">
            <div class="modal-body tx-center pd-y-20 pd-x-20">
                <!--                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>-->
                <i class="icon ion-ios-checkmark-outline tx-50 tx-success lh-1 mg-t-1 d-inline-block" id="icon"></i>
                <h4 class=" tx-14 mg-b-20" id="msg_text_success"></h4>
                <!--<p class="mg-b-20 mg-x-20">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration.</p>-->
                <button type="button" class="btn btn-success pd-x-25" data-dismiss="modal" aria-label="Close" id="ok_btn_success">Ok</button>
            </div><!-- modal-body -->
        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div><!-- modal -->

<?php
echo $html->css('/kra/lib/fastselect/css/fastselect.css');
echo $javascript->link('/kra/lib/fastselect/js/fastselect.standalone.js');
echo $javascript->link('/kra/lib/jquery-ui/js/jquery-ui.js');
echo $javascript->link('/kra/lib/moment/js/moment.js');
echo $javascript->link('/kra/js/hr_kra_template.js');
?>
            


