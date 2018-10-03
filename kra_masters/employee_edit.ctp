<?php
if (isset($view) && !empty($view)) {
    if ($view == 'rm_view')
        $readonly = 'readonly=readonly';
    else
        $readonly = '';
}
?>     
<?php echo $html->css('/kra/lib/jquery-toggles/css/toggles-full.css');
?>


<div class="slim-pageheader">
    <ol class="breadcrumb slim-breadcrumb">
    </ol>
    <h6 class="slim-pagetitle">Edit Employee Details </h6>
</div><!-- slim-pageheader -->
<div class="section-wrapper">
    <div class="form-layout">
        <div class="float-right">
            <a href="javascript:;" onclick="javascript:back_button()"><i class="fa fa-arrow-left"></i>Back</a>
        </div>
            <?php echo $form->create('EmployeeKraDetails', array('id'=>'EmployeeKraForm' ,'url' => '/KraMasters/employee_edit/' . $employee_detail[0]['Employee']['id'] . '/'.$view . '/'.$kra_mapping_id, 'id' => 'EmployeeKraForm', 'enctype' => 'multipart/form-data')); ?> 
            <div class="row mg-b-25">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-control-label">First Name</label>
                        <input class="form-control" type="text" name="data[Employee][first_name]" id="first_name" value="<?php echo $employee_detail[0]['Employee']['first_name']; ?>" placeholder="Enter firstname" readonly=readonly >
                        <input class="form-control" type="hidden"  name="data[Employee][id]" id="emp_id" value="<?php echo $employee_detail[0]['Employee']['id']; ?>" >
                    </div>
                </div><!-- col-4 -->
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-control-label">Last Name</label>
                        <input class="form-control" type="text" name="data[Employee][last_name]" id="lastname" value="<?php echo $employee_detail[0]['Employee']['last_name']; ?>" placeholder="Enter lastname" readonly=readonly >
                    </div>
                </div><!-- col-4 -->
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-control-label">Email Address</label>
                        <input class="form-control" type="text" name="data[Employee][work_email_address]" id="work_email_address" value="<?php echo $employee_detail[0]['Employee']['work_email_address']; ?>" placeholder="Enter email address"  readonly=readonly >
                    </div>
                </div><!-- col-4 -->
                <div class="col-lg-4">
                    <div class="form-group mg-b-10-force">
                        <label class="form-control-label">Employee Type</label>
                        <input class="form-control" type="text" id="employee_type" name="data[Employee][employee_type]" value="<?php echo $employee_type[$employee_detail[0]['Employee']['employee_type']] ?>" readonly=readonly >
                    </div>
                </div><!-- col-8 -->
                <div class="col-lg-4">
                    <div class="form-group mg-b-10-force">
<?php if ($readonly) { ?>
                            <label class="form-control-label">Band</label>
                            <input class="form-control" type="text" id="parent_band_id" name="band" value="<?php echo $bands[$employee_detail[0]['Band']['parent_id']] ?>" <?php echo $readonly; ?> >
                        <?php } else { ?>
                            <?php echo $form->input('parent_band_id', array('id' => 'parent_band_id', 'label' => 'Band', 'type' => 'select', 'empty' => '--Select Band--', 'selected' => $employee_detail[0]['Band']['parent_id'], 'options' => $bands, 'class' => 'form-control select2'));
                        }
                        ?>
                    </div>
                </div><!-- col-4 -->
                <div class="col-lg-4">
                    <div class="form-group mg-b-10-force">
                        <?php if ($readonly) { ?>
                            <label class="form-control-label">Sub Band</label>
                            <input class="form-control" type="text" id="band_id" name="data[Employee][band_id]" value="<?php echo $levels_list[$employee_detail[0]['Band']['id']] ?>" <?php echo $readonly; ?> >
<?php } else { ?>
    <?php echo $form->input('band_id1', array('name' => 'data[Employee][band_id]', 'id' => 'sub_band_id', 'label' => 'Sub Band', 'type' => 'select', 'empty' => '--Select Sub Band--', 'selected' => $employee_detail[0]['Band']['id'], 'options' => $levels_list, 'class' => 'form-control select2'));
} ?>
                    </div>
                </div><!-- col-8 -->
                <div class="col-lg-4">
                    <div class="form-group mg-b-10-force">
                        <label class="form-control-label">Employment Status</label>
                        <input class="form-control" type="text" id="employment_status" name="data[Employee][employment_status]" value="<?php echo $employment_status[$employee_detail[0]['Employee']['employment_status']] ?>" readonly=readonly >
                    </div>
                </div><!-- col-4 -->
                <div class="col-lg-4">
                    <div class="form-group mg-b-10-force">
                        <?php if ($readonly) { ?>
                            <label class="form-control-label">Designation</label>
                            <input class="form-control" type="text" id="designation_id" name="data[Employee][designation_id]" value="<?php echo $selected_designations[$employee_detail[0]['Employee']['designation_id']] ?>" <?php echo $readonly; ?> >
<?php } else { ?>
    <?php echo $form->input('designation_id1', array('name' => 'data[Employee][designation_id]', 'label' => 'Designation', 'type' => 'select', 'empty' => '--Select Sub Band--', 'selected' => $employee_detail[0]['Employee']['designation_id'], 'options' => $selected_designations, 'class' => 'form-control select2'));
} ?>
                    </div>
                </div><!-- col-4 -->
                <div class="col-lg-4">
                    <div class="form-group mg-b-10-force">
                        <?php if ($readonly) { ?>
                            <label class="form-control-label">Practice Group</label>
                            <input class="form-control" type="text" id="company_structure_id" name="company_structure_id" value="<?php echo $company_structure[$employee_detail[0]['CompanyStructure']['parent_id']] ?>" <?php echo $readonly; ?> >
<?php } else { ?>
                            <?php echo $form->input('company_structure_id', array('label' => 'Practice Group', 'type' => 'select', 'empty' => '--Select Practice Group--', 'selected' => $employee_detail[0]['CompanyStructure']['parent_id'], 'options' => $company_structure, 'class' => 'form-control select2'));
                        } ?>
                    </div>
                </div><!-- col-4 -->
                <div class="col-lg-4">
                    <div class="form-group mg-b-10-force">
<?php if ($readonly) { ?>
                            <label class="form-control-label">Practice</label>
                            <input class="form-control" type="text" id="structure_name" name="data[Employee][structure_name]" value="<?php echo $structure_name[$employee_detail[0]['CompanyStructure']['id']] ?>" <?php echo $readonly; ?> >
                        <?php } else { ?>
                            <?php echo $form->input('structure_name1', array('name' => 'data[Employee][structure_name]', 'label' => 'Practice', 'type' => 'select', 'empty' => '--Select Practice--', 'selected' => $employee_detail[0]['CompanyStructure']['id'], 'options' => $structure_name, 'class' => 'form-control select2'));
                        } ?>
                    </div>
                </div><!-- col-4 -->
                <div class="col-lg-4">
                    <div class="form-group mg-b-10-force">
<?php if ($readonly) { ?>
                            <label class="form-control-label">Sub Practice</label>
                            <input class="form-control" type="text" id="structure_name_subgroup" name="data[Employee][structure_name_subgroup]" value="<?php echo $structure_name_subgroup[$employee_detail[0]['CompanyStructureGroup']['id']] ?>" <?php echo $readonly; ?> >
                        <?php } else { ?>
    <?php echo $form->input('structure_name_subgroup1', array('name' => 'data[Employee][structure_name_subgroup]', 'label' => 'Sub Practice', 'type' => 'select', 'empty' => '--Select Sub Practice--', 'selected' => $employee_detail[0]['CompanyStructureGroup']['id'], 'options' => $structure_name_subgroup, 'class' => 'form-control select2'));
} ?>
                    </div>
                </div><!-- col-4 -->
                <div class="col-lg-4">
                    <div class="form-group mg-b-10-force">
                        <?php
                                if(!empty($request_details)){
                                   $manager =  $request_details['EmployeeRmChange']['changed_to'];
                                   $approvers = $changed_approvers;
                                   $approved_by =  $request_details['EmployeeRmChange']['approved_by'];
                                   
                                } else{
                                    $manager =  $employee_detail[0]['Employee']['manager'];
                                    $approvers = $function_heads;
                                    $approved_by = '';
                                }
                        ?>
<?php echo $form->input('manager1', array('name' => 'data[Employee][manager]', 'id' => 'rm_id', 'old_manager'=>$manager, 'label' => 'Reporting Manager', 'type' => 'select', 'empty' => '--Select Manager--', 'selected' => $manager, 'options' => $managers, 'class' => 'form-control select2')); ?>
                    </div>
                </div><!-- col-4 -->
                <div class="col-lg-4">
                    <div class="form-group mg-b-10-force">
<?php echo $form->input('approved_manager', array('name' => 'data[Employee][approved_by]', 'id' => 'approver_id', 'label' => 'Approved By', 'type' => 'select', 'empty' => '--Select Manager--', 'selected'=>$approved_by, 'options' => $approvers, 'class' => 'form-control select2')); ?>
                    </div>
                </div><!-- col-4 -->
<!--                <div class="col-lg-1">
                    <div class="form-group mg-b-10-force ">
                        <label class="form-control-label ">KRA Change </label>

                        <div class=" toggle toggle-light primary" >
                        </div>
                    </div>
                </div>-->
                <input type="hidden"  id="kra_mapping_id" name="data[Employee][kra_mapping_id]" value="<?php echo $kra_mapping_id; ?>"/>
                <input type="hidden"  id="view" name="data[Employee][view]" value="<?php echo $view; ?>"/>
            </div><!-- row -->
        <?php echo $form->end(); ?>

        <div class="form-layout-footer text-center">
            <button class="btn btn-primary bd-0 mg-r-10"  id="submit">Submit </button>
            <a href="<?php echo BASE_PATH; ?>kra_masters/reportee_view/<?php echo $view ?>" class="btn btn-primary">Cancel</a>
        </div><!-- form-layout-footer -->
    </div><!-- form-layout -->
</div><!-- section-wrapper -->


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
 <div class="row mg-b-25">
                <div class="col-lg-4">
            <div id="modaldemo7" class="modal fade">
                                            <div class="modal-dialog" role="document" style="min-width: 850px;">
                                                <div class="modal-content tx-size-sm" > 
                                                    <div class="modal-body pd-y-20 pd-x-20">
                                                       <div class="empDetails">

                                            </div>

                                                    </div><!-- modal-body -->
                                                </div><!-- modal-content -->
                                            </div><!-- modal-dialog -->
                                        </div><!-- modal -->
										 </div><!-- modal-dialog -->
                                        </div><!-- modal -->



<?php echo $javascript->link('/kra/js/employee_edit.js');
echo $javascript->link('/kra/lib/jquery-toggles/js/toggles.min.js');
?>
<script>
    $(document).ready(function () {

        $('.toggle').toggles({
            on: false,
            height: 26
            
        });
        
        $('.toggle-on').text("YES");
        $('.toggle-off').text("NO");
        $('.empDetails').hide();
        var kra_change = 0;
        var toggleNo = $('.toggle-light').find('.toggle-on').hasClass("active");
        if (toggleNo) {
            kra_change = 1;
             $('.toggle-on').text("YES");
             $('.toggle-off').text("NO");
        } else {
            kra_change = 0;
            $('.toggle-on').text("YES");
        $('.toggle-off').text("NO");
        }


    });

</script>




