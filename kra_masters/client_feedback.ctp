<?php 
    if(isset($invalid_link)){
        echo '<div class="text-center pd-t-100 pd-b-100"><h1 class="tx-xl-bold text-danger">Invalid Link</h1></div>';
}else{ ?>

<div class="section-wrapper mg-t-20">
    <div class="slim-pageheader">
        <label class="tx-xl-bold text-dark ">Remaining Employees - <?php echo sizeof($get_employee_details); ?></label>
        <label class="tx-xl-bold text-dark ">Client Feedback - <?php echo $customer_name ?></label>
    </div>

    <div class="table-responsive">
        <form id="ClientFeedbackForm" name="ClientFeedbackForm">
            <table class="table mg-b-0" id="datatable1">
            <thead>
                <tr>
                    <th class="wd-1p"><input type="checkbox" id="check_all"></th>
                    <th class="wd-8p">Employee Name</th>
                    <th class="wd-8p">Project Name</th>
                    <th class="wd-8p">Project Manager</th>
                    <th class="wd-15p">Time</th>
                    <th class="wd-15p">Quality</th>
                    <th class="wd-15p">Productivity</th>
                    <th class="wd-15p">Process Compliance</th>
                    <th class="wd-15p">Customer Commands</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (empty($get_employee_details)) {
                    echo '<tr><td colspan="9" align="center">No data to display</td></tr>';
                } else {
                    $count=0;
                    foreach ($get_employee_details as $get_employee_detail) {
                            ?>
                        <tr>
                            <td class="text-center">
                                <input type="checkbox"  name="data[selected_employee_id][]" value="<?php echo $get_employee_detail['Employee']['id']; ?>" class="individual_check" id="individual_check_<?php echo $count; ?>">
                                <input type="hidden" name="data[customer_id]" value="<?php echo $get_employee_detail['Customer']['id'] ?>">
                                <input type="hidden" name="data[EmployeeKraDetail][<?php echo $get_employee_detail['Employee']['id']; ?>][kra_client_feedback_id]" value="<?php echo $get_employee_detail['KraClientFeedback']['id'] ?>">
                            </td>
                            <td><span><?php echo $get_employee_detail['Employee']['employee_number'] . ' - ' . $get_employee_detail['Employee']['first_name'] . ' ' . $get_employee_detail['Employee']['last_name'] ?></span></td>
                            <td>
                                <span>
                                    <?php echo $get_employee_detail['Project']['project_code'] . ' - ' . $get_employee_detail['Project']['project_name'] ?>
                                    <input type="hidden" name="data[EmployeeKraDetail][<?php echo $get_employee_detail['Employee']['id']; ?>][project_id]" value="<?php echo $get_employee_detail['Project']['id']; ?>">
                                </span>
                            </td>
                            <td><span><?php echo $get_employee_detail['ProjectManager']['employee_number'] . ' - ' . $get_employee_detail['ProjectManager']['first_name'] . ' ' . $get_employee_detail['ProjectManager']['last_name'] ?></span></td>
                            <td>
                                <select class="form-control select client_rating" id="rating1_<?php echo $count; ?>" data-placeholder="Choose" tabindex="-1" aria-hidden="true" name="data[EmployeeKraDetail][<?php echo $get_employee_detail['Employee']['id']; ?>][time]"  >
                                    <option value="">Select</option>
                                    <?php
                                    foreach ($ratings as $index => $value) { ?>
                                         <option value="<?php echo $index ?>"<?php echo (!empty($get_employee_detail['KraClientFeedback']['id']) && $get_employee_detail['KraClientFeedback']['time']==$index)? 'Selected':'' ?>><?php echo $value ?></option>
                                   <?php }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select class="form-control select client_rating" id="rating2_<?php echo $count; ?>" data-placeholder="Choose" tabindex="-1" aria-hidden="true" name="data[EmployeeKraDetail][<?php echo  $get_employee_detail['Employee']['id']; ?>][quality]"  >
                                    <option value="">Select</option>
                                    <?php
                                    foreach ($ratings as $index => $value) { ?>
                                        <option value="<?php echo $index ?>"<?php echo (!empty($get_employee_detail['KraClientFeedback']['id']) && $get_employee_detail['KraClientFeedback']['quality']==$index)? 'Selected':'' ?>><?php echo $value ?></option>
                                   <?php  }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select class="form-control select client_rating" id="rating3_<?php echo $count; ?>" data-placeholder="Choose" tabindex="-1" aria-hidden="true" name="data[EmployeeKraDetail][<?php echo  $get_employee_detail['Employee']['id']; ?>][productivity]"  >
                                    <option value="">Select</option>
                                    <?php
                                    foreach ($ratings as $index => $value) { ?>
                                        <option value="<?php echo $index ?>"<?php echo (!empty($get_employee_detail['KraClientFeedback']['id']) && $get_employee_detail['KraClientFeedback']['productivity']==$index)? 'Selected':'' ?>><?php echo $value ?></option>
                                  <?php  }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select class="form-control select client_rating" id="rating4_<?php echo $count; ?>" data-placeholder="Choose" tabindex="-1" aria-hidden="true" name="data[EmployeeKraDetail][<?php echo  $get_employee_detail['Employee']['id']; ?>][process_compliance]"  >
                                    <option value="">Select</option>
                                    <?php
                                    foreach ($ratings as $index => $value) { ?>
                                        <option value="<?php echo $index ?>"<?php echo (!empty($get_employee_detail['KraClientFeedback']['id']) && $get_employee_detail['KraClientFeedback']['process_compliance']==$index)? 'Selected':'' ?>><?php echo $value ?></option>
                                 <?php   }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <textarea rows="1" class="form-control client_rating" id="rating5_<?php echo $count; ?>" placeholder="Enter" name="data[EmployeeKraDetail][<?php echo $get_employee_detail['Employee']['id']; ?>][customer_comments]"><?php echo (!empty($get_employee_detail['KraClientFeedback']['id']) && $get_employee_detail['KraClientFeedback']['customer_comments']!='')? $get_employee_detail['KraClientFeedback']['customer_comments']:''; ?></textarea>
                            </td>
                        </tr>
                    <?php
                    $count++; }
                }
                ?>   
            </tbody>
        </table>
            <hr>
        </form>
    </div><!-- table-responsive -->
    <div class="form-layout-footer text-center">
        <button class="btn btn-primary bd-0 mg-r-10" id="save">Save</button>
        <button class="btn btn-primary bd-0" id="submit">Submit</button>
    </div>
</div>
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
        echo $javascript->link('/kra/js/client_feedback.js'); 
        echo $html->css('/kra/lib/datatables/css/jquery.dataTables.css');
        echo $html->css('/kra/lib/select2/css/select2.min.css');
        echo $javascript->link('/kra/lib/datatables/js/jquery.dataTables.js');
    ?>
<?php } ?>