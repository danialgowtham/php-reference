<!DOCTYPE html>
<html lang="en">
    <body>
        <div class="slim-mainpanel">
            <div class="container">
                <div class="slim-pageheader">
                    <ol class="breadcrumb slim-breadcrumb">
                    </ol>
                    <h6 class="slim-pagetitle">Employee RM Change Request</h6>
                </div><!-- slim-pageheader -->

                <div class="section-wrapper">
                    <div class="report-summary-header mg-b-5 mg-t-0 mg-b-10">
                        <div><label class="section-title mg-t-0">RM Change Request List</label></div>
                        <div><a href="javascript:;" onclick="javascript:back_button()"><i class="fa fa-arrow-left"></i>Back</a></div>
                    </div>
                    <div class="table-responsive">
                        <?php echo $form->create('', array('id' => 'EmployeeKraForm', 'enctype' => 'multipart/form-data','action'=>'')); ?> 
                        <table class="table table-striped mg-b-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Requested By</th>
                                    <th>Requested For</th>
                                    <th>Approved By</th>
                                    <th>Changed From</th>
                                    <th>Changed To</th>
                                    <th>Template</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                        $count = 1;
                        foreach ($EmployeeRmChange as $kra_index => $kra_details) {
                            ?>
                            <tr>
                                <td><?php echo $count; ?></td>
                                <td><?php echo $kra_details[0]['requested_by'] ?></td>   
                                <td><?php echo $kra_details[0]['requested_on'] ?></td>   
                                <td><?php echo $kra_details[0]['approved_by'] ?></td>   
                                <td><?php echo $kra_details[0]['changed_from']?></td>   
                                <td><?php echo $kra_details[0]['changed_to'] ?></td>   
                                <td><?php if($kra_details['EmployeeRmChange']['kra_change'] == 1) echo $form->input('template_id', array('name' => 'data[HrApprove][template_id]', 'id' => 'template_id', 'label' => false, 'type' => 'select', 'empty' => '--Select Manager--',  'options' => $kraTemplates, 'class' => 'form-control select2')); ?></td>   
                                <td>
                                    <a href="#" ><button class="badge badge-success border-0 pointer" id="approve_btn" value="approve">Approve</button></a>
                                    <a href="#" ><button class="badge badge-danger border-0 pointer" id="send_back_btn" value="send_back">Send Back</button></a>
                                </td>   
                            </tr> 
                            <?php $count++;
                        } ?>
                            </tbody>
                        </table>
                        <?php echo $form->end(); ?>
                    </div><!-- bd -->
                </div><!-- container -->
            </div><!-- slim-mainpanel -->




    </body>
</html>

<div id="modaldemo3" class="modal fade">
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
<script>
    $(document).ready(function ()
{
    $("#approve_btn").click(function () {
            $("#action").val("approve");
            var template_id = 0;
            if ($("#template_id").length){
               template_id = $("#template_id").val();
            }
         if (($("#template_id").length)  && ($("#template_id").val() =="")){
                    $("#msg_text_failure").html(" Please select the KRA template !!");
                    $('#modaldemo5').modal('toggle');
                    $('#modaldemo5').modal('show');
                    $('#modaldemo5').modal('hide');
                    return false;
        } else {
                $.ajax({
                    type: 'POST',
                    url: rootpath + '/kra_masters/hr_approve/approve'  + '/' + <?php echo $kra_details['EmployeeRmChange']['id'];?>,
                    cache: false,
                    data: "&template_id=" +template_id ,
                    beforeSend: function () {
                        $(".preloader").show();
                    },
                    success: function (result) {
                        if (result == 'SUCCESS') {
                            $("#msg_text_success").html("Rm change request approved !!");
                            $('#modaldemo3').modal('toggle');
                            $('#modaldemo3').modal('show');
                            $('#modaldemo3').modal('hide');
                        } else {
                            $("#msg_text_failure").html(" Please try again !!");
                            $('#modaldemo5').modal('toggle');
                            $('#modaldemo5').modal('show');
                            $('#modaldemo5').modal('hide');
                        }
                        $(".preloader").fadeOut('500');
                    }});
                return false;
             }
     });
    $("#send_back_btn").click(function () {
        $("#action").val("send_back");
        var template_id = 0;
        if ($("#template_id").length){
           template_id = $("#template_id").val();
        }
        $.ajax({
            type: 'POST',
            url: rootpath + '/kra_masters/hr_approve/send_back'  + '/' + <?php echo $kra_details['EmployeeRmChange']['id'];?>,
            cache: false,
            data: "&template_id=" + template_id,
            beforeSend: function () {
                $(".preloader").show();
            },
            success: function (result) {
                if (result == 'SUCCESS') {
                    $("#msg_text_success").html("Rm change request rejected !!!");
                    $('#modaldemo3').modal('toggle');
                    $('#modaldemo3').modal('show');
                    $('#modaldemo3').modal('hide');
                    $(".preloader").fadeOut('500');
                } else {
                    $("#msg_text_failure").html("Please try again !!");
                    $('#modaldemo5').modal('toggle');
                    $('#modaldemo5').modal('show');
                    $('#modaldemo5').modal('hide');
                    $(".preloader").fadeOut('500');
                }
                $(".preloader").fadeOut('500');
            }});
    });
      });
      
    $(document).on('click','#ok_btn_success ,.new_page',function(){
       window.location.href = rootpath + '/kra_masters/hr_approve';
    });
    
    </script>

