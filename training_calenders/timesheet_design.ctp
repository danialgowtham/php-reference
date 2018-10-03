<style>
    .lms_background{
        background: none !important;
    }
    .accordion-one .card-header a::before {
        top: 10px;
    }
    #timesheet_table tfoot tr td{
        background: #fff !important;
        font-weight: 400;
    }
    .common_select{
        padding: 0px !important;
        height: 20px !important;
        width: 90px !important;
    }
    label{
        margin-bottom: 0px !important;
    }
    .border_class{
        border-top: 1px solid #dee2e6;
    }
    .focus_new{
            border: 1px solid #2e8de6 !important;
    }
</style>
<div class="card card-recent-messages col-4 pd-0" id="popup_div">
    <div class="card-header">
        <span>Task & Regularization</span>
        <span><i class="close_div fa fa-close cursor-pointer text-secondary"></i></span>
    </div><!-- card-header -->
    <div class="list-group list-group-flush" id="popup_div_body">
        <div class="row mg-0 pd-l-10 mg-t-5 pd-t-5 pd-b-5">
            <div class="col-lg-3 col-md-3 pd-0">
                <label class="form-control-label">Shift</label>
                <select  class="form-control select common_select" id="shift">
                    <option value="1">General</option>
                    <option value="2">I Shift</option>
                    <option value="3">II Shift</option>
                    <option value="4">III Shift</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-3 pd-0">
                <label class="form-control-label">Role</label>
                <select  class="form-control select common_select" id="role">
                    <option value="0">--Select Role--</option>
                    <option value="1">Developer</option>
                    <option value="2">Tester</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-3 pd-0">
                <label class="form-control-label">Phase</label>
                <select  class="form-control select common_select" id="phase">
                    <option value="0">--Select Phase--</option>
                    <option value="1">UI Development</option>
                    <option value="2">Automation Test</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-3 pd-0">
                <label class="form-control-label">Task</label>
                <select  class="form-control select common_select" id="task">
                    <option value="0">--Select Task--</option>
                    <option value="1">Learning</option>
                    <option value="2">KT</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-3 mg-t-10 pd-0">
                <label class="form-control-label">Regularization</label>
                <select  class="form-control select common_select" id="regularization">
                    <option value="">--Select Regularization--</option>
                    <option title=" Incorrect Available Hours" value=" Incorrect Available Hours"> Incorrect Available Hours</option>
                    <option title="Customer Working Day" value="Customer Working Day">Customer Working Day</option>
                    <option title="Incorrect Attendance Data" value="Incorrect Attendance Data">Incorrect Attendance Data</option>
                    <option title="Meeting at Customer Location" value="Meeting at Customer Location">Meeting at Customer Location</option>
                    <option title="No Attendance Data" value="No Attendance Data">No Attendance Data</option>
                    <option title="Old Timesheet Entry" value="Old Timesheet Entry">Old Timesheet Entry</option>
                    <option title="On Duty" value="On Duty">On Duty</option>
                    <option title="On Permission" value="On Permission">On Permission</option>
                    <option title="On Training" value="On Training">On Training</option>
                    <option title="On Travel" value="On Travel">On Travel</option>
                    <option title="Self Learning" value="Self Learning">Self Learning</option>
                    <option title="Work In Transit" value="Work In Transit">Work In Transit</option>
                    <option title="Worked - Company Holiday" value="Worked - Company Holiday">Worked - Company Holiday</option>
                    <option title="Worked - Week End" value="Worked - Week End">Worked - Week End</option>
                    <option title="Worked Additional Hours" value="Worked Additional Hours">Worked Additional Hours</option>
                </select>
            </div>
            <div class="col-lg-6 col-md-6 mg-t-10 pd-0 pd-r-20 d-none-normal">
                <input class="form-control" type="text" id="remarks" placeholder=" Enter Remarks">
            </div>
            <div class="col-lg-3 col-md-3 pd-0 mg-t-10  pd-r-20">
                <input class="form-control" type="text" id="time" placeholder="00:00">
            </div>
        </div>
    </div>
    <div class="card-footer border-top mg-t-5">
        <span class="add_task text-primary cursor-pointer"><i class="fa fa-plus mg-r-5"></i> Add More Task</span>
    </div>
</div><!-- list-group -->

<div class="slim-mainpanel">
    <div class="container pd-0 mg-b-20">
        <div class="slim-pageheader">
            <div class="text-dark font-weight-bold">
                <!--<span class="mg-r-10">Hello3</span>-->
            </div>
            <h6 class="slim-pagetitle pd-l-0 border-0"><i class="fa fa-calendar mg-l-5" style="color:#4662D4" ></i>
                Weekly TimeSheet
            </h6>
        </div><!-- slim-pageheader -->

        <div id="accordion" class="accordion-one" role="tablist" aria-multiselectable="true">
            <div class="card">
                <div class="card-header" role="tab" id="headingOne">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne" class="tx-gray-800 transition collapsed pd-10-force">
                        Guildlines
                    </a>
                </div><!-- card-header -->

                <div id="collapseOne" class="collapse" role="tabpanel" aria-labelledby="headingOne" style="">
                    <div class="card-body pd-5-force">
                        <ul>
                            <li>Please select check-box before submitting the Time sheet. Based on the selection only time sheet entry will be submitted</li>
                            <li>Please enter regularizations as separate row by choosing appropriate reason</li>
                            <li>Individual attendance hours for the current day will be available by next day at 10 a.m. IST for employees working from HTL India locations. However, time sheet can be entered and saved for the current day</li>
                            <li>In case of incorrect Attendance Hours and Available Hours please contact ideal support team (Ideal Support idealsupport@hindujatech.com)</li>
                            <li>Press Control+F5 to clear cache if Time sheets not loaded fully</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" role="tab" id="headingTwo">
                    <a class="collapsed tx-gray-800 transition pd-10-force" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Fields Description
                    </a>
                </div>
                <div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo">
                    <div class="card-body pd-5-force">
                        <ul>
                            <li>Office Hours: The minimum time required by associate’s to presence in the office including the lunch time. This data gets automatically populated from the system based on the location</li>
                            <li>Attendance Hours: The data from the swipe in and out. This data gets automatically populated from the swipe card system</li>
                            <li>Available hours: This is the productive work time. The associates are expected to capture their efforts equal or more than this time. This data gets automatically populated from the system based on the location</li>
                            <li>Time sheet hours: The associate’s actual efforts. The time sheet hours are expected to be equal or more than the available hours. This data needs to be manually entered by the associate</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-dark font-weight-bold float-right mg-t-20 mg-b-20">
            <span class="mg-r-10 tx-13 cursor-pointer" id="add_new_row"><i class="fa fa-plus mg-r-5 tx-16" style="color:#4662D4" ></i>Add Project</span>
            <span class="mg-r-10 tx-13"><i class="fa fa-file-excel-o mg-r-5 tx-16" style="color:#4662D4" ></i>Export</span>
            <span class="mg-r-10 tx-13"><i class="fa fa-calendar-plus-o mg-r-5 tx-16" style="color:#4662D4" ></i>Select Week</span>
        </div>
        <div>
            <table id="timesheet_table" class="table display responsive">
                <thead>
                    <tr>
                        <th class="wd-20p">Project</th>
                        <th class="wd-10p">Mon 01 AUG</th>
                        <th class="wd-10p">Tue 02 AUG<span class="text-danger" style="text-transform: capitalize;" title="State Emergency"> (Holiday)</span></th>
                        <th class="wd-10p">Wed 03 AUG</th>
                        <th class="wd-10p">Thu 04 AUG</th>
                        <th class="wd-10p">Fri 05 AUG <span class="text-danger" style="text-transform: capitalize;">(Leave Applied)</span></th>
                        <th class="wd-10p">Sat 06 AUG</th>
                        <th class="wd-10p">Sun 07 AUG</th>
                        <th class="wd-10p">Week Hours</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="row_1">
                        <td class="pd-l-0-force pd-r-0-force">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text" title="close">
                                        <i class="fa fa-plus-circle tx-20-force text-danger remove_row cursor-pointer" style="transform: rotate(50deg)" id="remove_1"></i>
                                    </div><!-- input-group-text -->
                                </div><!-- input-group-prepend -->
                                <select  class="form-control select project_class" id="project_1">
                                    <option value="0">--Select Project--</option>
                                    <option value="1">Ideal</option>
                                    <option value="2">Ideal2.o</option>
                                    <option value="3">Non Project Activity</option>
                                </select>
                            </div>
<!--                            <select  class="form-control select project_class" id="project_1">
                                <option value="0">--Select Project--</option>
                                <option value="1">Ideal</option>
                                <option value="2">Ideal2.o</option>
                                <option value="3">Ideal3.0</option>
                            </select>
                            -->                            <span class="project_span form-control bd-0" id="project_span_1">
                                <i class="fa fa-plus-circle tx-20-force text-danger remove_row cursor-pointer pd-b-13" style="transform: rotate(50deg)" id="remove_1"></i>
                                <label class="mg-l-10" id="project_label_1"></label>
                            </span>
                        </td>
                        <td><input type="text" class="form-control day_class"  placeholder="HH:MM" data-toggle="tooltip" data-placement="top" title="Office Hours - 09:30 Attendance Hours - 09:50 Available Hours - 09:00"></td>
                        <td><input type="text" class="form-control day_class"  placeholder="HH:MM" data-toggle="tooltip" data-placement="top" title="Office Hours - 09:30 Attendance Hours - 09:50 Available Hours - 09:00"></td>
                        <td><input type="text" class="form-control day_class"  placeholder="HH:MM" data-toggle="tooltip" data-placement="top" title="Office Hours - 09:30 Attendance Hours - 09:50 Available Hours - 09:00"></td>
                        <td><input type="text" class="form-control day_class"  placeholder="HH:MM" data-toggle="tooltip" data-placement="top" title="Office Hours - 09:30 Attendance Hours - 09:50 Available Hours - 09:00"></td>
                        <td><input type="text" class="form-control day_class" disabled placeholder="HH:MM" data-toggle="tooltip" data-placement="top" title="Office Hours - 09:30 Attendance Hours - 09:50 Available Hours - 09:00"></td>
                        <td><input type="text" class="form-control day_class"  placeholder="HH:MM" data-toggle="tooltip" data-placement="top" title="Office Hours - 09:30 Attendance Hours - 09:50 Available Hours - 09:00"></td>
                        <td><input type="text" class="form-control day_class"  placeholder="HH:MM" data-toggle="tooltip" data-placement="top" title="Office Hours - 09:30 Attendance Hours - 09:50 Available Hours - 09:00"></td>
                        <td><span class="form-control bd-0">0</span></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td><input type="button" class="btn btn-primary bd-0 mg-r-15 pd-r-15 pd-l-15" value="Save"><input type="button" class="btn btn-primary bd-0 mg-r-10" value="Submit"></td>
                        <td><span class="form-control bd-0">0</span></td>
                        <td><span class="form-control bd-0">0</span></td>
                        <td><span class="form-control bd-0">0</span></td>
                        <td><span class="form-control bd-0">0</span></td>
                        <td><span class="form-control bd-0">0</span></td>
                        <td><span class="form-control bd-0">0</span></td>
                        <td><span class="form-control bd-0">0</span></td>
                        <td><span class="form-control bd-0">0</span></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>


<script>
    $(document).ready(function () {
        $("#popup_div").hide();
        $(".project_span").hide();
        $('[data-toggle="tooltip"]').tooltip({
            template: '<div class="tooltip tooltip-primary" role="tooltip"><div class="arrow"></div><div class="tooltip-inner text-left"></div></div>'
        });
        $(document).on('click', ".add_task", function () {
//            $("#popup_div_body").css('max-height','none');
            var add_div = "<div class='row mg-0 pd-l-10 mg-t-5 pd-t-5 pd-b-5 border_class'><div class='col-lg-3 col-md-3 pd-0'><label class='form-control-label'>Shift</label>"
            add_div += "<select class='form-control select common_select' id='shift'>"
            add_div += "<option value='1'>General</option>"
            add_div += "<option value='2'>I Shift</option>"
            add_div += "<option value='3'>II Shift</option>"
            add_div += "<option value='4'>III Shift</option>"
            add_div += "</select>"
            add_div += "</div>"
            add_div += "<div class='col-lg-3 col-md-3 pd-0'>"
            add_div += "<label class='form-control-label'>Role</label>"
            add_div += "<select class='form-control select common_select' id='role'>"
            add_div += "<option value='0'>--Select Role--</option>"
            add_div += "<option value='1'>Developer</option>"
            add_div += "<option value='2'>Tester</option>"
            add_div += "</select>"
            add_div += "</div>"
            add_div += "<div class='col-lg-3 col-md-3 pd-0'>"
            add_div += "<label class='form-control-label'>Phase</label>"
            add_div += "<select class='form-control select common_select' id='phase'>"
            add_div += "<option value='0'>--Select Phase--</option>"
            add_div += "<option value='1'>UI Development</option>"
            add_div += "<option value='2'>Automation Test</option>"
            add_div += "</select>"
            add_div += "</div>"
            add_div += "<div class='col-lg-3 col-md-3 pd-0'>"
            add_div += "<label class='form-control-label'>Task</label>"
            add_div += "<select class='form-control select common_select' id='task'>"
            add_div += "<option value='0'>--Select Task--</option>"
            add_div += "<option value='1'>Learning</option>"
            add_div += "<option value='2'>KT</option>"
            add_div += "</select>"
            add_div += "</div>"
            add_div += "<div class='col-lg-3 col-md-3 mg-t-10 pd-0'>"
            add_div += "<label class='form-control-label'>Regularization</label>"
            add_div += "<select class='form-control select common_select' id='regularization'>"
            add_div += "<option value=''>--Select Regularization--</option>"
            add_div += "<option title=' Incorrect Available Hours' value=' Incorrect Available Hours'> Incorrect Available Hours</option>"
            add_div += "<option title='Customer Working Day' value='Customer Working Day'>Customer Working Day</option>"
            add_div += "<option title='Incorrect Attendance Data' value='Incorrect Attendance Data'>Incorrect Attendance Data</option>"
            add_div += "<option title='Meeting at Customer Location' value='Meeting at Customer Location'>Meeting at Customer Location</option>"
            add_div += "<option title='No Attendance Data' value='No Attendance Data'>No Attendance Data</option>"
            add_div += "<option title='Old Timesheet Entry' value='Old Timesheet Entry'>Old Timesheet Entry</option>"
            add_div += "<option title='On Duty' value='On Duty'>On Duty</option>"
            add_div += "<option title='On Permission' value='On Permission'>On Permission</option>"
            add_div += "<option title='On Training' value='On Training'>On Training</option>"
            add_div += "<option title='On Travel' value='On Travel'>On Travel</option>"
            add_div += "<option title='Self Learning' value='Self Learning'>Self Learning</option>"
            add_div += "<option title='Work In Transit' value='Work In Transit'>Work In Transit</option>"
            add_div += "<option title='Worked - Company Holiday' value='Worked - Company Holiday'>Worked - Company Holiday</option>"
            add_div += "<option title='Worked - Week End' value='Worked - Week End'>Worked - Week End</option>"
            add_div += "<option title='Worked Additional Hours' value='Worked Additional Hours'>Worked Additional Hours</option>"
            add_div += "</select>"
            add_div += "</div>"
            add_div += "<div class='col-lg-6 col-md-6 mg-t-10 pd-0 pd-r-20 d-none-normal'>"
            add_div += "<input class='form-control' type='text' id='remarks' placeholder=' Enter Remarks'>"
            add_div += "</div>"
            add_div += "<div class='col-lg-3 col-md-3 pd-0 mg-t-10  pd-r-20'>"
            add_div += "<input class='form-control' type='text' id='time' placeholder='00:00'>"
            add_div += "</div>"
            add_div += "</div>"
            $("#popup_div_body").append(add_div);
//             setTimeout(function(){
//                $("#popup_div_body").css('max-height','200px');
//             },100);
        });
        $(document).on('change', "#regularization", function () {
            if (this.value != '' && this.value != null) {
                $("#remarks").parent().show();
            } else {
                $("#remarks").parent().hide();
            }
        });
        $(document).on('click', ".close_div", function () {
            $("#popup_div").hide();
        });
        $(document).on('click', function (e) {
            if($(e.target).hasClass('transition')){
                $("#popup_div").hide();
            }
        });
        $(document).on('click', ".day_class", function () {
            $(".day_class").removeClass('focus_new')
            var input_position = $(this).position();
            $("#popup_div").css('top', input_position.top + 42);
            $("#popup_div").css('left', input_position.left);
            $("#popup_div").css('position', 'absolute');
            $("#popup_div").css('display', 'block');
            $(this).addClass('focus_new');
        });
        $(document).on('change', ".project_class", function () {
            var last_row = this.id;
            $(this).parent().hide();
            var row_id = last_row.substring(last_row.lastIndexOf('_') + 1);
            $("#project_label_" + row_id).text($(this).find(":selected").text());
            $("#project_span_" + row_id).css('display', 'block');
        })

        $(document).on('click', '#add_new_row', function () {
            var last_row = $('#timesheet_table tbody tr:last').attr('id');
            if (last_row) {
                var previous_row_id = last_row.substring(last_row.lastIndexOf('_') + 1);
                var row_id = ++previous_row_id;
            } else {
                row_id = 1;
            }
            var add_row = "<tr id='row_" + row_id + "'>";
            add_row += "<td class='pd-l-0-force pd-r-0-force'> <div class='input-group'><div class='input-group-prepend'><div class='input-group-text' title='close'>\n\
                        <i class='fa fa-plus-circle tx-20-force text-danger remove_row cursor-pointer' style='transform: rotate(50deg)' id='remove_" + row_id + "'></i></div></div>\n\
                        <select id='project_" + row_id + "'  class='form-control select project_class'><option value='0'>--Select Project--</option><option value='1'>Ideal</option><option value='2'>Ideal2.o</option><option value='3'>Ideal3.o</option></select></div>\n\
                        <span class='project_span form-control bd-0 d-none-normal' id='project_span_" + row_id + "'><i class='fa fa-plus-circle tx-20-force text-danger remove_row cursor-pointer pd-b-13' style='transform: rotate(50deg)' id='remove_" + row_id + "'></i>\n\
                        <label class='mg-l-10' id='project_label_" + row_id + "'></label></span></td>";
            add_row += "<td><input type='text' class='form-control day_class' placeholder='HH:MM' data-toggle='tooltip' data-placement='top' title='Office Hours - 09:30 Attendance Hours - 09:50 Available Hours - 09:00'></td>";
            add_row += "<td><input type='text' class='form-control day_class' placeholder='HH:MM' data-toggle='tooltip' data-placement='top' title='Office Hours - 09:30 Attendance Hours - 09:50 Available Hours - 09:00'></td>";
            add_row += "<td><input type='text' class='form-control day_class' placeholder='HH:MM' data-toggle='tooltip' data-placement='top' title='Office Hours - 09:30 Attendance Hours - 09:50 Available Hours - 09:00'></td>";
            add_row += "<td><input type='text' class='form-control day_class' placeholder='HH:MM' data-toggle='tooltip' data-placement='top' title='Office Hours - 09:30 Attendance Hours - 09:50 Available Hours - 09:00'></td>";
            add_row += "<td><input type='text' class='form-control day_class' disabled placeholder='HH:MM' data-toggle='tooltip' data-placement='top' title='Office Hours - 09:30 Attendance Hours - 09:50 Available Hours - 09:00'></td>";
            add_row += "<td><input type='text' class='form-control day_class' placeholder='HH:MM' data-toggle='tooltip' data-placement='top' title='Office Hours - 09:30 Attendance Hours - 09:50 Available Hours - 09:00'></td>";
            add_row += "<td><input type='text' class='form-control day_class' placeholder='HH:MM' data-toggle='tooltip' data-placement='top' title='Office Hours - 09:30 Attendance Hours - 09:50 Available Hours - 09:00'></td>";
            add_row += "<td><span class='form-control bd-0'>0</span></td>";
            add_row += "</tr>";
            $("#timesheet_table").append(add_row);
            $(".remove_row").show();
            $("#popup_div").hide();
             $(".day_class").removeClass('focus_new')
            $('#timesheet_table tfoot tr').show();
            $('[data-toggle="tooltip"]').tooltip({
                template: '<div class="tooltip tooltip-primary" role="tooltip"><div class="arrow"></div><div class="tooltip-inner text-left"></div></div>'
            });
        });
        $(document).on('click', '.remove_row', function () {
            var remove_row_id = $(this).attr('id');
            var row_id = remove_row_id.substring(remove_row_id.lastIndexOf('_') + 1);
            $("#row_" + row_id).remove();
            $("#popup_div").hide();
            $(".day_class").removeClass('focus_new')
            if ($('#timesheet_table tbody tr').length == 0) {
                $("#add_new_row").trigger("click");
            }
        });
    });
</script>
