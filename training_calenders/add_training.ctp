<style>
    .ui-state-disabled{
        opacity: 0.3;
    }

</style>
<div class="slim-mainpanel">
    <div class="container pd-0 mg-b-20">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
            </ol>
            <h6 class="slim-pagetitle">
                <?php echo isset($edit_page) ? 'Edit Training Calender' : 'Add Training Calender' ?>
            </h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">
            <div class="float-right">
                <a href="javascript:;" onclick="javascript:back_button()"><i class="fa fa-arrow-left"></i> Back</a>
            </div>
            <div class="form-layout">
                <form name="traing_calenders" id="traing_calenders">
                    <input type="hidden" name="data[traing_calenders][id]" value="<?php echo (isset($edit_data['TrainingCalender']['id']) && !empty($edit_data['TrainingCalender']['id'])) ? $edit_data['TrainingCalender']['id'] : '' ?>" page_type="<?php echo isset($edit_page) ? 'edit_page' : 'add_page' ?>" id="page_type_find">
                    <input type="hidden"  value="" id="venue_type" sel_text="<?php echo (empty($edit_data['TrainingCalender']['venue']) || (isset($edit_data['CompanyLocation']['id']) && !empty($edit_data['CompanyLocation']['id'])) || $edit_data['TrainingCalender']['venue'] == 'webX') ? 'type_select' : 'type_text'; ?>">
                    <div class="row mg-b-25">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="form-control-label">Title <span class="tx-danger">*</span></label>
                                <input class="form-control validation_field save_validation_field" type="text" name="data[traing_calenders][title]" placeholder="Enter Title" id="title" value="<?php echo (isset($edit_data['TrainingCalender']['title']) && !empty($edit_data['TrainingCalender']['title'])) ? $edit_data['TrainingCalender']['title'] : '' ?>">
                            </div>
                        </div><!-- col-4 -->

                        <div class="col-lg-4 mg-t-20 mg-lg-t-0">
                            <label class="form-control-label">Venue <span class="tx-danger">*</span></label>
                            <div class="input-group" id="input_group">
                                <select class="form-control select2 validation_field" data-placeholder="Choose Venue" name="data[traing_calenders][venue]" id="venue_select">
                                    <option label="Choose Venue"></option>
                                    <?php foreach ($company_location_list as $key => $value) { ?>
                                        <option value="<?php echo $key ?>" <?php echo (isset($edit_data['TrainingCalender']['venue']) && !empty($edit_data['TrainingCalender']['venue']) && $edit_data['TrainingCalender']['venue'] == $key) ? 'Selected' : '' ?>><?php echo "Hinduja Tech - " . $value ?></option>
                                    <?php } ?>
                                    <option value="webX" <?php echo (isset($edit_data['TrainingCalender']['venue']) && !empty($edit_data['TrainingCalender']['venue']) && $edit_data['TrainingCalender']['venue'] == 'webX') ? 'Selected' : '' ?>>webX</option>
                                </select>

                                <input type="text" class="form-control main_date bg-white"  name="data[traing_calenders][venue]" id="venue_text" value="<?php echo $edit_data['TrainingCalender']['venue'] ?>">
                                <span class="input-group-btn">
                                    <button class="btn bd bd-l-0 bg-white tx-gray-600" type="button" id="change_type" value="Text">Text</button>
                                </span>
                            </div><!-- input-group -->
                        </div><!-- col-4 -->

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="form-control-label">Date <span class="tx-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="icon ion-calendar tx-16 lh-0 op-6"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control main_date bg-white validation_field save_validation_field" placeholder="DD-MM-YYYY" name="data[traing_calenders][date]" readonly id="main_date" value="<?php echo (isset($edit_data['TrainingCalender']['date']) && !empty($edit_data['TrainingCalender']['date'])) ? date('d-m-Y', strtotime($edit_data['TrainingCalender']['date'])) : '' ?>">
                                </div>
                            </div>
                        </div><!-- col-4 -->
                        <div class="col-lg-4">
                            <div class="form-group mg-b-10-force" id="band_div">
                                <label class="form-control-label">Band <span class="tx-danger">*</span></label>
                                <select class="form-control select2 validation_field" id="bands" multiple name="data[traing_calenders][band][]">
                                    <?php
                                    $selected_band = explode(",", $edit_data['TrainingCalender']['band']);
                                    foreach ($band as $key => $value) {
                                        ?>
                                        <option value="<?php echo $key ?>"  <?php echo (isset($edit_data['TrainingCalender']['band']) && !empty($edit_data['TrainingCalender']['band']) && in_array($key, $selected_band)) ? 'selected' : '' ?>><?php echo $value ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div><!-- col-8 -->
                        <div class="col-lg-4">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">Unit <span class="tx-danger">*</span></label>
                                <select class="form-control select2 validation_field" data-placeholder="Choose Unit" name="data[traing_calenders][su_id]" id="su_id">
                                    <option label="Choose Unit"></option>
                                    <option value="ALL" label="All" <?php echo (isset($edit_data['TrainingCalender']['su_id']) && !empty($edit_data['TrainingCalender']['su_id']) && $edit_data['TrainingCalender']['su_id'] == 'ALL' ) ? 'selected' : '' ?>></option>
                                    <?php foreach ($company_unit_list as $key => $value) { ?>
                                        <option value="<?php echo $key ?>" <?php echo (isset($edit_data['TrainingCalender']['su_id']) && !empty($edit_data['TrainingCalender']['su_id']) && $edit_data['TrainingCalender']['su_id'] == $key ) ? 'selected' : '' ?>><?php echo $value ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div><!-- col-4 -->
                        <div class="col-lg-4">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">Program Detail <span class="tx-danger">*</span></label>
                                <input class="form-control validation_field" type="text" name="data[traing_calenders][program_detail]" placeholder="Enter Program Detail" id="program_detail" value="<?php echo (isset($edit_data['TrainingCalender']['program_detail']) && !empty($edit_data['TrainingCalender']['program_detail'])) ? $edit_data['TrainingCalender']['program_detail'] : '' ?>">
                            </div>
                        </div><!-- col-4 -->
                        <div class="col-lg-4">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">Trainer Name</label>
                                <input class="form-control" type="text" name="data[traing_calenders][trainer_name]" placeholder="Enter Name" id="trainer_name" value="<?php echo (isset($edit_data['TrainingCalender']['trainer_name']) && !empty($edit_data['TrainingCalender']['trainer_name'])) ? $edit_data['TrainingCalender']['trainer_name'] : '' ?>">
                            </div>
                        </div><!-- col-4 -->
                        <div class="col-lg-4">
                            <div class="form-group mg-b-10-force pd-l-0">
                                <label class="form-control-label">Trainer Detail </label>
                                <input class="form-control" type="text" name="data[traing_calenders][trainer_deatils]" placeholder="Enter Trainer Detail" id="trainer_deatils" value="<?php echo (isset($edit_data['TrainingCalender']['trainer_deatils']) && !empty($edit_data['TrainingCalender']['trainer_deatils'])) ? $edit_data['TrainingCalender']['trainer_deatils'] : '' ?>">
                            </div>
                        </div><!-- col-4 -->
                        <div class="col-lg-4">
                            <div class="form-group mg-b-10-force pd-l-0">
                                <label class="form-control-label">Nomination Type <span class="tx-danger">*</span></label>
                                <select class="form-control select2 validation_field" data-placeholder="Choose Nomination Type" name="data[traing_calenders][training_type]" id="training_type">
                                    <option label="Choose Training Type"></option>
                                    <?php foreach ($nomination_type as $key => $value) { ?>
                                        <option value="<?php echo $key ?>" <?php echo (isset($edit_data['TrainingCalender']['training_type']) && !empty($edit_data['TrainingCalender']['training_type']) && $edit_data['TrainingCalender']['training_type'] == $key ) ? 'selected' : '' ?>><?php echo $value ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div><!-- col-4 -->
                        <div class="col-lg-8">
                            <div class="form-group col-lg-6 mg-b-10-force pd-l-0">
                                <label class="form-control-label">Maximum Nomination <span class="tx-danger">*</span></label>
                                <input class="form-control checknumber validation_field" type="text" name="data[traing_calenders][max_nomination]" placeholder="Enter Maximum Nominations" id="max_nomination" value="<?php echo (isset($edit_data['TrainingCalender']['max_nomination']) && !empty($edit_data['TrainingCalender']['max_nomination'])) ? $edit_data['TrainingCalender']['max_nomination'] : '' ?>">
                            </div>
                        </div><!-- col-4 -->
                        <div class="back_button col-lg-8 col-md-8 mg-t-20">
                            <div>
                                <lable class="section-title mg-b-10">Training Schedule <span class="tx-danger">*</span></lable>
                            </div>
                            <div>
                                <span class="add_new_date tx-primary cursor-pointer pd-t-20" id="add_new_date" title="add date"><i class="fa fa-plus tx-16"></i> Add</span>
                                <!--<label class="tx-primary cursor-pointer" id="add_evaluvation"> <i class="fa fa-plus"></i> Add</label>-->
                            </div>
                        </div>
                        <div class="col-lg-8 mg-t-10" id="table_div">
                            <div class="form-group mg-b-10-force pd-l-0">
                                <div class="table-responsive">
                                    <table class="table mg-b-0 table-bordered" id="date_table">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>From Time</th>
                                                <th>To Time</th>
                                                <th>Total Hours</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($duration_edit_data) {
                                                $count = 1;
                                                foreach ($duration_edit_data as $date_data) {
                                                    $start_time = explode(':', $date_data['TrainingDuration']['start_time']);
                                                    $start_time = $start_time[0] . ':' . $start_time[1];
                                                    $end_time = explode(':', $date_data['TrainingDuration']['end_time']);
                                                    $end_time = $end_time[0] . ':' . $end_time[1];
                                                    $time_difference = explode(':', $date_data['TrainingDuration']['time_difference']);
                                                    $time_difference = $time_difference[0] . ':' . $time_difference[1];
                                                    ?>
                                                    <tr id="row_<?php echo $count; ?>">
                                                        <td><input type="hidden" name="datelist[<?php echo $count; ?>][id]" value="<?php echo $date_data['TrainingDuration']['id'] ?>" id="deleted_id_<?php echo $count; ?>"><input type='text' placeholder="DD-MM-YYYY" name="datelist[<?php echo $count; ?>][date]" class="form-control bg-white validation_field datetime date_field" value="<?php echo date('d-m-Y', strtotime($date_data['TrainingDuration']['duration_date'])) ?>" readonly id='date_<?php echo $count; ?>'></td>
                                                        <td><input id="start_time_<?php echo $count; ?>" name="datelist[<?php echo $count; ?>][start_time]" type="text" class="form-control validation_field start_time"  placeholder="Start time" value="<?php echo $start_time; ?>"></td>
                                                        <td><input id="end_time_<?php echo $count; ?>" name="datelist[<?php echo $count; ?>][end_time]" type="text" class="form-control validation_field end_time" placeholder="End time" value="<?php echo $end_time; ?>"></td>
                                                        <td><input id="time_difference_<?php echo $count; ?>" name="datelist[<?php echo $count; ?>][time_difference]" type="text" class="form-control time_difference" value="<?php echo $time_difference; ?>" readonly></td>
                                                        <td class='text-center'><i class='fa fa-close tx-20-force text-danger remove_row cursor-pointer' id='remove_<?php echo $count; ?>'></i></td>
                                                    </tr>
                                                    <?php
                                                    $count++;
                                                }
                                            } else {
                                                ?>
                                                <tr id="row_0">
                                                    <td><input type="text" class="form-control  bg-white validation_field datetime date_field" placeholder="DD-MM-YYYY" id="date_0" readonly name="datelist[0][date]"></td>
                                                    <td><input id="start_time_0" name="datelist[0][start_time]" type="text" class="form-control validation_field start_time"  placeholder="Start time"></td>
                                                    <td><input id="end_time_0" name="datelist[0][end_time]" type="text" class="form-control validation_field end_time" placeholder="End time"></td>
                                                    <td><input id="time_difference_0" name="datelist[0][time_difference]" type="text" class="form-control" readonly></td>
                                                    <td class='text-center'><i class='fa fa-close tx-20-force text-danger remove_row cursor-pointer' id='remove_0' style="display:none"></i></td>

                                                </tr>
                                            <?php }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><!-- col-4 -->
                    </div><!-- row -->
                </form>

                <div class="form-layout-footer text-center">
                    <?php if(!(isset($edit_page))){ ?>
                        <button class="btn btn-secondary bd-0" id="save">Save</button>
                    <?php } ?>
                    <button class="btn btn-primary bd-0 mg-l-10" id="submit">Submit</button>
                </div><!-- form-layout-footer -->
            </div><!-- form-layout -->
        </div><!-- section-wrapper -->
    </div>
    <div id="modaldemo5" class="modal fade effect-newspaper" >
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

    <div id="modaldemo3" class="modal fade effect-newspaper" >
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
    echo $html->css('/kra/lib/jt.timepicker/css/jquery.timepicker.css');
    echo $javascript->link('/kra/lib/moment/js/moment.js');
    echo $javascript->link('/kra/lib/jt.timepicker/js/jquery.timepicker.js');
    echo $javascript->link('/kra/lib/fastselect/js/fastselect.standalone.js');
    echo $javascript->link('/kra/lib/jquery-ui/js/jquery-ui.js');
    echo $javascript->link('/js/add_training.js');
    ?>
</div>