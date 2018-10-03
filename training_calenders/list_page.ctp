<style>
    .dataTables_filter{
        padding-right: 8px;
    }
    .search_row_new th{
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
<div class="slim-mainpanel mg-b-20">
    <div class="container">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
            </ol>
            <h6 class="slim-pagetitle">Training List</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">
            <form id="export_form" name="export_form" method="post">
                <?php if ($view == 'hr_view') { ?>
                    <div class="float-right mg-b-10">
                        <span id="export_all" class="export_span text-primary cursor-pointer"><i class="fa fa-file-excel-o"></i> Export</span>
                        <a href="<?php echo BASE_PATH; ?>training_calenders/add_training" class="mg-l-10" ><i class="fa fa-plus"></i> Add Training</a>

                    </div>
                <?php } ?>
                <div class="table-responsive">
                    <div class="table-wrapper">
                        <table id="datatable" class="table display responsive nowrap">
                            <thead>
                                <tr>
                                    <th class="wd-5p">#</th>
                                    <th class="wd-10p">Title</th>
                                    <th class="wd-10p">Venue</th>
                                    <th class="wd-10p">Date</th>
                                    <th class="wd-10p">Band</th>
                                    <th class="wd-10p">Unit</th>
                                    <th class="wd-5p">No Seats Remaining</th>
                                    <?php if ($view == 'hr_view') { ?>
                                        <th class="wd-10p">Status</th>
                                    <?php } ?>
                                    <th class="wd-5p">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $count=1; foreach($training_details as $training_detail){
                                    $bands = explode(',', $training_detail['TrainingCalender']['band']);
                                    $select_band = array();
                                    foreach ($bands as $key => $value) {
                                        $select_band[] = $band_list[$value];
                                    }
                                    $select_band = implode(',', $select_band);
                                    ?>
                                <tr>
                                    <td><span><?php echo $count++; ?></span></td>
                                    <td><span><?php echo $training_detail['TrainingCalender']['title'] ?></span></td>
                                    <td><span><?php echo !empty($training_detail['City']['city'])?$training_detail['City']['city']:$training_detail['TrainingCalender']['venue'] ?></span></td>
                                    <td><span><?php echo !empty($training_detail['TrainingCalender']['date'])?date('d-m-Y', strtotime($training_detail['TrainingCalender']['date'])):'' ?></span></td>
                                    <td><span><?php echo $select_band ?></span></td>
                                    <td><span><?php echo !empty($training_detail['CompanyStructure']['name'])?$training_detail['CompanyStructure']['name']:$training_detail['TrainingCalender']['su_id'] ?></span></td>
                                    <td class="text-center"><span><?php echo $training_detail['TrainingCalender']['max_nomination']-$training_detail[0]['total_nominated'] ?></span></td>
                                    <?php if ($view == 'hr_view') { ?>
                                        <td><span  class="tx-12 <?php echo $status_class[$training_detail['ConfigurationValue']['configuration_value']] ?>"><?php echo $training_detail['ConfigurationValue']['configuration_value']  ?></span></td>
                                     <?php } ?>
                                        <td><span>
                                                <?php 
                                                    $row_temp = "<a href='" . BASE_PATH . "training_calenders/training_view/" . $training_detail['TrainingCalender']['id'] . "/" . $view . "' style='color: #5b636a;font-size: 15px;padding-left: 10px;'><i class='fa fa-eye'></i></a>";
                                                    if (in_array($training_detail['ConfigurationValue']['configuration_value'], $edit_status) && $view == 'hr_view') {
                                                        $row_temp .= " <a href='" . BASE_PATH . "training_calenders/edit_training/" . $training_detail['TrainingCalender']['id'] . "' style='color: #5b636a;font-size: 15px;'><i class='icon ion-compose'></i></a>";
                                                    }
                                                    if ($view == 'hr_view') {
                                                        $row_temp .= "<span id='" . $training_detail['TrainingCalender']['id'] . "' class='export_span' style='color: #5b636a;font-size: 15px;padding-left: 6px;' Title='Export'><i class='fa fa-file-excel-o cursor-pointer'></i></span>";
                                                    }
                                                echo $row_temp; ?>
                                            </span>
                                        </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div><!-- table-wrapper -->
            </form>
        </div><!-- section-wrapper -->
    </div><!-- container -->
</div><!-- slim-mainpanel -->
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.js"></script>
<?php
echo $html->css('/kra/lib/datatables/css/jquery.dataTables.css');
echo $html->css('/kra/lib/select2/css/select2.min.css');
echo $html->css('/kra/lib/jquery-toggles/css/toggles-full.css');

//echo $javascript->link('/kra/lib/datatables/js/jquery.dataTables.js');
echo $javascript->link('/kra/lib/datatables-responsive/js/dataTables.responsive.js');
echo $javascript->link('/kra/lib/jquery-ui/js/jquery-ui.js');
?>

<script>
    $(document).ready(function () {

        $(document).on('click','.export_span',function () {
            var action="<?php echo BASE_PATH; ?>training_calenders/export_training/"+this.id;
            $("#export_form").attr('action',action)
            $("#export_form").submit();
        });

        var table = $('#datatable').DataTable(
                {
            "iDisplayLength": 10,
            "sPaginationType": "full_numbers",
            language: {
                searchPlaceholder: 'Search...',
                sSearch: '',
                lengthMenu: '_MENU_ Choose Number of Records',
            }
        });
        
         $("#datatable_length label select").val('10');
         });
        


</script>

