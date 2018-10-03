<style>
    .template_choose{
        height: 35px !important;
        padding: 0px !important;
        width: 75% !important;
    }
</style>
<!DOCTYPE html>
<html lang="en">
    <body>
        <div class="slim-mainpanel">
            <div class="container">
                <div class="slim-pageheader">
                    <ol class="breadcrumb slim-breadcrumb">
                    </ol>
                    <h6 class="slim-pagetitle">Employee RM Change Process</h6>
                </div><!-- slim-pageheader -->

                <div class="section-wrapper mg-b-70">
                    <div class="report-summary-header mg-b-5 mg-t-0 mg-b-10">
                        <div><label class="section-title mg-t-0">Team's KRA Update</label></div>
                        <div><a href="javascript:;" onclick="javascript:back_button()"><i class="fa fa-arrow-left"></i>Back</a></div>
                    </div>
                    <?php echo $form->create('', array('id' => 'Rmapprove', 'enctype' => 'multipart/form-data', 'action' => 'rm_approve')); ?>
                    <div class="table-responsive">
                        <table class="table table-striped mg-b-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Employee</th>
                                    <th>Band</th>
                                    <th>Transferred From</th>
                                    <th>From </th>
                                    <th>To </th>
                                    <th>HTL Experience</th>
                                    <th>Last Promotion</th>
                                    <th>Last Rating</th>
                                    <th class="wd-15p">View Rating</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
//                                echo '<pre>';
//                                print_r($employee_detail);
//                                die();
                                $count = 1;
                                foreach ($EmployeeRmChange as $kra_index => $kra_details) {
                                    ?>
                                    <tr>
                                        <td><?php echo $count; ?></td>
                                        <td><?php echo $kra_details[0]['requested_on'] ?></td>   
                                        <td><?php echo $employee_detail[$kra_details['EmployeeRmChange']['employee_id']]['Band']['name'] ; ?></td>   
                                        <td><?php echo $kra_details[0]['changed_from'] ?></td>   
                                        <td><?php echo  date('d-m-Y', strtotime($kra_details['EmployeeRmChange']['from_date'])); ?></td>   
                                        <td><?php echo  date('d-m-Y', strtotime($kra_details['EmployeeRmChange']['to_date'])); ?></td>   
                                        <td><?php echo $work_experience[$kra_details['EmployeeRmChange']['employee_id']]['htl']; ?></td>   
                                        <td><?php echo $last_promotion[$kra_details['EmployeeRmChange']['employee_id']]; ?></td>   
                                        <td><?php echo $last_rating[$kra_details['EmployeeRmChange']['employee_id']]; ?></td>   
                                        <td class="template "  data-request ="<?php echo $kra_details['EmployeeRmChange']['id']; ?>">
                                            <div class="pb-1 pl-1"><a href="#" id="view_template" title="View Template">KRA Feedback</a></div>
                                            <div class="pb-1 pl-1"><a href="#" ><button class="badge badge-danger border-0 pointer hr_mail_trigger" id="disagree_rating" value="send_back">Disagree Rating</button></a></div>
                                        </td>   
                                        <td>
                                            <a href="#" ><button class="badge badge-success border-0 pointer mb-2 apply_kra" title="Accept" id="accept_kra" value="approve">Agree</button></a>
                                        </td>   
                                    </tr> 
                                    <?php
                                    $count++;
                                }
                                ?>
                            </tbody>
                        </table>
                        </div><!-- bd -->
                        <!--<div class="row mg-b-25">-->
                            <!--<div class="col-lg-4">-->
                            <div class="card card-dash-one mg-t-20" id="modaldemo8_card">
                                <div id="modaldemo8" class="modal fade ">
                                    <div class="modal-dialog " role="document" style="min-width: 1000px;">
                                        <div class="modal-content tx-size-sm" > 
                                            <div class="modal-body pd-y-20 pd-x-20">
                                                <div class="row no-gutters" style=" background-color: #f8f9fa;">
                                                    <div class="col-lg-2 col-md-6 ">
                                                        <?php echo $form->input('template', array('name' => 'data[RmApprove][template_id]', 'id' => 'kra_templates', 'label' => 'Change Template', 'type' => 'select', 'empty' => '--Select--', 'selected' => '', 'options' => $kraTemplates, 'class' => 'form-control select2')); ?>
                                                    </div>
                                                    <div class="col-lg-4 col-md-6 border-left-0">
                                                         <div class="col-lg-6 col-md-6 ">
                                                        <div class="form-group mg-t-30-force float-right">
                                                            <button class="btn btn-primary bd-0 mg-r-10 mg-t-10  " id="submit_template" name="data[RmApprove][apply]">
                                                              <i class="fa  fa-mail-forward mg-r-3 mg-t-3" style="font-size:18px"></i>  
                                                                Apply</button>
                                                        </div>
                                                    </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 border-left-0">
                                                    <div class="form-group mg-t-30-force float-right border-left-0">
                                                        <label for="req_new_template">Request New Template</label>
                                                        <button class="btn btn-primary bd-0 mg-r-10 hr_mail_trigger" id="req_new_template" name="data[RmApprove][mail]">
                                                            <i class="fa fa-envelope mg-r-3" style="font-size:18px"></i> 
                                                            Request HR</button>
                                                    </div>
                                                    </div>
                                                </div>
                                                <div id="template_view"> </div>
                                            </div><!-- modal-body -->
                                        </div><!-- modal-content -->
                                    </div><!-- modal-dialog -->
                                </div><!-- modal -->
                                </div><!-- modal -->
                                
                                
                            <div class="card card-dash-one mg-t-20 ">
                                <div id="modaldemo_confirm" class="modal fade new_page">
                                    <div class="modal-dialog " role="document" style="min-width: 250px;">
                                        <div class="modal-content tx-size-sm" > 
                                            <div class="modal-body pd-y-20 pd-x-20">
                                                <div class="row no-gutters" style=" background-color: #f8f9fa;">
                                                    <div class="col-lg-12 col-md-12 border-left-0">
                                                             <label for="" style="font-size: 16px;">Do you wanna continue with same KRA template?</label>
                                                    </div>
                                                </div>
                                                 <div class="row no-gutters" style=" background-color: #f8f9fa;">
                                                    <!--<div class="col-lg-6 col-md-6 border-left-0">-->
                                                            <div class="form-group  float-right">
                                                                <button class="btn btn-primary bd-0 mg-r-10 mg-l-60  " id="kra_yes" name="data[RmApprove][kra_yes]">
                                                                  <i class="fa fa-thumbs-o-up mg-r-3 mg-t-4" style="font-size:18px"></i>  
                                                                  Yes</button> 
                                                            </div>
                                                        
                                                                <div class="form-group  float-right">
                                                                <button class="btn btn-primary bd-0 mg-r-10   " id="kra_no" name="data[RmApprove][kra_no]">
                                                                  <i class="fa fa-thumbs-o-down mg-r-3 mg-t-4" style="font-size:18px"></i>  
                                                                    No</button>
                                                            </div>
                                                    <!--</div>-->
<!--                                                     <div class="col-lg-6 col-md-6 border-left-0">
                                                         <div class="col-lg-6 col-md-6 ">
                                                            <div class="form-group mg-t-30-force float-right">
                                                                <button class="btn btn-primary bd-0 mg-r-10 mg-t-10 apply_kra " id="submit_template" name="data[RmApprove][apply]">
                                                                  <i class="fa fa-thumbs-o-down mg-r-3 mg-t-4" style="font-size:18px"></i>  
                                                                    No</button>
                                                            </div>
                                                        </div>
                                                    </div>-->
                                                </div>
                                            </div><!-- modal-body -->
                                        </div><!-- modal-content -->
                                    </div><!-- modal-dialog -->
                                </div><!-- modal -->
                                </div><!-- modal -->
                            <!--</div> modal-dialog -->
                        <!--</div> modal -->

                        <?php echo $form->end(); ?>
                    
                </div><!-- container -->
            </div><!-- slim-mainpanel -->

        </div>
    </body>
</html>

<div id="modaldemo3" class="modal fade new_page">
    <div class="modal-dialog wd-30p" role="document">
        <div class="modal-content tx-size-sm">
            <div class="modal-body tx-center pd-y-20 pd-x-20">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <i class="icon ion-ios-checkmark-outline tx-50 tx-success lh-1 mg-t-1 d-inline-block" id="icon"></i>
                <h4 class=" tx-14 mg-b-20" id="msg_text_success"></h4>
                <!--<p class="mg-b-20 mg-x-20">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration.</p>-->
                <button type="button" class="btn btn-success pd-x-25" data-dismiss="modal" aria-label="Close" id="ok_btn_success">Ok</button>
            </div><!-- modal-body -->
        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div><!-- modal -->

<div id="modaldemo5" class="modal fade new_page">
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

<div class="row mg-b-25">
    <div class="col-lg-4">
        <div id="modaldemo7" class="modal fade ">
            <div class="modal-dialog " role="document" style="min-width: 850px;">
                <div class="modal-content tx-size-sm" > 
                    <div class="modal-body pd-y-20 pd-x-20">
                        <div class="templateDetails">
                        </div>
                    </div><!-- modal-body -->
                </div><!-- modal-content -->
            </div><!-- modal-dialog -->
        </div><!-- modal -->
    </div><!-- modal-dialog -->
</div><!-- modal -->


<script>
    $(document).ready(function ()
    {
        $("#submit_template").click(function () {
            if($("#kra_templates").val() == ''){
                 $("#kra_templates").css({"outline":"1px solid red"});
                    return false;
            } else {
                $.ajax({
                    type: 'POST',
                    url: rootpath + '/kra_masters/rm_approve/' +  $(".template").data("request"),
                    cache: false,
                    data: $("#Rmapprove").serialize(),
                    beforeSend: function () {
                         $(".preloader").show();
                    },
                    success: function (result) {
                            $('#modaldemo8').modal('hide');
                            $("#msg_text_success").html(result);
                            $('#modaldemo3').modal('toggle');
                            $('#modaldemo3').modal('show');
                            $('#modaldemo3').modal('hide');
                            $(".preloader").fadeOut('500');
                        
                    }});
                return false;
            }
        });
        
         $("#kra_yes").click(function () {
             $('#modaldemo_confirm').modal('hide');
             $("#kra_templates").val("");
                $.ajax({
                    type: 'POST',
                    url: rootpath + '/kra_masters/rm_approve/' +  $(".template").data("request"),
                    cache: false,
                    data: $("#Rmapprove").serialize(),
                    beforeSend: function () {
                         $(".preloader").show();
                    },
                    success: function (result) {
                            $('#modaldemo8').modal('hide');
                            $("#msg_text_success").html(result);
                            $('#modaldemo3').modal('toggle');
                            $('#modaldemo3').modal('show');
                            $('#modaldemo3').modal('hide');
                            $(".preloader").fadeOut('500');
                        
                    }});
                return false;
            
        });
         $(".hr_mail_trigger").click(function () {
             var id = $(this).attr('id');
             console.log(id);
             if(id == 'disagree_rating'){
                 var request_type = 1;
             } 
             if(id == 'req_new_template'){
                 var request_type = 2;
             }
             
               $.ajax({
                    type: 'POST',
                    url: rootpath + '/kra_masters/notify_hr/' +  $(".template").data("request") +'/'+request_type,
                    cache: false,
                    data: "&action_type='disagree'",
                    beforeSend: function () {
                         $(".preloader").show();
                    },
                    success: function (result) {
                            $('#modaldemo8').modal('hide');
                            $('#modaldemo_confirm').modal('hide');
                            $("#msg_text_success").html(result);
                            $('#modaldemo3').modal('toggle');
                            $('#modaldemo3').modal('show');
                            $('#modaldemo3').modal('hide');
                            $(".preloader").fadeOut('500');
                        
                    }});
                return false;
        });
        $("#kra_templates").change(function () {
            $.ajax({
                type: 'POST',
                url: rootpath + '/kra_masters/template_view/',
                cache: false,
                data: "&template_id=" + $("#kra_templates").val(),
//                beforeSend: function () {
//                    $(".preloader").show();
//                },
                success: function (result) {
                    $("#template_view").html(result);
                    $('#template_view').show();
//                    $(".preloader").fadeOut('500');
                }});
                return false;
            
        });
         $("#kra_no").click(function () {
             $("#kra_templates").css({"outline":"none"});
            if($("#kra_templates").val() != ''){
              $("#kra_templates").val("");  
              $("#template_view").html("");  
            }
//                    $("#kra_templates").val("");
//                    $("#modaldemo8_card").html("");
                    $('#modaldemo_confirm').modal('hide');
                    $('#modaldemo8').modal('toggle');
                    $('#modaldemo8').modal('show');
                    $('#modaldemo8').modal('hide');
//                    $(".templateDetails").html(result);
//                    $('.templateDetails').show();
                    $(".preloader").fadeOut('500');
                return false;
            
        });
         $("#accept_kra").click(function () {
                 $('#modaldemo_confirm').modal('toggle');
                $('#modaldemo_confirm').modal('show');
                $('#modaldemo_confirm').modal('hide');
               return false;
            
        });

        $("#view_template").click(function () {
            $.ajax({
                type: 'POST',
                url: rootpath + '/kra_masters/employee_view/',
                cache: false,
                data: "&request_id=" + $(".template").data("request"),
                beforeSend: function () {
                    $(".preloader").show();
                },
                success: function (result) {
                    $('#modaldemo7').modal('toggle');
                    $('#modaldemo7').modal('show');
                    $('#modaldemo7').modal('hide');
                    $(".templateDetails").html(result);
                    $('.templateDetails').show();
                    $(".preloader").fadeOut('500');
                }});
        });

        $("#change_template").click(function () {
            $.ajax({
                type: 'POST',
                url: rootpath + '/kra_masters/template_view/' + $(".template").data("request"),
                cache: false,
//                data: "&template_id=" + template_id,
                beforeSend: function () {
                    $(".preloader").show();
                },
                success: function (result) {
                    $('#modaldemo8').modal('toggle');
                    $('#modaldemo8').modal('show');
                    $('#modaldemo8').modal('hide');
//                    $(".templateDetails").html(result);
//                    $('.templateDetails').show();
                    $(".preloader").fadeOut('500');
                }});
        });
        
         $(document).on('click','.new_page',function(){
            location.reload();
        });
    });


</script>

