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
                                <th class="wd-10p">Template Name</th>
                                <th class="wd-10p">Band</th> 
                                <th class="wd-10p">Sub Band</th>
                                <th class="wd-10p">Unit</th>
                                <th class="wd-10p">SBU</th>
                                <th class="wd-5p">Sub SBU</th>
                                <th class="wd-5p">Employee</th>
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
<?php
echo $html->css('/kra/lib/datatables/css/jquery.dataTables.css');
echo $html->css('/kra/lib/select2/css/select2.min.css');
echo $javascript->link('/kra/lib/datatables/js/jquery.dataTables.js');
echo $javascript->link('/kra/lib/datatables-responsive/js/dataTables.responsive.js');
echo $javascript->link('/kra/lib/select2/js/select2.min.js');
?>

 
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
                    "targets": [0,8],
                    "orderable": false,
                }],
            "sAjaxSource": '<?php echo $html->Url(array('controller' => 'kra_masters', 'action' => 'kra_template_list_datatables')); ?>',
            "data": {testing: $('#select_individual').val()},
            language: {
                searchPlaceholder: 'Search...',
                sSearch: '',
                lengthMenu: '_MENU_ Choose Number of Records',
            }
        });
        
    });


</script>


