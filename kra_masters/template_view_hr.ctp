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
                                <th class="wd-10p">Manager</th>
                                <th class="wd-10p">Year</th>
                                <th class="wd-10p">Status</th>
                                <th class="wd-5p">Action</th>
                                
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
<div id="modaldemo7" class="modal fade ">
    <div class="modal-dialog modal-sm" role="document" style="min-width: 850px;">
        <div class="modal-content bd-0 tx-14 min">
            <div class="modal-header">
                <h6 class="tx-14 mg-l-350 mg-b-0 tx-uppercase tx-inverse tx-bold">Preview KRA</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-20">
                <div class="templateDetails">
                        </div>
            </div>
        </div>
    </div><!-- modal-dialog -->
</div>        
        
        
        
<!--        
        <div id="modaldemo7" class="modal fade ">
             <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-dialog " role="document" style="min-width: 850px;">
                <div class="modal-content tx-size-sm" > 
                    <div class="modal-body pd-y-20 pd-x-20">
                        <div class="templateDetails">
                        </div>
                    </div> modal-body 
                </div> modal-content 
            </div> modal-dialog 
        </div> modal -->
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
                    "targets": [0, 4, 5],
                    "orderable": false,
                }],
            "sAjaxSource": '<?php echo $html->Url(array('controller' => 'kra_masters', 'action' => 'template_view_datatables/')); ?>',
            language: {
                searchPlaceholder: 'Search...',
                sSearch: '',
                lengthMenu: '_MENU_ Choose Number of Records',
            }
        });
        $("#datatable_filter").hide(); //<option value="2">Project Manager</option>  for demo pm hiding 
        $('#datatable_length').after('<div id="datatable_filter" class="dataTables_filter"><label><input type="search" class="" placeholder="Search..." aria-controls="datatable" id="search_datatables"></label><label class="ml-3"><select class="form-control select2" name="tesing" id="select_individual">\n\
        <option value="0">--Select Field--</option><option value="1">Employee</option>\n\
        <option value="2">Manager</option><option value="3">Year</option>\n\
        </label></div>');

        
           
        $('.toggle').toggles({
            on: false,
            height: 26
        });
        $('#search_datatables').on('keyup', function () {
            $('#datatable').DataTable().search(
                    $('#search_datatables').val(),
                    $('#select_individual').val()
                    ).draw();

        });
        
        $(document).on('click','.click_span',function(){
            $.ajax({
                type: 'POST',
                url: rootpath + '/kra_masters/template_view/',
                cache: false,
                data: "&template_id=" +  this.id,
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
        
       
    });


</script>


