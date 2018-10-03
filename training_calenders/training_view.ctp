<style>
    #view_table th, #view_table td{
        border: 0px !important;
    }

</style>
<div class="slim-mainpanel">
    <div class="container pd-0 mg-b-20">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
            </ol>
            <h6 class="slim-pagetitle">View Training Calender</h6>
        </div><!-- slim-pageheader -->

        <div class="float-right mg-t-20 mg-r-20">
            <a href="javascript:;" onclick="javascript:back_button()"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
        <div class="section-wrapper">
            <?php if (isset($view) && $view=='hr_view') {
                ?>
                <div class="slim-navbar bd-0">
                    <div class="container pd-0">
                        <ul class="nav">
                            <li class="nav-item active cursor-pointer"  view_type="employee_view_div">
                                <div class="nav-link" href="#">
                                    <i class="icon ion-ios-home-outline"></i>
                                    <lable>Nomination</lable>
                                </div>
                            </li>
                            <li class="nav-item cursor-pointer"  view_type="hr_view_div">
                                <div class="nav-link" href="#">
                                    <i class="icon ion-ios-book-outline"></i>
                                    <lable>Nominees</lable>
                                </div>
                            </li>
                        </ul>
                    </div><!-- container -->
                </div><!-- slim-navbar -->
            <?php } ?>
            <div class="form-layout" id="find_view" remaining_nomination="<?php echo !empty($edit_data[0]['remaining_nomination'])?$edit_data[0]['remaining_nomination']:0  ?>" view_type_page="<?php echo $view; ?>">
                <div class="card card-dash-one mg-t-20">

                    <div class="row no-gutters">

                        <div class="col-lg-3 col-md-6 pt-2 pb-0 pl-0 pr-0 bd-b bg-white">

              <!--<i class="icon ion-ios-analytics-outline"></i>-->

                            <div class="dash-content">

                                <label class="tx-primary">Title</label>

                                <h6 class="text-dark card_heading"><?php echo (isset($edit_data['TrainingCalender']['title']) && !empty($edit_data['TrainingCalender']['title'])) ? $edit_data['TrainingCalender']['title'] : '' ?></h6>

                            </div><!-- dash-content -->

                        </div><!-- col-3 -->

                        <div class="col-lg-3 col-md-6 pt-2 pb-0 pl-0 pr-0 bd-b bg-white">

              <!--<i class="icon ion-ios-pie-outline"></i>-->

                            <div class="dash-content">

                                <label class="tx-primary">Date</label>

                                <h6  class="text-dark card_heading"><?php echo (isset($edit_data['TrainingCalender']['date']) && !empty($edit_data['TrainingCalender']['date'])) ? date('d-m-Y', strtotime($edit_data['TrainingCalender']['date'])) : '' ?></h6>

                            </div><!-- dash-content -->

                        </div><!-- col-3 -->

                        <div class="col-lg-3 col-md-6 pt-2 pb-0 pl-0 pr-0 bd-b bg-white">

              <!--<i class="icon ion-ios-stopwatch-outline"></i>-->

                            <div class="dash-content">

                                <label class="tx-primary">Program Detail</label>

                                <h6  class="text-dark card_heading"><?php echo (isset($edit_data['TrainingCalender']['program_detail']) && !empty($edit_data['TrainingCalender']['program_detail'])) ? $edit_data['TrainingCalender']['program_detail'] : '' ?></h6>

                            </div><!-- dash-content -->

                        </div><!-- col-3 -->

                        <div class="col-lg-3 col-md-6 pt-2 pb-0 pl-0 pr-0 bd-b bg-white">

              <!--<i class="icon ion-ios-world-outline"></i>-->

                            <div class="dash-content">

                                <label class="tx-primary">Venue</label>

                                <h6 class="text-dark card_heading"><?php echo (isset($edit_data['City']['city']) && !empty($edit_data['City']['city'])) ? $edit_data['City']['city'] : $edit_data['TrainingCalender']['venue'] ?></h6>

                            </div><!-- dash-content -->

                        </div><!-- col-3 -->


                        <!--</div> col-3 -->

                    </div><!-- row -->


                    <div class="row no-gutters">
                        <div class="col-lg-3 col-md-6 pt-2 pb-0 pl-0 pr-0 bg-white">

              <!--<i class="icon ion-ios-analytics-outline"></i>-->

                            <div class="dash-content">

                                <label class="tx-primary">Unit</label>

                                <h6 class="text-dark card_heading"><?php echo (isset($edit_data['CompanyStructure']['name']) && !empty($edit_data['CompanyStructure']['name'])) ? $edit_data['CompanyStructure']['name'] : $edit_data['TrainingCalender']['su_id'] ?></h6>

                            </div><!-- dash-content -->

                        </div><!-- dash-content -->

                        <div class="col-lg-3 col-md-6 pt-0 pb-0 pl-0 pr-0  bg-white ">

                            <div class="dash-content">

                                <label class="tx-primary">Band</label>

                                <h6 class="text-dark card_heading"><?php
                                    $selected_band = explode(",", $edit_data['TrainingCalender']['band']);
                                    $band_list = array();
                                    foreach ($band as $key => $value) {
                                        if (in_array($key, $selected_band))
                                            $band_list[] = $value;
                                    }
                                    echo implode(",", $band_list);
                                    ?></h6>

                            </div><!-- dash-content -->

                        </div><!-- dash-content -->

                        <div class="col-lg-3 col-md-6 pt-2 pb-0 pl-0 pr-0 bg-white ">

              <!--<i class="icon ion-ios-analytics-outline"></i>-->

                            <div class="dash-content">

                                <label class="tx-primary">Trainer Name</label>

                                <h6 class="text-dark card_heading"><?php echo (isset($edit_data['TrainingCalender']['trainer_name']) && !empty($edit_data['TrainingCalender']['trainer_name'])) ? $edit_data['TrainingCalender']['trainer_name'] : '' ?></h6>

                            </div><!-- dash-content -->

                        </div><!-- col-3 -->

                        <div class="col-lg-2 col-md-6 pt-2 pb-0 pl-0 pr-0 bg-white">

              <!--<i class="icon ion-ios-pie-outline"></i>-->

                            <div class="dash-content">

                                <label class="tx-primary">Trainer Detail</label>

                                <h6  class="text-dark card_heading"><?php echo (isset($edit_data['TrainingCalender']['trainer_deatils']) && !empty($edit_data['TrainingCalender']['trainer_deatils'])) ? $edit_data['TrainingCalender']['trainer_deatils'] : '' ?></h6>

                            </div><!-- dash-content -->

                        </div><!-- col-3 -->


                    </div><!-- row -->
                </div><!-- row -->


                <div class="row mg-b-25">
                    <div class="col-lg-9 col-md-8 mg-t-20">
                        <div>
                            <lable class="section-title">Training Schedule</lable>
                        </div>
                    </div>
                    <div class="col-lg-9 pd-0 pd-l-15 mg-t-20">
                        <div class="form-group mg-b-10-force pd-l-0">
                            <div class="table-responsive">
                                <table class="table mg-b-0 table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>From Time</th>
                                            <th>To Time</th>
                                            <th>Total Hours</th>
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
                                                    <td><?php echo date('d-m-Y', strtotime($date_data['TrainingDuration']['duration_date'])) ?></td>
                                                    <td><?php echo $start_time; ?></td>
                                                    <td><?php echo $end_time; ?></td>
                                                    <td><?php echo $time_difference; ?></td>
                                                </tr>
                                                <?php
                                                $count++;
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!-- col-4 -->
                </div><!-- row -->
                <div id="employee_view_div" class="view_div">
                    <?php if (!empty($nominated_list)) { ?>
                        <div class="row mg-b-25">
                            <div class="col-lg-9 col-md-8 mg-t-20">
                                <div>
                                    <lable class="section-title">Nominees</lable>
                                </div>
                            </div>
                            <div class="col-lg-9 pd-0 pd-l-15 mg-t-20">
                                <div class="form-group mg-b-10-force pd-l-0">
                                    <div class="table-responsive">
                                        <table class="table mg-b-0 table-bordered">
                                            <thead>
                                                <tr>
                                                <tr>
                                                    <th>Employee</th>
                                                    <th>Band</th>
                                                    <th>Current Location</th>
                                                    <th>Nominated On</th>
                                                </tr>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($nominated_list as $employee_data) { ?>
                                                    <tr>
                                                        <td><input type="hidden" name="data[<?php echo $employee_data['Employee']['id'] ?>][id]"><span><?php echo $employee_data[0]['employee'] ?></span></td>
                                                        <td><span><?php echo $employee_data['TraineeNominationDetail']['employee_location'] ?></span></td>
                                                        <td><span><?php echo $employee_data['Band']['name'] ?></span></td>
                                                        <td><span><?php echo date('d-m-Y H:i:s', strtotime($employee_data['TraineeNominationDetail']['nominated_on'])) ?></span></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-layout-footer text-center" id="button_list">
                        <?php if($view!="hr_view" && (isset($edit_data['TrainingCalender']['training_type']) && !empty($edit_data['TrainingCalender']['training_type']) && $edit_data['TrainingCalender']['training_type']=='r')){ ?>
                        <lable class="section-title mg-b-10 tx-danger">Nomination Restricted By HR</lable>
                        <?php } else if (!empty($edit_data[0]['remaining_nomination'])&& ($edit_data[0]['remaining_nomination']<=0)){ ?>
                            <lable class="section-title mg-b-10 tx-danger">Nomination Closed Due to Unavailability of Seats</lable>
                          <?php  }
                            else if((isset($edit_data['TrainingCalender']['date']) && !empty($edit_data['TrainingCalender']['date'])) && $edit_data['TrainingCalender']['date'] > date("Y-m-d")) { ?>
                            <?php if (!empty($edit_data[0]['remaining_nomination'])&& $edit_data[0]['remaining_nomination']>=0 && !empty($self_nominated[0]['Employee']['id']) && empty($self_nominated[0]['TraineeNominationDetail']['id'])){ ?>
                                <button class="btn btn-primary bd-0 mg-l-10" id="open_modal">Self Nominate</button>
                            <?php } else if (!empty($self_nominated[0]['Employee']['id']) && !empty($self_nominated[0]['TraineeNominationDetail']['id'])) { ?>
                                <button class="btn bg-firewatch bg-white-5 text-white bd-0 mg-l-10 cancel_nomination_button" id="<?php echo $self_nominated[0]['TraineeNominationDetail']['id'] ?>">Cancel Nomination</button>

                            <?php } ?>
                            <?php if (!empty($reportees_list) && !empty($edit_data[0]['remaining_nomination'])&& $edit_data[0]['remaining_nomination']>=0) { ?>
                                <button class="btn btn-primary bd-0 mg-l-10" id="reportees_nominates">Nominate Reportees</button>
                                <?php
                            }
                            ?>
                        <?php }else{ ?>
                                <lable class="section-title mg-b-10 tx-danger">Nomination Closed</lable>
                        <?php } ?>
                    </div> 
                </div><!-- form-layout -->
                <div id="hr_view_div" class="view_div mg-b-25 mg-t-25">
                    <form id="EmployeeAttendance" name="EmployeeAttendance">
                        <div class="table-responsive">
                            <div class="back_button mg-b-10 col-lg-12 col-md-12 mg-t-20 pd-l-0 pd-r-0">
                                <div>
                                     <lable class="section-title">Trainee Details</lable>
                                </div>
                                <?php if (empty($nominated_overall_list[0]['TraineeNominationDetail']['attendance']) &&  $edit_data['TrainingCalender']['date'] <= date("Y-m-d")) { ?>
                                    <div>
                                        <label class="tx-primary cursor-pointer" id="add_employee_hr"> <i class="fa fa-plus"></i> Add Employee</label>
                                    </div>
                                <?php } ?>
                            </div>
                            

                            <table class="table table-bordered mg-b-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Employee Name</th>
                                        <th>Band</th>
                                        <th>Location</th>
                                        <th>Nominated By</th>
                                        <th>Nominated On</th>
                                        <?php if (!empty($nominated_overall_list[0][0]['max_Date']) && (strtotime($nominated_overall_list[0][0]['max_Date']) < strtotime(date('Y-m-d')))) { ?>
                                            <th>Attendance</th>
                                            <th>Attendance Hours</th>
                                        <?php }else{ ?>
                                            <th>Cancel Nomination</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 0;
                                    foreach ($nominated_overall_list as $employee_list) {
                                        $count++;
                                        ?>
                                        <tr>
                                            <td><span><?php echo $count; ?></span></td>
                                            <td><span><?php echo $employee_list[0]['employee'] ?></span></td>
                                            <td><span><?php echo $employee_list['Band']['name'] ?></span></td>
                                            <td><span><?php echo $employee_list['TraineeNominationDetail']['employee_location'] ?></span></td>
                                            <td><span><?php echo $employee_list[0]['nominated_by'] ?></span></td>
                                            <td><span><?php echo date('d-m-Y H:i:s', strtotime($employee_list['TraineeNominationDetail']['nominated_on'])) ?></span></td>
                                            <?php if (!empty($nominated_overall_list[0][0]['max_Date']) && (strtotime($nominated_overall_list[0][0]['max_Date']) < strtotime(date('Y-m-d')))) { ?>
        <?php if (empty($employee_list['TraineeNominationDetail']['attendance'])) { ?>
                                                    <td>
                                                        <label>
                                                            <input type="radio" name="data[<?php echo $employee_list['TraineeNominationDetail']['id'] ?>][attendance]" class="attendance_radio attendanceradiofield_<?php echo $count ?>" id="present_<?php echo $count ?>" value="Present"><span class="mg-l-5">Present</span>
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="data[<?php echo $employee_list['TraineeNominationDetail']['id'] ?>][attendance]" class="attendance_radio attendanceradiofield_<?php echo $count ?>" id="absent_<?php echo $count ?>" value="Absent"><span class="mg-l-5">Absent</span>
                                                        </label>
                                                    </td>
                                                    <td><input maxlength="8" type="text" name="data[<?php echo $employee_list['TraineeNominationDetail']['id'] ?>][attendance_hours]"class="attendance_validation_field checknumber hours_validation wd-80" id="attendancehour_<?php echo $count ?>" value="<?php echo $employee_list[0]['total_hours']; ?>" maxhours="<?php echo $employee_list[0]['total_hours']; ?>"></td>
                                                <?php } else { ?>
                                                    <td><span><?php echo $employee_list['TraineeNominationDetail']['attendance'] ?></span></td>
                                                    <?php
                                                    $atte_time = explode(':', $employee_list['TraineeNominationDetail']['attendance_hours']);
                                                    if (!empty($atte_time[0]))
                                                        $atte_time = $atte_time[0] . ':' . $atte_time[1];
                                                    else
                                                        $atte_time = '';
                                                    ?>

                                                    <td><span><?php echo $atte_time ?></span></td>
                                                <?php } ?>
                                        <?php }else{ ?>
                                                    <td class='text-center'><i class='fa fa-close tx-20-force text-danger cancel_nomination_button cursor-pointer' id='<?php echo $employee_list['TraineeNominationDetail']['id']; ?>'></i></td>
                                        <?php } ?>
                                        </tr>
                                    <?php } ?>
<?php if ($count == 0) { ?>
                                        <tr>
                                            <td class="text-center" colspan="7"><span>No One Nominated</span></td>
                                        </tr>
                                    <?php }
                                    ?>
                                </tbody>
                            </table>
                        </div><!-- table-responsive -->
                    </form>
                     <?php if (isset($nominated_overall_list) && !empty($nominated_overall_list[0][0]['max_Date']) && (strtotime($nominated_overall_list[0][0]['max_Date']) < strtotime(date('Y-m-d')))&&empty($nominated_overall_list[0]['TraineeNominationDetail']['attendance'])) { ?>
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-primary pd-x-25 text-center mg-t-20" data-dismiss="modal" aria-label="Close" id="submit_attendance">Submit</button>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div><!-- section-wrapper -->
    </div>
    <div id="modaldemo5" class="modal fade effect-newspaper">
        <div class="modal-dialog wd-30p" role="document">
            <div class="modal-content tx-size-sm">
<!--                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>-->
                <div class="modal-body tx-center pd-y-20 pd-x-20">
                    <i class="icon icon ion-ios-close-outline tx-50 tx-danger lh-1 mg-t-1 d-inline-block"></i>
                    <h4 class=" tx-14 mg-b-20" id="msg_text_failure"></h4>
                    <!--<p class="mg-b-20 mg-x-20">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration.</p>-->
                    <button type="button" class="btn btn-danger pd-x-25" data-dismiss="modal" aria-label="Close" id="ok_btn_failure">OK</button>
                </div><!-- modal-body -->
            </div><!-- modal-content -->
        </div><!-- modal-dialog -->
    </div><!-- modal -->

    <div id="modaldemo3" class="modal fade effect-newspaper">
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

    <div id="modaldemo1" class="modal fade effect-newspaper">
        <div class="modal-dialog modal-lg wd-500" role="document">
            <div class="modal-content bd-0 tx-14">
                <div class="modal-header pd-x-20">
                    <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Cancel Nomination</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20 mg-l-5">
                    <label class="section-title mg-0 pd-0">Cancel Reason</label>
                    <textarea id="cancel_nomination" rows="5" cols="50"></textarea>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-primary" id="cancel_submit">Submit</button>
                </div>
            </div>
        </div><!-- modal-dialog -->
    </div><!-- modal -->
    <div id="modaldemo6" class="modal fade effect-newspaper">
        <div class="modal-dialog modal-lg wd-500 " role="document">
            <div class="modal-content bd-0 tx-14 ht-150">
                <h6 class="tx-14 mg-b-0 text-center mg-t-20 mg-b-20 tx-uppercase tx-inverse tx-bold">Are You Sure To Nominate Yourself</h6>

                <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-primary nomination_button" id="self_nominate"><i class="fa fa-thumbs-up pd-r-10"></i>Yes</button>
                        <button type="button" class="btn btn-primary mg-l-20" id="close_nomination"><i class="fa fa-thumbs-down pd-r-10"></i>No </button>
                </div>
            </div>
        </div><!-- modal-dialog -->
    </div><!-- modal -->

    <div id="modaldemo2" class="modal fade effect-newspaper">
        <div class="modal-dialog modal-lg wd-800" role="document">
            <div class="modal-content bd-0 tx-14">
                <div class="modal-header pd-x-20">
                    <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold" id="hr_header">Nominate Reportees </h6>
                    <span class="mg-l-10 mg-b-1 tx-danger text-capitalize">( Available Seats -<?php echo !empty($edit_data[0]['remaining_nomination'])?$edit_data[0]['remaining_nomination']: "0" ; ?> )</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <form name="nomination_form" id="nomination_form">
                        <div class="form-layout col-lg-8">
                            <input type="hidden" name="training_calender_id" value="<?php echo (isset($edit_data['TrainingCalender']['id']) && !empty($edit_data['TrainingCalender']['id'])) ? $edit_data['TrainingCalender']['id'] : '' ?>" id="training_id">
                            <input type="hidden" name="nomination_by" value="" id="nomination_id">
                            <div class="form-group mg-b-10-force" id="reportees_div">
                                <label class="form-control-label" id="hr_lable">Reportees</label> <span class="tx-danger">*</span> <span class="mg-l-10 tx-danger" id="error_message"></span>
                                <select class="form-control select2" id="reportees_list" multiple name="">
                                    <?php
                                    foreach ($reportees_list as $key => $value) {
                                        ?>
                                        <option value="<?php echo $key ?>"><?php echo $value ?></option>
<?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group mg-b-10-force col-lg-12">
                            <div class="table-responsive">
                                <table class="table mg-b-0 table-bordered" id="employee_detail_table">
                                    <thead>
                                        <tr>
                                            <th>Employee</th>
                                            <th>Band</th>
                                            <th>Current Location</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-primary nomination_button" id="reportees_nominate">Nominate</button>
                </div>
            </div>
        </div><!-- modal-dialog -->
    </div><!-- modal -->

    <?php
    echo $html->css('/kra/lib/fastselect/css/fastselect.css');
    echo $javascript->link('/kra/lib/fastselect/js/fastselect.standalone.js');
    echo $javascript->link('/js/training_view.js');
    ?>
</div>
