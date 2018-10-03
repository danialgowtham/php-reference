<style>
    .dataTables_filter{
        padding-right: 8px;
    }
    .search_row th{
        background-color: #fff !important;
        border: none !important;
    }
    .toggle-wrapper{
        top:15px
    }
    .external_css{
        width: 63px !important;
        margin-left: 25px;
    }
    .trigger_mail{
        color: #5b636a;
        font-size: 16px;
        margin-left: 7px;
        cursor: pointer;
        position: absolute;
        margin-top: 2px;
    }
</style>
<div class="slim-mainpanel">
    <div class="container">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
            </ol>
            <h6 class="slim-pagetitle">Kra Report</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">
            <div class="float-right mg-b-10">
                <a href="javascript:;" onclick="javascript:back_button()"><i class="fa fa-arrow-left"></i>Back</a>
            </div>

            <div class="table-responsive">
                <div class="table-wrapper">
                    <table id="datatable" class="table display responsive nowrap">
                        <thead>
                            <tr>
                                <th class="wd-5p">#</th>
                                <th class="wd-10p">Employees</th>
                                <!--<th class="wd-10p">Project Manager</th>--> 
                                <th class="wd-10p">From Date</th>
                                <th class="wd-10p">To Date</th>
                                <?php if ($view != 'hr_view') { ?>
                                    <th class="wd-10p">Status</th>
                                <?php } ?>
                                <th class="wd-5p" id="rating">Rating</th>
                                <th class="wd-5p">Year</th>
                                <?php if ($view != 'rm_view') { ?>
                                    <th class="wd-10p">Manager</th>
                                <?php } ?>
                                <th class="wd-10p">Band</th>
                                <?php if ($view == 'rm_view') { ?>
                                    <th class="wd-10p"><?php echo $sub_practice; ?></th>
                                <?php } else { ?>
                                    <th class="wd-10p">Sub Sbu</th>
                                <?php } ?>
                                <th class="wd-5p">Action</th>
                            </tr>
                        </thead>
                        <thead>
                            <tr class="search_row">
                                <th class="wd-5p"></th>
                                <th class="wd-10p"><input type="text" placeholder="Search Employee" id="employee_search"/></th>
                                <!--<th class="wd-10p"><input type="text" placeholder="Search Manager" id="manager_search"/></th>--> 
                                <th class="wd-10p"></th>
                                <th class="wd-10p"></th>
                                <?php if ($view != 'hr_view') { ?>
                                    <th class="wd-10p"></th>
                                <?php } ?>
                                <th class="wd-5p"></th>
                                <th class="wd-5p"></th>
                                <th class="wd-5p"></th>
                                <?php if ($view != 'rm_view') { ?>
                                    <th class="wd-10p"></th>
                                <?php } ?>
                                <th class="wd-10p"></th> 
                                <th class="wd-10p"></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div><!-- table-wrapper -->
        </div><!-- section-wrapper -->
    </div><!-- container -->
</div><!-- slim-mainpanel -->
<div id="modaldemo6" class="modal fade">
    <div class="modal-dialog modal-lg wd-400 " role="document">
        <div class="modal-content bd-0 tx-14 ht-150">

                <label class="tx-14 mg-b-0 text-center tx-inverse mg-t-20 mg-b-10">This will Trigger a Mail To Your Reportee.<br> Do You Want To Continue?</label>
            <div class="modal-footer justify-content-center mg-b-20">
                <button type="button" class="btn btn-primary " id="trigger_mail_button"><i class="fa fa-thumbs-up pd-r-10"></i>Yes</button>
                <button type="button" class="btn btn-primary mg-l-10" id="close_popup"><i class="fa fa-thumbs-down pd-r-10"></i>No </button>
            </div>
        </div>
    </div><!-- modal-dialog -->
</div><!-- modal -->
<div id="modaldemo5" class="modal fade">
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
echo $html->css('/kra/lib/datatables/css/jquery.dataTables.css');
echo $html->css('/kra/lib/select2/css/select2.min.css');
echo $html->css('/kra/lib/jquery-toggles/css/toggles-full.css');

//echo $javascript->link('/kra/lib/datatables/js/jquery.dataTables.js');
echo $javascript->link('/kra/lib/datatables-responsive/js/dataTables.responsive.js');
echo $javascript->link('/kra/lib/select2/js/select2.min.js');
echo $javascript->link('/kra/lib/jquery-toggles/js/toggles.min.js');
?>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.js"></script>

<script>
                    $(document).ready(function () {
                        var table = $('#datatable').DataTable({
                            "responsive": true,
                            "bProcessing": true,
                            "bServerSide": true,
                            //"bFilter":false,
                            "iDisplayLength": 15,
                            "sPaginationType": "full_numbers",
                            "columnDefs": [{
                                    "targets": [0, 9],
                                    "orderable": false,
                                }],
                            "sAjaxSource": '<?php echo $html->Url(array('controller' => 'kra_masters', 'action' => 'reportee_view_datatables/' . $view)); ?>',
                            "data": {testing: $('#select_individual').val()},
                            language: {
                                searchPlaceholder: 'Search...',
                                sSearch: '',
                                lengthMenu: '_MENU_ Choose Number of Records',
                            }
                        });
                        $("#datatable_filter").hide(); //<option value="2">Project Manager</option>  for demo pm hiding 
                        $('#datatable_length').after('<div id="datatable_filter" class="dataTables_filter"><label><input type="search" class="" placeholder="Search..." aria-controls="datatable" id="search_datatables"></label><label class="ml-3"><select class="form-control select2" name="tesing" id="select_individual">\n\
        <option value="0">--Select Field--</option><option value="1">Employee</option>\n\
        <option value="3">Status</option><option value="4">Overall Rating</option>\n\
        <option value="5">Year</option><option value="6">Band</option><option value="7">Sub Sbu</option></label></div>');

<?php if ($view == 'hr_view') { ?>
                            $('#datatable_filter').append('<lable><div class="toggle-wrapper wd-100"><lable>Overall Rating</lable><div class="toggle toggle-light success external_css"></div></div><lable>');
                            $('#datatable_length').css('position', 'relative');
                            $('#datatable_length').css('top', '25px');
                            $('#datatable_length').css('left', '2px');

<?php } ?>

                        $('.toggle').toggles({
                            on: false,
                            height: 26
                        });
                        $("#datatable_length label select").val('10');
                        $('#search_datatables').on('keyup', function () {
                            $('#datatable').DataTable().search(
                                    $('#search_datatables').val(),
                                    $('#select_individual').val()
                                    ).draw();

                        });
                        $('#employee_search').on('keyup', function () {
                            $('#datatable').DataTable().column(0).search(
                                    $('#employee_search').val()
                                    ).draw();

                        });
                        $('#manager_search').on('keyup', function () {
                            $('#datatable').DataTable().column(3).search(
                                    $('#manager_search').val()
                                    ).draw();

                        });

                        $('.toggle-light').click(function () {
                            var toggleNo = $(this).find('.toggle-on').hasClass("active");
                            if (toggleNo) {
                                $("#rating").text('Overall Rating');
                                $('#datatable').DataTable().column(8).search(
                                        'active'
                                        ).draw();
                            } else {
                                $("#rating").text('Rating');
                                $('#datatable').DataTable().column(8).search(
                                        'deactive'
                                        ).draw();
                            }

                        });

                        $(document).on('click', ".trigger_mail", function () {
                            $("#trigger_mail_button").attr('mapping_id', this.id);
                            $('#modaldemo6').modal('toggle');
                            $('#modaldemo6').modal('show');
                        });
                        $(document).on('click', "#trigger_mail_button", function () {
                            $.ajax({
                                type: "POST",
                                url: rootpath + '/kra_masters/kra_rm_trigger/' + $("#trigger_mail_button").attr('mapping_id'),
                                beforeSend: function () {
                                    $(".preloader").show();
                                    $('#modaldemo6').modal('toggle');
                                    $('#modaldemo6').modal('hide');
                                },
                                success: function (result)
                                {
                                    if (result == 'SUCCESS') {
                                        $("#msg_text_success").html("Mail Sent Sucessfully!!!");
                                        $('#modaldemo3').modal('toggle');
                                        $('#modaldemo3').modal('show');
                                        $('#modaldemo3').modal('hide');
                                    } else {
                                        $("#msg_text_failure").html("Something went wrong!! Please try again!!");
                                        $('#modaldemo5').modal('toggle');
                                        $('#modaldemo5').modal('show');
                                        $('#modaldemo5').modal('hide');
                                    }
                                    $(".preloader").hide();
                                }
                            });
                        });
                        $(document).on('click', '#close_popup', function () {
                            $('#modaldemo6').modal('toggle');
                            $('#modaldemo6').modal('hide');
                        });
                    });


</script>

