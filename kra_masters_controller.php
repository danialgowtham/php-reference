<?php

App::import('Sanitize');
App::import('Vendor', 'php-excel-reader/excel_reader2');

class KraMastersController extends AppController {

    var $name = 'KraMasters';
    var $helpers = array('Html', 'Form', 'Ajax', 'Javascript', 'DatePicker', 'Xls');
    var $uses = array('EmployeeKraMapping', 'ConfigurationValue', 'RequestEvaluvation', 'KraFeedback', 'EmployeeReview', 'EmployeeRmChange', 'CompanyStructure', 'Designation', 'KraTemplate', 'Band', 'Kra', 'KraUom', 'Employee', 'ProjectTeamAllocation', 'KraMaster', 'EmployeeKraDetail', 'KraPmReview', 'KraPmMaster', 'KraEventRecord', 'EmployeeKraRating', 'KraFrequency', 'Customer', 'Project', 'KraClientFeedback', 'KraTemplateMapping', 'EmployeeKraRating', 'SbuTemplateMapping', 'SubsbuTemplateMapping', 'EmployeeTemplateMapping', 'UnitTemplateMapping', 'BandTemplateMapping', 'SubbandTemplateMapping', 'KraRmMaster', 'KraRmReview', 'KraClientFeedbackCreation', 'HeadConfiguration', 'EmployeeInfoHistory', 'DtlEmployeeWorkexperience', 'Designation', 'Band', 'AaEligibleAssociate', 'CaEligibleAssociate', 'KraUpload', 'EmployeeKraNormalization','DlFunctionMaster','KraRmTrigger','KraCommentsMaster','KraComment');
    var $components = array('RequestHandler', 'Email', 'Session', 'Common');

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('employee_review', 'ajax_add_kra_template', 'hr_kra_templates', 'sub_bands', 'set_selected_menu', 'employee_edit', 'index', 'hr_view_datatables', 'reportee_view_datatables', 'reportee_view', 'hr_view', 'employee_kra', 'get_kra_status_list', 'reportees_kra', 'employee_index', 'employee_view', 'pm_review', 'reportees_list', 'event_record', 'calculate_overall_rating', 'client_feedback', 'get_projects', 'hr_index', 'ajax_kra_mapping_data', 'sub_band_dynamic', 'unit_dynamic', 'ajaxGenerateReportingManagers', 'kra_template_mapping_list', 'kra_template_list_datatables', 'sbu_ssu_search', 'sub_sbu_ssu_search', 'kra_mail_reportees', 'rm_feedback', 'hr_approve', 'client_rating_save', 'client_feedback_creation', 'get_mail_ids', 'get_customer', 'get_rm_comments', 'rm_approve', 'get_transfer_approvers', 'template_view', 'employee_file_upload', 'remove_upload', 'reviewer_comments', 'notify_hr', 'save_normalization', 'normalizer_comments', 'template_view_hr', 'template_view_datatables','kra_rm_trigger','kra_record_comments');
    }

    function index($hrView = null) {
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes
        $this->layout = "kra";
        if ($hrView) {
            $this->set('view', $hrView);
            if ($_POST) {
                $emp_manager_ids = explode(",", $_POST['selected']);
                $this->layout = false;
                $emp_head_ids = $this->employeeManagerRecursive($_POST['selected']);
                $emp_head_ids = array_merge($emp_head_ids, $emp_manager_ids);
            }
        }

        $reporties = $this->Employee->find('list', array('fields' => array('id', 'employee_number'), 'conditions' => array('Employee.manager' => $this->Auth->user('employee_id'))));
//        $total_reporties_data = $this->Employee->find('all', array('fields' => array('Employee.*'), 'conditions' => array('Employee.manager' => $this->Auth->user('employee_id'),'((Employee.employment_status in ("p","e") or (Employee.employment_status ="v" and Employee.band_id not in(33,34))))')));
        $employeesInProject = $this->ProjectTeamAllocation->find('list', array('fields' => array('employee_id', 'project_id'), 'conditions' => array('ProjectTeamAllocation.employee_id' => array_keys($reporties), 'ProjectTeamAllocation.deleted' => 0), 'group' => 'ProjectTeamAllocation.employee_id'));
        $mappedEmps = $this->EmployeeKraMapping->find('all', array('conditions' => array('EmployeeKraMapping.employee_id' => array_keys($reporties), 'EmployeeKraMapping.deleted' => 0, 'EmployeeKraMapping.manager_id' => $this->Auth->user('employee_id'))));
        $conditions = array('((Employee.employment_status in ("p","e") or (Employee.employment_status ="v" and Employee.band_id not in(33,34))))');
        if (isset($emp_head_ids)) {
            $conditions = array('((Employee.employment_status in ("p","e") or (Employee.employment_status ="v" and Employee.band_id not in(33,34))))', 'Employee.manager' => $emp_head_ids);
        }

        if (!$hrView) {
            $conditions = array('((Employee.employment_status in ("p","e") or (Employee.employment_status ="v" and Employee.band_id not in(33,34))))', 'Employee.manager' => $this->Auth->user('employee_id'));
        }

        $this->Employee->recursive = -1;
        $employeekraDetails = $this->Employee->find('all', array('fields' => array('EmployeeKraMapping.*','Employee.*',
                'CONCAT(Employee.employee_number," - ",Employee.first_name," ", Employee.last_name) as Employee_name',
            ), 'joins' => array(
                array(
                    'table' => 'employee_kra_mappings',
                    'alias' => 'EmployeeKraMapping',
                    'type' => 'LEFT',
                    'conditions' => array('EmployeeKraMapping.employee_id = Employee.id')
                )
            )
            , 'conditions' => $conditions));
        $totalEmployee = array();
        ;
        $mappedEmployee = array();
        $notMapped = array();
        $mappedEmployeeSaved = array();
        $mappedEmployeeWaiting = array();
        $mappedEmployeeOpen = array();
        $mappedEmployeeRevied = array();
        $mappedEmployeeSendback = array();
        $ratingCount = array();
        foreach ($employeekraDetails as $employeekraDetail) {
            $totalEmployee[]['employee_name'] = $employeekraDetail[0]['Employee_name'];
            if ($employeekraDetail['EmployeeKraMapping']['id']) {
                $mappedEmployee[]['employee_name'] = $employeekraDetail[0]['Employee_name'];
                switch ($employeekraDetail['EmployeeKraMapping']['status']) {
                    case 'o':
                        $mappedEmployeeOpen[]['employee_name'] = $employeekraDetail[0]['Employee_name'];
                        break;
                    case 'a':
                        $mappedEmployeeRevied[]['employee_name'] = $employeekraDetail[0]['Employee_name'];
                        $mappedEmployeeRevied[$employeekraDetail['EmployeeKraMapping']['employee_id']]['rating'] = $employeekraDetail['EmployeeKraMapping']['overall_rating'];
                        if (isset($ratingCount[$employeekraDetail['EmployeeKraMapping']['overall_rating']])) {
                            $ratingCount[$employeekraDetail['EmployeeKraMapping']['overall_rating']] = $ratingCount[$employeekraDetail['EmployeeKraMapping']['overall_rating']] + 1;
                        } else {
                            $ratingCount[$employeekraDetail['EmployeeKraMapping']['overall_rating']] = 1;
                        }

                        break;
                    case 'm':
                        $mappedEmployeeWaiting[$employeekraDetail['EmployeeKraMapping']['employee_id']]['employee_name'] = $employeekraDetail[0]['Employee_name'];
                        $mappedEmployeeWaiting[$employeekraDetail['EmployeeKraMapping']['employee_id']]['kra_mappind_id'] = $employeekraDetail['EmployeeKraMapping']['id'];
                        break;
                    case 's':
                        $mappedEmployeeSaved[]['employee_name'] = $employeekraDetail[0]['Employee_name'];
                        break;
                    case 'r':
                        $mappedEmployeeSendback[]['employee_name'] = $employeekraDetail[0]['Employee_name'];
                        break;
                }
            } else {
                $notMapped[]['employee_name'] = $employeekraDetail[0]['Employee_name'];
            }
        }
        $graphData = '[';
        $last = count($ratingCount);
        $i = 1;
        ksort($ratingCount);
        foreach ($ratingCount as $key => $rating) {
            if ($i != $last) {
                $graphData .= '[' . $key . ',' . $rating . '],';
            } else {
                $graphData .= '[' . $key . ',' . $rating . ']';
            }
            $i++;
        }
        $graphData .= ']';
        if ($graphData == '[]') {
            $graphData = '[[0.0]]';
        }
//        $this->log($employeekraDetails);
        $this->set('total_reporties_data', $employeekraDetails);
        $this->set('graphData', $graphData);
        $this->set('totalEmployee', $totalEmployee);
        $this->set('mappedEmployee', $mappedEmployee);
        $this->set('mappedEmployeeOpen', $mappedEmployeeOpen);
        $this->set('mappedEmployeeRevied', $mappedEmployeeRevied);
        $this->set('mappedEmployeeWaiting', $mappedEmployeeWaiting);
        $this->set('mappedEmployeeSaved', $mappedEmployeeSaved);
        $this->set('mappedEmployeeSendback', $mappedEmployeeSendback);
        $this->set('notMapped', $notMapped);
        
        if(!empty($totalEmployee)){
            if(count($totalEmployee)<100){
                $this->set('icon_weight',10);
            }
            else if(count($totalEmployee)<500){
                $this->set('icon_weight',50);
            }
            else{
                $this->set('icon_weight',100);
            }
        }else{
             $this->set('icon_weight',100);
        }
        
        $notMapped=((100 / count($totalEmployee)) *  count($notMapped));
        $mappedEmployeeWaiting=((100 / count($totalEmployee)) *  count($mappedEmployeeWaiting));
        $mappedEmployeeOpen=((100 / count($totalEmployee)) *  count($mappedEmployeeOpen));
        $this->set('first_bar',((100 / count($totalEmployee)) *  count($totalEmployee)));
        $this->set('second_bar',$this->_roundUpToAny($notMapped));
        $this->set('third_bar',$this->_roundUpToAny($mappedEmployeeWaiting));
        $this->set('fourth_bar',$this->_roundUpToAny($mappedEmployeeOpen));
        
        
        $total_reporties = count($reporties);
        $repotiesIn_project = count($employeesInProject);
        $repotiesIn_project_progress = ((100 / $total_reporties) * $repotiesIn_project);
        $repotiesnotIn_project = ($total_reporties - $repotiesIn_project);
        $repotiesnotIn_project_progress = ((100 / $total_reporties) * $repotiesnotIn_project);
        $this->set('repotiesIn_project_progress', $this->_roundUpToAny($repotiesIn_project_progress));
        $this->set('repotiesnotIn_project_progress', $this->_roundUpToAny($repotiesnotIn_project_progress));
        $this->set('repotiesnotIn_project', $repotiesnotIn_project);
        $this->set('repotiesIn_project', $repotiesIn_project);
        $this->set('total_reporties', $total_reporties);
        $total_mappedEmp = count($mappedEmps);
        $draft = 0;
        $open = 0;
        $submitted = 0;
        $kraInProject = 0;
        $open_progress = 0;
        $draft_progress = 0;
        $submitted_progress = 0;
        foreach ($mappedEmps as $mappedEmp) {
            if (in_array($mappedEmp['EmployeeKraMapping']['employee_id'], array_keys($employeesInProject))) {
                $kraInProject++;
            }
            if ($mappedEmp['EmployeeKraMapping']['status'] == 'o') {
                $open++;
            } else if ($mappedEmp['EmployeeKraMapping']['status'] == 's') {
                $draft++;
            } else if ($mappedEmp['EmployeeKraMapping']['status'] == 'm') {
                $submitted++;
            }
        }
        if ($total_mappedEmp) {
            $open_progress = ((100 / $total_mappedEmp) * $open);
            $draft_progress = ((100 / $total_mappedEmp) * $draft);
            $submitted_progress = ((100 / $total_mappedEmp) * $submitted);
        }
        $pending = $total_reporties - $total_mappedEmp;

        $pending_progress = ((100 / $total_reporties) * $pending);
        $this->set('total_mappedEmp', $total_mappedEmp);
        $this->set('draft', $draft);
        $this->set('open', $open);
        $this->set('open_progress', $this->_roundUpToAny($open_progress));
        $this->set('draft_progress', $this->_roundUpToAny($draft_progress));
        $this->set('submitted_progress', $this->_roundUpToAny($submitted_progress));
        $this->set('submitted', $submitted);
        $this->set('pending', $pending);
        $this->set('pending_progress', $pending_progress);
        $repoties_without_kras_In_project = $repotiesIn_project - $kraInProject;
        $repoties_without_kras_not_in_project = $repotiesnotIn_project - ($total_mappedEmp - $kraInProject);
        $repoties_without_kras_in_project_progress = ((100 / $pending) * $repoties_without_kras_In_project);
        $repoties_without_kras_not_in_project_progress = ((100 / $pending) * $repoties_without_kras_not_in_project);
        $this->set('repoties_without_kras_In_project', $repoties_without_kras_In_project);
        $this->set('repoties_without_kras_not_in_project', $repoties_without_kras_not_in_project);
        $this->set('repoties_without_kras_in_project_progress', $this->_roundUpToAny($repoties_without_kras_in_project_progress));
        $this->set('repoties_without_kras_not_in_project_progress', $this->_roundUpToAny($repoties_without_kras_not_in_project_progress));
        if ($hrView) {
            $menuarray = $this->_getHrMenu();

            $employee_menu_list = '';
            foreach ($menuarray as $menudata) {
                $employee_menu_list[$menudata['HeadStructure']['id']][$menudata['Employee']['id']]['Employee_name'] = $menudata[0]['Employee_name'];
            }
            $this->set('employee_menu_list', $employee_menu_list);
        }
    }

    function _getHrMenu() {
        $employeeDetails = $this->Employee->find('all', array('fields' => array('HeadStructure.*', 'Employee.id',
                'CONCAT(Employee.employee_number," - ",Employee.first_name," ", Employee.last_name) as Employee_name',
            ), 'joins' => array(
                array(
                    'table' => 'head_configurations',
                    'alias' => 'HeadConfiguration',
                    'type' => 'inner',
                    'conditions' => array('HeadConfiguration.employee_id = Employee.id')
                ),
                array(
                    'table' => 'head_structures',
                    'alias' => 'HeadStructure',
                    'type' => 'inner',
                    'conditions' => array('HeadStructure.id = HeadConfiguration.head_structure_id')
                )
            )
            , 'conditions' => array('((Employee.employment_status in ("p","e") or (Employee.employment_status ="v" and Employee.band_id not in(33,34))))'), 'group' => 'Employee.id'));


        return $employeeDetails;
    }

    function _roundUpToAny($n, $x = 5) {
        if ($n) {
            return (round($n) % $x === 0) ? round($n) : round(($n + $x / 2) / $x) * $x;
        } else {
            return $n;
        }
    }

    function reportees_kra() {
        $this->layout = "kra";

        if (empty($emp_id) || ($emp_id == null)) {
            $emp_id = $this->Auth->user('employee_id');
        }
        $employee_kra_details = $this->EmployeeKraMapping->find('all', array('fields' => array('EmployeeKraMapping.*', 'KraTemplate.*', 'Kra.*', 'KraUom.*', 'Employee.*', 'EmployeeManager.*'),
            'joins' => array(
                array(
                    'table' => 'kra_templates',
                    'alias' => 'KraTemplate',
                    'type' => 'LEFT',
                    'conditions' => array('KraTemplate.kra_master_id = EmployeeKraMapping.kra_master_id')
                ),
                array(
                    'table' => 'kras',
                    'alias' => 'Kra',
                    'type' => 'LEFT',
                    'conditions' => array('KraTemplate.kra_id = Kra.id')
                ),
                array(
                    'table' => 'kra_uoms',
                    'alias' => 'KraUom',
                    'type' => 'LEFT',
                    'conditions' => array('KraTemplate.uom_id = KraUom.id')
                ),
                array(
                    'table' => 'employees',
                    'alias' => 'Employee',
                    'type' => 'LEFT',
                    'conditions' => array('Employee.id = EmployeeKraMapping.employee_id')
                ),
                array(
                    'table' => 'employees',
                    'alias' => 'EmployeeManager',
                    'type' => 'LEFT',
                    'conditions' => array('EmployeeManager.id = Employee.manager')
                ),
            ),
            'conditions' => array('EmployeeKraMapping.manager_id' => $emp_id,
                'EmployeeKraMapping.status' => 'm',
                'EmployeeKraMapping.deleted' => 0)));
        foreach ($employee_kra_details as $employee_kra_detail) {

            $employeeList[$employee_kra_detail['Employee']['id'] . '/' . $employee_kra_detail['EmployeeKraMapping']['id']] = $employee_kra_detail['Employee']['employee_number'] . ' - ' . $employee_kra_detail['Employee']['first_name'] . ' ' . $employee_kra_detail['Employee']['last_name'];
            $kraMappingId[$employee_kra_detail['Employee']['id']] = $employee_kra_detail['EmployeeKraMapping']['id'];
        }
        if ($employee_kra_details) {
            $this->set('employeeList', $employeeList);
            $this->set('kraMappingId', $kraMappingId);
        }
        $this->set('emp_kra_details', $employee_kra_details);

        $this->set('auth_user', $this->Auth->user('employee_id'));
        $this->set('emp_id', $emp_id);
    }

    function employee_kra($emp_id = null, $kra_mapping_id, $view = null) {
        
        if ($view == "rm_feedback") {
            $rm_change_request = $this->EmployeeRmChange->find('all', array('conditions' => array('EmployeeRmChange.employee_id' => $emp_id, 'EmployeeRmChange.status' => 'd')));
            if (!empty($rm_change_request)) {
                echo "EXISTS";
                exit;
            }
        }
        $get_kra_frequency = $this->KraFrequency->find('first', array());
        $kra_start_date = date("Y") . "-" . $get_kra_frequency['KraFrequency']['start_month'] . "-01";
        $kra_end_date = date("Y-m-d", strtotime($kra_start_date . " +12 months -1 days"));
        if ($_POST) {
            $this->layout = false;
        }
        $this->layout = false;
        $showBackButton = false;
        $showActionButton = false;
        $error = false;
        $message = 'SUCCESS';
        if ($emp_id == 0 || empty($emp_id) || ($emp_id == null) || $view != null) {
            if ($emp_id) {
                $emp_id = $emp_id;
            } else {
                $emp_id = $this->Auth->user('employee_id');
            }

            $this->layout = "kra";
            $showBackButton = true;
        }
        if($view != null){
            $check_trigger_rm = $this->KraRmTrigger->find('first',array('conditions'=>array('KraRmTrigger.employee_id'=>$emp_id,'KraRmTrigger.triggered_by'=>$this->Auth->user('employee_id'),'KraRmTrigger.deleted'=>0,'KraRmTrigger.status'=>'o')));
            if(!empty($check_trigger_rm)) $showActionButton = true;
        }else{
            $check_trigger_emp = $this->KraRmTrigger->find('first',array('conditions'=>array('KraRmTrigger.employee_id'=>$emp_id,'KraRmTrigger.deleted'=>0,'KraRmTrigger.status'=>'o')));
            if(!empty($check_trigger_emp)) $showActionButton = true;
        }
        $this->set('showActionButton',$showActionButton);

        if ($_POST) {
            $this->layout = false;
        }
        $this->set('showBackButton', $showBackButton);


        $kra_details = $this->EmployeeKraMapping->find('all', array(
            'joins' => array(
                array(
                    'table' => 'employee_kra_details',
                    'alias' => 'EmployeeKraDetail',
                    'type' => 'INNER',
                    'conditions' => array('EmployeeKraDetail.employee_kra_mapping_id = EmployeeKraMapping.id')
                ),
            ),
            'conditions' => array('EmployeeKraMapping.id' => $kra_mapping_id)));
        if (!empty($kra_details))
            $condition = 'EmployeeKraDetail.kra_template_id  = KraTemplate.id';
        else
            $condition = '';

        $employee_kra_details = $this->EmployeeKraMapping->find('all', array('fields' => array('EmployeeKraMapping.*', 'EmployeeKraMapping.*', 'EmployeeKraDetail.*', 'KraTemplate.*', 'Kra.*', 'KraUom.*', 'Employee.*', 'EmployeeManager.*'),
            'joins' => array(
                array(
                    'table' => 'kra_masters',
                    'alias' => 'KraMaster',
                    'type' => 'INNER',
                    'conditions' => array('KraMaster.id = EmployeeKraMapping.kra_master_id')
                ),
                array(
                    'table' => 'kra_templates',
                    'alias' => 'KraTemplate',
                    'type' => 'LEFT',
                    'conditions' => array('KraTemplate.kra_master_id = KraMaster.id')
                ),
                array(
                    'table' => 'employee_kra_details',
                    'alias' => 'EmployeeKraDetail',
                    'type' => 'LEFT',
                    'conditions' => array('EmployeeKraDetail.employee_kra_mapping_id = EmployeeKraMapping.id')
                ),
                array(
                    'table' => 'kras',
                    'alias' => 'Kra',
                    'type' => 'LEFT',
                    'conditions' => array('KraTemplate.kra_id = Kra.id')
                ),
                array(
                    'table' => 'kra_uoms',
                    'alias' => 'KraUom',
                    'type' => 'LEFT',
                    'conditions' => array('KraTemplate.uom_id = KraUom.id')
                ),
                array(
                    'table' => 'employees',
                    'alias' => 'Employee',
                    'type' => 'LEFT',
                    'conditions' => array('Employee.id = EmployeeKraMapping.employee_id')
                ),
                array(
                    'table' => 'employees',
                    'alias' => 'EmployeeManager',
                    'type' => 'LEFT',
                    'conditions' => array('EmployeeManager.id = Employee.manager')
                ),
            ),
            'conditions' => array(
                'EmployeeKraMapping.id' => $kra_mapping_id,
                'EmployeeKraMapping.deleted' => 0,
                $condition),
        ));

        if (!$this->RequestHandler->isAjax()) {
            $employee_detail = $this->Employee->find('all', array('fields' => array('CONCAT(Manager.employee_number," - ",Manager.first_name," ", Manager.last_name) as manager_name',
                    'CONCAT(Employee.employee_number," - ",Employee.first_name," ", Employee.last_name) as employee_name',
                    'Designation.*', 'Employee.*', 'Band.*', 'CompanyStructure.*', 'CompanyStructureGroup.*', 'Country.*'),
                'conditions' => array('Employee.id' => $emp_id)));
            $last_promotion_date =  '-';
            $work_experience = $this->get_employee_work_experience($emp_id);
            $last_rating = $this->get_last_rating($emp_id);
            $fields_changed = array('Designation', 'Sub-Band', 'Band');
            $emp_last_promotion = $this->EmployeeInfoHistory->find('first', array('fields' => array('EmployeeInfoHistory.*'), 'conditions' => array('EmployeeInfoHistory.employee_id' => $emp_id, 'EmployeeInfoHistory.field_changed' => $fields_changed), 'order' => array('EmployeeInfoHistory.id DESC')));
            if ($emp_last_promotion['EmployeeInfoHistory']['field_changed'] == "Designation") {
                $last_promotion = $this->Designation->field('Designation.designation', array('Designation.id =' => $emp_last_promotion['EmployeeInfoHistory']['changed_from']));
            }
            if (!empty($emp_last_promotion)) {
                $last_promotion_date = date('d-m-Y', $emp_last_promotion['EmployeeInfoHistory']['modified_time']);
            }
            $this->set('last_promotion', $last_promotion_date);
            $this->set('last_rating', $last_rating);
            $this->set('work_experience', $work_experience);
            $this->set('billable_status', $this->generateBillableEmployeeList());
            $this->set('employee_detail', $employee_detail);
            $practice = $sub_practice = '';
            if ($employee_detail[0]['CompanyStructure']['parent_id'] == Configure::read('CompanyStructure.SBU_ID')) {
                $practice = "Business Unit";
                $sub_practice = "Practice";
            } else if ($employee_detail[0]['CompanyStructure']['parent_id'] == Configure::read('CompanyStructure.SSU_ID')) {
                $practice = "Enabling Function";
                $sub_practice = "Sub Function";
            } else if ($employee_detail[0]['CompanyStructure']['parent_id'] == Configure::read('CompanyStructure.SALES')) {
                $practice = "Sales";
                $sub_practice = "Geography/Account";
            }
            $this->set('practice', $practice);
            $this->set('sub_practice', $sub_practice);
        }
        $target_visibility = true;
        $kra_target = $this->KraTemplate->find('all', array('conditions' => array('KraTemplate.kra_master_id' => $employee_kra_details[0]['EmployeeKraMapping']['kra_master_id'], 'KraTemplate.uom_id != ""')));
        if (empty($kra_target)) {
            $target_visibility = false;
        }
        $this->set('emp_kra_details', $employee_kra_details);
        $this->set('emp_id', $emp_id);
        $this->set('view', $view);
        $this->set('target_visibility', $target_visibility);
        $this->set('auth_user', $this->Auth->user('employee_id'));
        for ($rating_count = 1; $rating_count <= KraMaster::RATING_MAX_LIMIT; $rating_count++) {
            $ratings[$rating_count] = $rating_count;
        }
        $this->set('ratings', $ratings);
        $pm_reviews = $this->EmployeeKraMapping->find('all', array('fields' => array('KraPmMaster.*', 'KraPmReview.*',
                'EmployeeKraMapping.year', 'EmployeeKraMapping.status', 'Band.name', 'Date_Format(ProjectTeamAllocation.end_date,"%d-%m-%Y") as end_date',
                'concat("01","-",KraFrequency.start_month,"-",EmployeeKraMapping.year) as from_date',
                'concat(Project.project_code,"-",Project.project_name) as project_name',
                'concat(Employee.employee_number,"-",Employee.first_name," ",Employee.last_name) as employee_name',
                'CONCAT(ProjectManager.employee_number," - ",ProjectManager.first_name," ", ProjectManager.last_name) as project_manager'),
            'joins' => array(
                array(
                    'table' => 'kra_pm_masters',
                    'alias' => 'KraPmMaster',
                    'type' => 'INNER',
                    'conditions' => array('KraPmMaster.employee_kra_mapping_id = EmployeeKraMapping.id')
                ),
                array(
                    'table' => 'kra_masters',
                    'alias' => 'KraMaster',
                    'type' => 'INNER',
                    'conditions' => array('KraMaster.id = EmployeeKraMapping.kra_master_id')
                ),
                array(
                    'table' => 'kra_pm_reviews',
                    'alias' => 'KraPmReview',
                    'type' => 'INNER',
                    'conditions' => array('KraPmReview.Kra_pm_master_id = KraPmMaster.id')
                ),
                array(
                    'table' => 'projects',
                    'alias' => 'Project',
                    'type' => 'inner',
                    'conditions' => array('Project.id = KraPmMaster.project_id')
                ),
                array(
                    'table' => 'employees',
                    'alias' => 'Employee',
                    'type' => 'LEFT',
                    'conditions' => array('Employee.id = EmployeeKraMapping.employee_id')
                ),
                array(
                    'table' => 'employees',
                    'alias' => 'ProjectManager',
                    'type' => 'LEFT',
                    'conditions' => array('ProjectManager.id = KraPmMaster.pm_id')
                ),
                array(
                    'table' => 'bands',
                    'alias' => 'Band',
                    'type' => 'LEFT',
                    'conditions' => array('EmployeeKraMapping.band_id = Band.id')
                ),
                array(
                    'table' => 'project_team_allocations',
                    'alias' => 'ProjectTeamAllocation',
                    'type' => 'LEFT',
                    'conditions' => array('ProjectTeamAllocation.id = KraPmMaster.pta_id')
                ),
                array(
                    'table' => 'kra_frequencies',
                    'alias' => 'KraFrequency',
                    'type' => 'LEFT',
                    'conditions' => array('KraFrequency.id = KraMaster.frequency_id')
                ),
            ),
            'conditions' => array('EmployeeKraMapping.id' => $kra_mapping_id),
            'group' => array('KraPmMaster.id'))
        );
        if ($pm_reviews) {
            $this->set('pm_reviews', $pm_reviews);
        }

        $rm_reviews = $this->EmployeeKraMapping->find('all', array('fields' => array('EmployeeKraMapping.*', 'CONCAT(ReportingManager.employee_number," - ",ReportingManager.first_name," ", ReportingManager.last_name) as reporting_manager'),
            'joins' => array(
                array(
                    'table' => 'employees',
                    'alias' => 'ReportingManager',
                    'type' => 'LEFT',
                    'conditions' => array('ReportingManager.id = EmployeeKraMapping.manager_id')
                ),
            ),
            'conditions' => array('EmployeeKraMapping.employee_id' => $emp_id, 'EmployeeKraMapping.to_date IS Not NULL', 'EmployeeKraMapping.deleted' => 0)
                )
        );
        if ($rm_reviews) {
            $this->set('rm_reviews', $rm_reviews);
        }

        // ----  Set data Ends -- //
        $learning_and_development = $this->EmployeeKraMapping->find('all', array('fields' => array('RequestEvaluvation.*'),
            'joins' => array(
                array(
                    'table' => 'request_evaluvations',
                    'alias' => 'RequestEvaluvation',
                    'type' => 'INNER',
                    'conditions' => array('RequestEvaluvation.employee_kra_mapping_id = EmployeeKraMapping.id')
                ),
            ),
            'conditions' => array(
                'EmployeeKraMapping.id' => $kra_mapping_id,
                'EmployeeKraMapping.deleted' => 0,
            ),
        ));

        $this->set('learning_and_development', $learning_and_development);

        $employee_attachements = $this->EmployeeKraMapping->find('all', array('fields' => array('KraUpload.*'),
            'joins' => array(
                array(
                    'table' => 'kra_uploads',
                    'alias' => 'KraUpload',
                    'type' => 'INNER',
                    'conditions' => array('KraUpload.employee_kra_mapping_id = EmployeeKraMapping.id')
                ),
            ),
            'conditions' => array(
                'EmployeeKraMapping.id' => $kra_mapping_id,
                'EmployeeKraMapping.deleted' => 0,
                'KraUpload.deleted' => 0
            ),
        ));
        $this->set('employee_attachements', $employee_attachements);


        $learning_and_development_text = $this->ConfigurationValue->find('list', array('fields' => array('Configuration_inself.configuration_key', 'Configuration_inself.configuration_value'),
            'joins' => array(array(
                    'table' => 'configuration_values',
                    'alias' => 'Configuration_inself',
                    'type' => 'INNER',
                    'conditions' => array('Configuration_inself.parent_id = ConfigurationValue.id')
                )),
            'conditions' => array('ConfigurationValue.configuration_key' => 'request_evaluvations'
            ))
        );
        $this->set('learning_and_development_text', $learning_and_development_text);

        $client_feedbacks = $this->KraClientFeedback->find('all', array('fields' => array('KraClientFeedback.*', 'Project.*', 'Customer.*'),
            'joins' => array(
                array(
                    'table' => 'projects',
                    'alias' => 'Project',
                    'type' => 'LEFT',
                    'conditions' => array('Project.id = KraClientFeedback.project_id')
                ),
                array(
                    'table' => 'customers',
                    'alias' => 'Customer',
                    'type' => 'LEFT',
                    'conditions' => array('Customer.id = KraClientFeedback.customer_id')
                ),
            ),
            'conditions' => array(
                'KraClientFeedback.employee_id' => $emp_id,
                'KraClientFeedback.status' => 'm',
                'DATE(KraClientFeedback.submitted_on) BETWEEN "' . $kra_start_date . '" AND "' . $kra_end_date . '"',
            ),
        ));

        if ($client_feedbacks) {
            $this->set('client_feedbacks', $client_feedbacks);
        }
        if (!empty($_POST['data']['EmployeeKraDetail'])) {
            $this->data = $_POST['data'];
            $emp_submit = $manager_submit = false;
            $reviewed_on = $mail_to = $mail_cc = '';
            $kra_mapping_id = $employee_kra_details[0]['EmployeeKraMapping']['id'];
            if ($this->data['EmployeeKraDetail']['action'] == "submit") {
                if ($employee_kra_details[0]['EmployeeKraMapping']['status'] == 'o' || $employee_kra_details[0]['EmployeeKraMapping']['status'] == 's' || $employee_kra_details[0]['EmployeeKraMapping']['status'] == 'r') {
                    $kra_mapping_status = 'm';
                    $submitted_on = date('Y-m-d H:i:s');
                    $emp_submit = true;
                } else if ($employee_kra_details[0]['EmployeeKraMapping']['status'] == 'm') {
                    $reviewed_on = date('Y-m-d H:i:s');
                    $submitted_on = $employee_kra_details[0]['EmployeeKraMapping']['submitted_date'];
                    $kra_mapping_status = 'a';
                    $manager_submit = true;
                }
            } else if ($this->data['EmployeeKraDetail']['action'] == "save") {
                $kra_mapping_status = 's';
                $submitted_on = date('Y-m-d H:i:s');
                $emp_submit = true;
            } else if ($this->data['EmployeeKraDetail']['action'] == "send_back") {
                $kra_mapping_status = 'r';
                $submitted_on = $employee_kra_details[0]['EmployeeKraMapping']['submitted_date'];
                $reviewed_on = date('Y-m-d H:i:s');
            }

            foreach ($this->data['EmployeeKraDetail']['employee_kra_mapping_id'] as $key => $val) {
                if (!empty($this->data['EmployeeKraDetail']['id'][$key])) {
                    $employee_kra_data ['id'] = $this->data['EmployeeKraDetail']['id'][$key];
                }
                $employee_kra_data ['employee_id'] = $emp_id;
                $employee_kra_data ['employee_kra_mapping_id'] = $this->data['EmployeeKraDetail']['employee_kra_mapping_id'][$key];
                $employee_kra_data ['kra_template_id'] = $this->data['EmployeeKraDetail']['kra_template_id'][$key];
//                $employee_kra_data ['achievedvsTarget'] = $this->data['EmployeeKraDetail']['achievedvsTarget'][$key];
                if ($emp_submit) {
                    $employee_kra_data ['self_rating'] = (isset($this->data['EmployeeKraDetail']['self_rating'][$key]) || !empty($this->data['EmployeeKraDetail']['self_rating'][$key])) ? $this->data['EmployeeKraDetail']['self_rating'][$key] : '';
                    $employee_kra_data ['self_comments'] = (isset($this->data['EmployeeKraDetail']['self_comments'][$key]) || !empty($this->data['EmployeeKraDetail']['self_comments'][$key])) ? trim($this->data['EmployeeKraDetail']['self_comments'][$key]) : '';
                    $employee_kra_data ['target'] = (isset($this->data['EmployeeKraDetail']['target'][$key]) || !empty($this->data['EmployeeKraDetail']['target'][$key])) ? trim($this->data['EmployeeKraDetail']['target'][$key]) : '';
                    $employee_kra_data ['achievedvsTarget'] = (isset($this->data['EmployeeKraDetail']['achievedvsTarget'][$key]) || !empty($this->data['EmployeeKraDetail']['achievedvsTarget'][$key])) ? trim($this->data['EmployeeKraDetail']['achievedvsTarget'][$key]) : '';
                    $subject = 'Appraisal Self-Assessment Completion';
                    $content = 'your Appraisal Self-Assessment. It is now with your Reporting Manager for his review and assessment';
                }
                if ($manager_submit) {
                    $employee_kra_data ['manager_rating'] = (isset($this->data['EmployeeKraDetail']['manager_rating'][$key]) || !empty($this->data['EmployeeKraDetail']['manager_rating'][$key])) ? $this->data['EmployeeKraDetail']['manager_rating'][$key] : '';
                    $employee_kra_data ['manager_comments'] = (isset($this->data['EmployeeKraDetail']['manager_comments'][$key]) || !empty($this->data['EmployeeKraDetail']['manager_comments'][$key])) ? trim($this->data['EmployeeKraDetail']['manager_comments'][$key]) : '';
                    
                    $subject = 'Appraisal RM Assessment Completion';
                    $content = 'Appraisal Review and Assessment of your Reporting Manager';
                }

                //save kra details table
                $this->EmployeeKraDetail->create();
                if (!$this->EmployeeKraDetail->save($employee_kra_data)) {
                    $error = true;
                }
            }
            $employee_kra_mapping = array(
                'id' => $kra_mapping_id,
                'status' => $kra_mapping_status,
                'submitted_date' => $submitted_on,
                'overall_rating' => $this->data['EmployeeKraMapping']['overall_rating'],
                'requesting_evaluations' => trim($this->data['EmployeeKraMapping']['requesting_evaluations']),
                'reviewed_date' => $reviewed_on,
                'deleted' => 0
            );
            if (!$this->EmployeeKraMapping->save($employee_kra_mapping)) {
                $error = true;
            }
            //save employee kra mapping

            $learning_developments = $_POST['requesting_evaluations'];
            $evaluations = array();
            foreach ($learning_developments as $learning_development) {
                $evaluations['id'] = (isset($learning_development['id']) && !empty($learning_development['id'])) ? $learning_development['id'] : '';
                $evaluations['employee_kra_mapping_id'] = $kra_mapping_id;
                $evaluations['title'] = $learning_development['title'];
                $evaluations['comments'] = $learning_development['comments'];
                $evaluations['evaluvations_type'] = $learning_development['evaluvations_type'];
                $this->RequestEvaluvation->save($evaluations);
            }
            $employee_attachements = $_POST['attachement'];
            $attachements = array();
            foreach ($employee_attachements as $id=>$employee_attachement) {
                if(empty($employee_attachement['id'])){
                    $attachements['id'] = '';
                    $attachements['employee_kra_mapping_id'] = $kra_mapping_id;
                    $attachements['attachement_name'] = $employee_attachement['attachement_name'];
                    $attachements['uploded_on'] = date('Y-m-d H:i:s');
                    $attachements['deleted'] = 0;
                    $this->KraUpload->save($attachements);
                }
            }
            //auto approvals 
            if($manager_submit && !$showActionButton){
                $get_kra_frequency = $this->KraFrequency->find('first', array());
                if (date('m') < $get_kra_frequency['KraFrequency']['start_month']) {
                    $kra_year = date('Y');
                } else {
                    $kra_year = date("Y",strtotime("+1 year"));
                }
            $check_normalization=$this->Employee->find('all',array('fields'=>array( 
                'HeadConfigurationNormalizer.employee_id','HeadConfigurationReviewer.employee_id'
            ),
            'joins' => array(
                array(
                    'table' => 'head_configurations',
                    'alias' => 'HeadConfigurationNormalizer',
                    'type' => 'LEFT',
                    'conditions' => array('HeadConfigurationNormalizer.employee_id = Employee.id','HeadConfigurationNormalizer.head_structure_id in (1,2)')
                ),
                array(
                    'table' => 'head_configurations',
                    'alias' => 'HeadConfigurationReviewer',
                    'type' => 'LEFT',
                    'conditions' => array('HeadConfigurationReviewer.employee_id = Employee.id','HeadConfigurationReviewer.head_structure_id in (3,4)')
                )
                ),
            'conditions'=>array('Employee.id'=> $this->Auth->user('employee_id'))));
                if(!empty($check_normalization)){
                    $reviewer_id=!empty($check_normalization);
                    if(!empty($check_normalization[0]['HeadConfigurationNormalizer']['employee_id'])){
                        $reviewer_id=$check_normalization[0]['HeadConfigurationNormalizer']['employee_id'];
                        $normalizer_id=$check_normalization[0]['HeadConfigurationNormalizer']['employee_id'];
                    }
                    else{
                        $reviewer_id=$check_normalization[0]['HeadConfigurationReviewer']['employee_id'];
                        $normailzer_name=$this->Employee->find('all',array('fields'=>array('Normalizer.id'),
                                'joins' => array(
                                    array(
                                        'table' => 'head_configurations',
                                        'alias' => 'HeadConfiguration',
                                        'type' => 'INNER',
                                        'conditions' => array('Employee.structure_name_subgroup=HeadConfiguration.sub_sbu','HeadConfiguration.head_structure_id in (1,2)')
                                    ),
                                    array(
                                        'table' => 'employees',
                                        'alias' => 'Normalizer',
                                        'type' => 'INNER',
                                        'conditions' => array('Normalizer.id=HeadConfiguration.employee_id')
                                    )),
                                'conditions'=>array('Normalizer.designation_id !='.Configure::read('CEO.designation_id'),'Employee.id'=>$this->Auth->user("employee_id"))));
                        $normalizer_id=!empty($normailzer_name)?$normailzer_name[0]['Normalizer']['id']: $this->Auth->user('employee_id');
                    }
                    $kra_details =  $this->EmployeeKraMapping->find('all', array(
                                    'conditions' => array('EmployeeKraMapping.employee_id' => $emp_id, 'EmployeeKraMapping.status' => 'a','EmployeeKraMapping.year'=>$kra_year),
                                    'order' => 'EmployeeKraMapping.id'));
                    if (!empty($kra_details)) {
                        $manager_id=array();
                        if (sizeof($kra_details) > 1) {
                            foreach ($kra_details as $key => $kra_data) {
                                $kra_from_date = $kra_data['EmployeeKraMapping']['year'] . "-" . $get_kra_frequency['KraFrequency']['start_month'] . "-01";
                                $kra_last_date = date("Y-m-d", strtotime($kra_from_date . " +12 months -1 days"));
                                $dates_in_range = $this->Common->date_range($kra_from_date, $kra_last_date);
                                $kra_freq = count($dates_in_range);
                                $kra_duration = $this->Common->date_range($kra_data['EmployeeKraMapping']['from_date'], $kra_data['EmployeeKraMapping']['to_date']);
                                $kra_days = count($kra_duration);
                                $rate += ($kra_data['EmployeeKraMapping']['overall_rating'] / $kra_freq) * $kra_days;
                                $kra_mapping_id = $kra_data['EmployeeKraMapping']['id'];
                                $manager_id[] = $kra_data['EmployeeKraMapping']['manager_id'];
                            }
                            $overall_rating = round($rate);
                        } else {
                            $kra_mapping_id = $kra_details[0]['EmployeeKraMapping']['id'];
                            $overall_rating = $kra_details[0]['EmployeeKraMapping']['overall_rating'];
                            $manager_id[] =  $kra_details[0]['EmployeeKraMapping']['manager_id'];
                        }
                        $manager_id = implode(',', $manager_id);
                       
                        $this->data['EmployeeKraNormalization']['employee_kra_mapping_id'] = $kra_mapping_id;
                        $this->data['EmployeeKraNormalization']['manager_ids'] = $manager_id;
                        $this->data['EmployeeKraNormalization']['employee_id'] = $emp_id;
                        $this->data['EmployeeKraNormalization']['normalized_rating'] = $overall_rating;
                        $this->data['EmployeeKraNormalization']['year'] = $kra_year;
                        $this->data['EmployeeKraNormalization']['reviewer_id'] = $reviewer_id;
                        $this->data['EmployeeKraNormalization']['reviewer_rating'] =  $this->data['EmployeeKraMapping']['overall_rating'];
                        $this->data['EmployeeKraNormalization']['reviewer_comment'] =  'Auto Approval';
                        $this->data['EmployeeKraNormalization']['reviewed_on'] = date('Y-m-d H:i:s');
                        $this->data['EmployeeKraNormalization']['normalizer_id'] = $normalizer_id;
                        if($reviewer_id==$normalizer_id){
                            $this->data['EmployeeKraNormalization']['normalizer_rating'] =  $this->data['EmployeeKraMapping']['overall_rating'];
                            $this->data['EmployeeKraNormalization']['normalizer_comment'] =  'Auto Approval';
                            $this->data['EmployeeKraNormalization']['normalized_on'] = date('Y-m-d H:i:s');
                            $this->EmployeeKraMapping->updateAll(array('EmployeeKraMapping.status' => "'n'"), array('EmployeeKraMapping.employee_id' => $emp_id,'EmployeeKraMapping.year'=>$kra_year));
                        }else{
                            $this->EmployeeKraMapping->updateAll(array('EmployeeKraMapping.status' => "'h'"), array('EmployeeKraMapping.employee_id' => $emp_id,'EmployeeKraMapping.year'=>$kra_year));
                        }
                        
                        $this->data['EmployeeKraNormalization']['status'] = ($reviewer_id==$normalizer_id)?'n':'h';
                        $this->data['EmployeeKraNormalization']['deleted'] = 0;
                        $this->EmployeeKraNormalization->create();
                        $this->EmployeeKraNormalization->save($this->data['EmployeeKraNormalization']);
                    }
                }
                
            }

            if ($error) {
                $message = "FAILED";
            } else{
                //
                if($kra_mapping_status != 's'){
                    Configure::load('messages');
                    $subjectBody = Configure::read('KRA_SUBMIT_NOTIFICATION');
                    $mail_cc = $this->Employee->field('Employee.work_email_address', array('Employee.id =' => $employee_kra_details[0]['EmployeeKraMapping']['manager_id']));
                    $hr_dl = $this->_getHrMailList();
                    array_push($hr_dl,$mail_cc);
                    if($manager_submit){
                        $emp_sub_sbu  = $this->Employee->field('Employee.structure_name_subgroup', array('Employee.id =' => $emp_id));
                        $practise_head_id = $this->HeadConfiguration->field('HeadConfiguration.employee_id', array('HeadConfiguration.sub_sbu =' => $emp_sub_sbu,'HeadConfiguration.head_structure_id =' => 2));
                        $practice_head_mail = $this->Employee->field('Employee.work_email_address', array('Employee.id =' =>$practise_head_id));
                         if(!empty($practise_head_id)){
                             array_push($hr_dl,$practice_head_mail);
                         }
                        
                    }
                    $cc_list = array();//$mail_cc
                    $employee_number = $this->Employee->field('Employee.employee_number', array('Employee.id =' => $emp_id));
                    $employeeName = $this->Employee->field('CONCAT(Employee.first_name," ",Employee.last_name)', array('Employee.id =' =>  $emp_id));
                    $this->Email->to = $this->Employee->field('Employee.work_email_address', array('Employee.id =' => $emp_id));        
                    $this->Email->html_body = sprintf($subjectBody['body'],$employeeName,$content);
                    $this->Email->subject = sprintf($subjectBody['subject'],$subject);
                    $this->Email->cc = $hr_dl;
                    $result = $this->Email->send();
                    $this->log($hr_dl);
                    $this->log($emp_id);
                }
                
            }
            echo json_encode($message);
            Configure::write('debug', 0);
            $this->autoRender = false;
            exit();
        }
    }

//    function get_kra_status_list() {
//        $kra_status = $this->ConfigurationValues->find('all', array('fields' => array('ConfigurationValues.configuration_key', 'ConfigurationValues.configuration_value'), 'joins' => array(
//                array(
//                    'table' => 'configuration_values',
//                    'alias' => 'a',
//                    'type' => 'INNER',
//                    'foreignKey' => false,
//                    'conditions' => array('ConfigurationValues.parent_id = a.id')
//                )
//            ),
//            'conditions' => array('a.configuration_key = "kra_status"')
//        ));
//        foreach ($kra_status as $key => $value) {
//            $kra_status_list[$value['ConfigurationValues']['configuration_key']] = $value['ConfigurationValues']['configuration_value'];
//        }
//        return $kra_status_list;
//    }

    function employee_index($emp_id = '') {
        if (empty($emp_id) || ($emp_id == null)) {
            $emp_id = $this->Auth->user('employee_id');
        }
        $this->layout = "kra";
        $status_class = array('o' => 'badge badge-danger ', 'm' => 'badge bg-warning text-white', 'h' => 'badge badge-secondary white-space-normal', 'r' => 'badge badge-warning text-white', 's' => 'badge badge-info', 'c' => 'badge badge-danger ', 'a' => 'badge badge-success text-white ');
        $this->set('status_class', $status_class);
        $edit_status = array('o', 's', 'r');
        $this->set('edit_status', $edit_status);
        $this->set('billable_status', $this->generateBillableEmployeeList());
        $last_promotion_date =  '-';
        $employee_detail = $this->Employee->find('all', array('fields' => array('CONCAT(Manager.employee_number," - ",Manager.first_name," ", Manager.last_name) as manager_name',
                'CONCAT(Employee.employee_number," - ",Employee.first_name," ", Employee.last_name) as employee_name',
                'Designation.*', 'Employee.*', 'Band.*', 'CompanyStructure.*', 'CompanyStructureGroup.*', 'Country.*','Manager.*'),
            'conditions' => array('Employee.id' => $emp_id)));
        $work_experience = $this->get_employee_work_experience($emp_id);
        $last_rating = $this->get_last_rating($emp_id);
        $fields_changed = array('Designation', 'Sub-Band', 'Band');
        $emp_last_promotion = $this->EmployeeInfoHistory->find('first', array('fields' => array('EmployeeInfoHistory.*'), 'conditions' => array('EmployeeInfoHistory.employee_id' => $emp_id, 'EmployeeInfoHistory.field_changed' => $fields_changed), 'order' => array('EmployeeInfoHistory.id DESC')));
        if ($emp_last_promotion['EmployeeInfoHistory']['field_changed'] == "Designation") {
            $last_promotion = $this->Designation->field('Designation.designation', array('Designation.id =' => $emp_last_promotion['EmployeeInfoHistory']['changed_from']));
        }
        if (!empty($emp_last_promotion)) {
            $last_promotion_date = date('d-m-Y', $emp_last_promotion['EmployeeInfoHistory']['modified_time']);
        }
        $this->set('last_promotion', $last_promotion_date);
        $this->set('last_rating', $last_rating);
        $this->set('work_experience', $work_experience);
        $this->set('employee_detail', $employee_detail);
        $practice = $sub_practice = '';
        if ($employee_detail[0]['CompanyStructure']['parent_id'] == Configure::read('CompanyStructure.SBU_ID')) {
            $practice = "Business Unit";
            $sub_practice = "Practice";
        } else if ($employee_detail[0]['CompanyStructure']['parent_id'] == Configure::read('CompanyStructure.SSU_ID')) {
            $practice = "Enabling Function";
            $sub_practice = "Sub Function";
        } else if ($employee_detail[0]['CompanyStructure']['parent_id'] == Configure::read('CompanyStructure.SALES')) {
            $practice = "Sales";
            $sub_practice = "Geography/Account";
        }
        $this->set('practice', $practice);
        $this->set('sub_practice', $sub_practice);
        $employee_kra_details = $this->EmployeeKraMapping->find('all', array('fields' => array('EmployeeKraMapping.*', 'Band.name', 'CompanyStructure.name', 'ConfigurationValue.configuration_value',
                'CONCAT(EmployeeManager.employee_number," - ",EmployeeManager.first_name," ", EmployeeManager.last_name) as manager',
                'CONCAT(ProjectManager.employee_number," - ",ProjectManager.first_name," ", ProjectManager.last_name) as projectmanager'),
            'joins' => array(
                array(
                    'table' => 'bands',
                    'alias' => 'Band',
                    'type' => 'LEFT',
                    'conditions' => array('EmployeeKraMapping.band_id = Band.id')
                ),
                array(
                    'table' => 'company_structures',
                    'alias' => 'CompanyStructure',
                    'type' => 'LEFT',
                    'conditions' => array('CompanyStructure.id = EmployeeKraMapping.sub_sbu_id')
                ),
                array(
                    'table' => 'employees',
                    'alias' => 'EmployeeManager',
                    'type' => 'LEFT',
                    'conditions' => array('EmployeeManager.id = EmployeeKraMapping.manager_id')
                ),
                array(
                    'table' => 'employees',
                    'alias' => 'ProjectManager',
                    'type' => 'LEFT',
                    'conditions' => array('ProjectManager.id = EmployeeKraMapping.pm_id')
                ),
                array(
                    'table' => 'configuration_values',
                    'alias' => 'ConfigurationValueParent',
                    'type' => 'LEFT',
                    'conditions' => array('ConfigurationValueParent.configuration_value="kra_status"')
                ),
                array(
                    'table' => 'configuration_values',
                    'alias' => 'ConfigurationValue',
                    'type' => 'LEFT',
                    'conditions' => array('ConfigurationValue.configuration_key = EmployeeKraMapping.status',
                        'ConfigurationValue.parent_id=ConfigurationValueParent.id')
                )
            ),
            'conditions' => array('EmployeeKraMapping.employee_id' => $emp_id,
                'EmployeeKraMapping.deleted' => 0),
            'order'=>'EmployeeKraMapping.id DESC',
            'limit'=>1));
        $this->set('employee_kra_details', $employee_kra_details);
    }

    function employee_view($emp_id = '', $kra_mapping_id = '') {
        if ($emp_id == 0 || empty($emp_id) || ($emp_id == null)) {
            $emp_id = $this->Auth->user('employee_id');
            $this->set('emp_view', true);
        }
         if ($this->RequestHandler->isAjax()) {
             $this->set('hideBackButton',true);
         }
        if ($_POST['status'] == 'a') {
            $this->layout = false;
            $this->set('showBackButton', false);
        } else {
            $this->set('showBackButton', true);
            $this->layout = "kra";
        }
        if (!empty($_POST['request_id'])) {
            $emp_id = $this->EmployeeRmChange->field('EmployeeRmChange.employee_id', array('EmployeeRmChange.id =' => $_POST['request_id']));

            $mapping_details = $this->EmployeeKraMapping->find('first', array('fields' => array('EmployeeKraMapping.id', 'EmployeeKraMapping.employee_id'), 'conditions' => array('EmployeeKraMapping.employee_id' => $emp_id)));
            $kra_mapping_id = $mapping_details['EmployeeKraMapping']['id'];
            $this->set('emp_view', false);
        }
        $kra_details = $this->EmployeeKraMapping->find('all', array(
            'joins' => array(
                array(
                    'table' => 'employee_kra_details',
                    'alias' => 'EmployeeKraDetail',
                    'type' => 'INNER',
                    'conditions' => array('EmployeeKraDetail.employee_kra_mapping_id = EmployeeKraMapping.id')
                ),
            ),
            'conditions' => array('EmployeeKraMapping.id' => $kra_mapping_id)));
        if (!empty($kra_details))
            $condition = 'EmployeeKraDetail.kra_template_id  = KraTemplate.id';
        else
            $condition = '';

        $learning_and_development_text = $this->ConfigurationValue->find('list', array('fields' => array('Configuration_inself.configuration_key', 'Configuration_inself.configuration_value'),
            'joins' => array(array(
                    'table' => 'configuration_values',
                    'alias' => 'Configuration_inself',
                    'type' => 'INNER',
                    'conditions' => array('Configuration_inself.parent_id = ConfigurationValue.id')
                )),
            'conditions' => array('ConfigurationValue.configuration_key' => 'request_evaluvations'
            ))
        );
        $this->set('learning_and_development_text', $learning_and_development_text);
        $employee_kra_details = $this->EmployeeKraMapping->find('all', array('fields' => array('EmployeeKraMapping.*', 'EmployeeKraMapping.*', 'EmployeeKraDetail.*', 'KraTemplate.*', 'Kra.*', 'KraUom.*', 'Employee.*', 'EmployeeManager.*'),
            'joins' => array(
                array(
                    'table' => 'kra_masters',
                    'alias' => 'KraMaster',
                    'type' => 'INNER',
                    'conditions' => array('KraMaster.id = EmployeeKraMapping.kra_master_id')
                ),
                array(
                    'table' => 'kra_templates',
                    'alias' => 'KraTemplate',
                    'type' => 'LEFT',
                    'conditions' => array('KraTemplate.kra_master_id = KraMaster.id')
                ),
                array(
                    'table' => 'employee_kra_details',
                    'alias' => 'EmployeeKraDetail',
                    'type' => 'LEFT',
                    'conditions' => array('EmployeeKraDetail.employee_kra_mapping_id = EmployeeKraMapping.id')
                ),
                array(
                    'table' => 'kras',
                    'alias' => 'Kra',
                    'type' => 'LEFT',
                    'conditions' => array('KraTemplate.kra_id = Kra.id')
                ),
                array(
                    'table' => 'kra_uoms',
                    'alias' => 'KraUom',
                    'type' => 'LEFT',
                    'conditions' => array('KraTemplate.uom_id = KraUom.id')
                ),
                array(
                    'table' => 'employees',
                    'alias' => 'Employee',
                    'type' => 'LEFT',
                    'conditions' => array('Employee.id = EmployeeKraMapping.employee_id')
                ),
                array(
                    'table' => 'employees',
                    'alias' => 'EmployeeManager',
                    'type' => 'LEFT',
                    'conditions' => array('EmployeeManager.id = Employee.manager')
                )
            ),
            'conditions' => array(
                'EmployeeKraMapping.id' => $kra_mapping_id,
                'EmployeeKraMapping.deleted' => 0,
                $condition),
        ));
//        $employee_review_data = $this->EmployeeKraMapping->find('all', array('fields' => array('EmployeeReview.*', 'KraFeedback.*'),
//            'joins' => array(
//                array(
//                    'table' => 'employee_reviews',
//                    'alias' => 'EmployeeReview',
//                    'type' => 'LEFT',
//                    'conditions' => array('EmployeeReview.employee_kra_mapping_id = EmployeeKraMapping.id')
//                ),
//                array(
//                    'table' => 'kra_feedbacks',
//                    'alias' => 'KraFeedback',
//                    'type' => 'LEFT',
//                    'conditions' => array('KraFeedback.id = EmployeeReview.reason_id')
//                ),
//            ),
//            'conditions' => array(
//                'EmployeeKraMapping.id' => $kra_mapping_id,
//                'EmployeeKraMapping.deleted' => 0,
//            ),
//        ));

        $learning_and_development = $this->EmployeeKraMapping->find('all', array('fields' => array('RequestEvaluvation.*'),
            'joins' => array(
                array(
                    'table' => 'request_evaluvations',
                    'alias' => 'RequestEvaluvation',
                    'type' => 'INNER',
                    'conditions' => array('RequestEvaluvation.employee_kra_mapping_id = EmployeeKraMapping.id')
                ),
            ),
            'conditions' => array(
                'EmployeeKraMapping.id' => $kra_mapping_id,
                'EmployeeKraMapping.deleted' => 0,
            ),
        ));
        
        $employee_attachements = $this->EmployeeKraMapping->find('all', array('fields' => array('KraUpload.*'),
            'joins' => array(
                array(
                    'table' => 'kra_uploads',
                    'alias' => 'KraUpload',
                    'type' => 'INNER',
                    'conditions' => array('KraUpload.employee_kra_mapping_id = EmployeeKraMapping.id')
                ),
            ),
            'conditions' => array(
                'EmployeeKraMapping.id' => $kra_mapping_id,
                'EmployeeKraMapping.deleted' => 0,
                'KraUpload.deleted' => 0
            ),
        ));
        $this->set('employee_attachements', $employee_attachements);

        $this->set('learning_and_development', $learning_and_development);
        $kra_feedback = $this->KraFeedback->find('list', array('fields' => array('KraFeedback.id', 'KraFeedback.name'), 'conditions' => array('KraFeedback.deleted' => 0)));
        $emp_details = $this->Employee->find('first', array('conditions' => array('Employee.id' => $emp_id)));
        $this->set('kra_feedback', $kra_feedback);
        $this->set('employee_details', $emp_details);
        $this->set('emp_kra_details', $employee_kra_details);
        $this->set('emp_id', $emp_id);
        $this->set('auth_user', $this->Auth->user('employee_id'));
        $target_visibility = false;
        $kra_target = $this->KraTemplate->find('all', array('conditions' => array('KraTemplate.kra_master_id' => $employee_kra_details[0]['EmployeeKraMapping']['kra_master_id'], 'KraTemplate.uom_id != ""')));
        if (!empty($kra_target)) {
            $target_visibility = true;
        }
        $this->set('target_visibility', $target_visibility);
        $this->set('auth_user_id', $this->Auth->user('employee_id'));
        if (!empty($_POST['request_id'])) {
            Configure::write('debug', 0);
            $this->layout = false;
        }
    }

    function reportee_view($view = null) {
        $this->layout = "kra";
        $this->set('view', $view);
        $employee_detail = $this->Employee->find('all', array('fields' => array('CONCAT(Manager.employee_number," - ",Manager.first_name," ", Manager.last_name) as manager_name',
                'CONCAT(Employee.employee_number," - ",Employee.first_name," ", Employee.last_name) as employee_name',
                'Designation.*', 'Employee.*', 'Band.*', 'CompanyStructure.*', 'CompanyStructureGroup.*', 'Country.*'),
            'conditions' => array('Employee.id' => $this->Auth->user('employee_id'))));
        $practice = $sub_practice = '';
        if ($employee_detail[0]['CompanyStructure']['parent_id'] == Configure::read('CompanyStructure.SBU_ID')) {
            $practice = "Business Unit";
            $sub_practice = "Practice";
        } else if ($employee_detail[0]['CompanyStructure']['parent_id'] == Configure::read('CompanyStructure.SSU_ID')) {
            $practice = "Enabling Function";
            $sub_practice = "Sub Function";
        } else if ($employee_detail[0]['CompanyStructure']['parent_id'] == Configure::read('CompanyStructure.SALES')) {
            $practice = "Sales";
            $sub_practice = "Geography/Account";
        }
        $this->set('practice', $practice);
        $this->set('sub_practice', $sub_practice);
    }

    function reportee_view_datatables($view) {
        $this->autoRender = false;
        if ($this->RequestHandler->isAjax()) {
            $output = '';
            if ($view) {
                $output = $this->KraMaster->kra_report($_GET, $view, $this->Auth->user('employee_id'));
                echo json_encode($output);
            }
        }
    }

    function hr_approve($type = '', $rm_change_id = '') {
        if ($rm_change_id && $type) {
            if ($type == 'send_back') {
                $save_data['status'] = 'r';
                $save_data['id'] = $rm_change_id;
                $this->EmployeeRmChange->save($save_data);
                $this->redirect(array('controller' => 'kra_masters', 'action' => "hr_approve"));
            } else if ($type == 'approve') {
                $save_data['status'] = 'a';
                $save_data['id'] = $rm_change_id;
                $save_data['template_id'] = $_POST['template_id'];
                $save_data['approved_by'] = $this->Auth->user("employee_id");
                $this->EmployeeRmChange->save($save_data);
                $rm_detail = $this->EmployeeRmChange->find('all', array('conditions' => array('id' => $rm_change_id)));
                $reason = 'manager changed';
//                $insert_qry = sprintf("INSERT into employee_info_histories(employee_id,changed_by,reason,changed_from,changed_to,field_changed,modified_time,created_time) values(%d,%d,'%s','%s','%s','%s',%d,%d)", $rm_detail[0]['EmployeeRmChange']['employee_id'], $rm_detail[0]['EmployeeRmChange']['changed_by'], $reason, $rm_detail[0]['EmployeeRmChange']['changed_from'], $rm_detail[0]['EmployeeRmChange']['changed_to'], "Manager", time(), time());
//                $this->Employee->query($insert_qry);
                $this->data['Employee']['manager'] = $rm_detail[0]['EmployeeRmChange']['changed_to'];

                $update_employee['id'] = $rm_detail[0]['EmployeeRmChange']['employee_id'];
                $update_employee['manager'] = $rm_detail[0]['EmployeeRmChange']['changed_to'];
//                $this->Employee->save($update_employee);
                $this->_saveEmployeeHistory($rm_detail[0]['EmployeeRmChange']['employee_id'], true, $_POST['template_id']);
                if ($this->RequestHandler->isAjax()) {
                    echo "SUCCESS";
                    Configure::write('debug', 0);
                    $this->autoRender = false;
                    exit();
                }
                // $this->redirect(array('controller' => 'kra_masters', 'action' => "hr_approve"));
            }
        } else {
            $this->layout = "kra";
            $EmployeeRmChange = $this->EmployeeRmChange->find('all', array('fields' => array('EmployeeRmChange.id', 'EmployeeRmChange.kra_change',
                    'CONCAT(RequestedBy.employee_number," - ",RequestedBy.first_name," ", RequestedBy.last_name) as requested_by',
                    'CONCAT(RequestedOn.employee_number," - ",RequestedOn.first_name," ", RequestedOn.last_name) as requested_on',
                    'CONCAT(ChangedFrom.employee_number," - ",ChangedFrom.first_name," ", ChangedFrom.last_name) as changed_from',
                    'CONCAT(ApprovedBy.employee_number," - ",ApprovedBy.first_name," ", ApprovedBy.last_name) as approved_by',
                    'CONCAT(ChangedTo.employee_number," - ",ChangedTo.first_name," ", ChangedTo.last_name) as changed_to'),
                'joins' => array(
                    array(
                        'table' => 'employees',
                        'alias' => 'RequestedBy',
                        'type' => 'INNER',
                        'conditions' => array('EmployeeRmChange.changed_by = RequestedBy.id')
                    ),
                    array(
                        'table' => 'employees',
                        'alias' => 'RequestedOn',
                        'type' => 'INNER',
                        'conditions' => array('EmployeeRmChange.employee_id = RequestedOn.id')
                    ),
                    array(
                        'table' => 'employees',
                        'alias' => 'ChangedFrom',
                        'type' => 'INNER',
                        'conditions' => array('EmployeeRmChange.changed_from = ChangedFrom.id')
                    ),
                    array(
                        'table' => 'employees',
                        'alias' => 'ChangedTo',
                        'type' => 'INNER',
                        'conditions' => array('EmployeeRmChange.changed_to = ChangedTo.id')
                    ),
                    array(
                        'table' => 'employees',
                        'alias' => 'ApprovedBy',
                        'type' => 'INNER',
                        'conditions' => array('EmployeeRmChange.approved_by = ApprovedBy.id')
                    ),
                ),
                'conditions' => array('EmployeeRmChange.deleted' => 0, 'EmployeeRmChange.status' => 'd')));

            $this->set('EmployeeRmChange', $EmployeeRmChange);
            $kraTemplates = $this->KraMaster->find('list', array('fields' => array('KraMaster.id', 'KraMaster.template_name'), 'conditions' => array('KraMaster.custom' => 0)));
            $this->set('kraTemplates', $kraTemplates);
        }
    }

    function employee_edit($employee_id, $view, $kra_mapping_id = null, $request_id = null) {
        if (empty($employee_id) || empty($view)) {
            $this->redirect(array('controller' => 'kra_masters', 'action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($view == 'hr_view') {
                //$this->_saveEmployeeHistory($employee_id);
                $this->redirect(array('controller' => 'kra_masters', 'action' => "reportee_view/$view"));
            } else if ($view = 'rm_view') {
                $beforesave = $this->Employee->find('first', array('fields' => array('Employee.manager', 'Employee.joined_date','Employee.structure_name_subgroup'), 'conditions' => array('Employee.id' => $employee_id)));
                $emp_history = $this->EmployeeInfoHistory->find('first', array('fields' => array('DATE(FROM_UNIXTIME(EmployeeInfoHistory.modified_time)) as modified_time'), 'conditions' => array('EmployeeInfoHistory.employee_id' => $employee_id, 'EmployeeInfoHistory.changed_to' => $beforesave['Employee']['manager'], 'EmployeeInfoHistory.field_changed' => 'manager'), 'order' => array('EmployeeInfoHistory.id DESC')));
                if (empty($emp_history)) {
                    $from_date = $beforesave['Employee']['joined_date'];
                } else {
                    $from_date = $emp_history[0]['modified_time'];
                }
                $save_data['employee_id'] = $employee_id;
                $save_data['changed_by'] = $this->Auth->user("employee_id");
                $save_data['changed_from'] = $beforesave['Employee']['manager'];
                $save_data['changed_to'] = $this->data['Employee']['manager'];
                $save_data['from_date'] = $from_date;
                $save_data['to_date'] = date("Y-m-d", strtotime('-1 days'));
                $save_data['approved_by'] = $this->data['Employee']['approved_by'];
                $save_data['modified_on'] = date('Y-m-d H:i:s');
                $save_data['status'] = 'm';
                $save_data['deleted'] = 0;
                $this->EmployeeRmChange->save($save_data);
                $this->_saveEmployeeHistory($employee_id);
                
                // mail to practise head
                $cc_list = $mail_cc = array();
                $practice_function_head =array("3","4");
                $practise_head = $this->HeadConfiguration->find('all',array(
                    'fields'=>array('HeadConfiguration.*','Employee.*'),
                                        'joins'=>array(
                                             array(
                                                'table' => 'employees',
                                                'alias' => 'Employee',
                                                'type' => 'LEFT',
                                                'conditions' => array('Employee.id = HeadConfiguration.employee_id')
                                            ),
                                        ),
                                        'conditions'=>array('HeadConfiguration.sub_sbu =' => $beforesave['Employee']['structure_name_subgroup'],'HeadConfiguration.head_structure_id ' => $practice_function_head)));
                foreach($practise_head as $key => $head_details){
                    $cc_list[] =$head_details['Employee']['work_email_address'];
                }
                $cc_list[] = $this->Employee->field('Employee.work_email_address', array('Employee.id =' => $beforesave['Employee']['manager']));
                $cc_list[] = $this->Employee->field('Employee.work_email_address', array('Employee.id =' => $employee_id));
                $hr_dl = $this->_getHrMailList();
                $mail_cc = array_merge($cc_list,$hr_dl);
                Configure::load('messages');
                $subjectBody = Configure::read('KRA_RM_CHANGE_NOTIFICATION');
                $employeeName = $this->Employee->field('CONCAT(Employee.first_name," ",Employee.last_name)', array('Employee.id =' => $employee_id));
                $employee_number = $this->Employee->field('Employee.employee_number', array('Employee.id =' => $employee_id));
                $managerName = $this->Employee->field('CONCAT(Employee.first_name," ",Employee.last_name)', array('Employee.id =' => $this->data['Employee']['manager']));
                $this->Email->to = $this->Employee->field('Employee.work_email_address', array('Employee.id =' => $employee_id));
                $this->Email->html_body = sprintf($subjectBody['body'],$managerName,$employee_number, $employeeName,date('d-m-Y'));
                $this->Email->subject = $subjectBody['subject'];
                $this->Email->cc = $mail_cc;
                $result = $this->Email->send();
                $this->redirect(array('controller' => 'kra_masters', 'action' => "reportee_view/$view"));
            }
        } else {
            $this->layout = "kra";
            $employee_detail = $this->Employee->find('all', array('conditions' => array('Employee.id' => $employee_id)));
            $this->set('employee_detail', $employee_detail);
            $this->set('company_structure', $this->CompanyStructure->find('list', array('fields' => array('CompanyStructure.id', 'CompanyStructure.name'), 'conditions' => array('CompanyStructure.parent_id = ' => '0'))));
            $structureName = $this->CompanyStructure->find('list', array('fields' => array('CompanyStructure.id', 'CompanyStructure.name'), 'conditions' => array('CompanyStructure.parent_id' => $employee_detail[0]['CompanyStructure']['parent_id'])));
            $this->set('structure_name', $structureName);
            $this->set('structure_name_subgroup', $this->CompanyStructure->find('list', array('fields' => array('CompanyStructure.id', 'CompanyStructure.name'), 'conditions' => array('CompanyStructure.parent_id' => $employee_detail[0]['Employee']['structure_name']))));
            $this->set('bands', $this->generateParentLevelList());
            $this->set('levels_list', $this->Common->get_sub_bands($employee_detail[0]['Band']['parent_id']));
            $this->set('employee_type', $this->employeeType());
            $this->set('employment_status', $this->employmentStatus('edit'));
            $this->set('selected_designations', $this->generateDesignationList());
            $this->set('view', $view);
            $this->set('kra_mapping_id', $kra_mapping_id);
            $report_levels_list = $this->Common->get_rm($employee_detail[0]['Band']['id']);
            foreach ($report_levels_list as $report_levels_lists) {
                $ids[] = $report_levels_lists['LevelReporting']['report_level'];
            }
            $in_id = implode(',', $ids);
            $managers_list = $this->Employee->query('SELECT id, employee_number, first_name, last_name FROM employees WHERE employment_status not in ("r","t","b","q","o") and band_id IN (' . $in_id . ') order by first_name');
            foreach ($managers_list as $managers_lists) {
                $managerListData[$managers_lists['employees']['id']] = $managers_lists['employees']['employee_number'] . ' - ' . $managers_lists['employees']['first_name'] . ' ' . $managers_lists['employees']['last_name'];
            }
            $this->set('managers', $managerListData);
            $rm_id = $this->Employee->field('Employee.manager', array('Employee.id =' => $employee_id));
            $approvers = $this->get_transfer_approvers($rm_id);
            $this->set('function_heads', $approvers);
        }
        if (!empty($request_id)) {
            $request_details = $this->EmployeeRmChange->find("all", array('conditions' => array('id' => $request_id)));
            $this->set('request_details', $request_details[0]);
            $this->set('changed_approvers', $this->get_transfer_approvers($request_details[0]['EmployeeRmChange']['changed_by']));
        }
    }

    function sub_bands() {
        $this->autoRender = false;
        if ($this->RequestHandler->isAjax()) {
            $parent_id = $_POST['data'];
            $bands = $this->Common->get_sub_bands($parent_id);
            $output = '<option value="">-- Select Sub Band --</option>';
            foreach ($bands as $key => $val) {
                $output .= "<option value =" . $key . ">" . $val . "</option>";
            }
            return $output;
        }
    }

    function ajaxGenerateReportingManagers() {
        if ($this->RequestHandler->isAjax()) {
            $val = $_POST['data'];
            $report_levels_list = $this->Common->get_rm($val);
            $output = '<option value="">--Select reporting manager--</option>';
            if ($report_levels_list) {
                foreach ($report_levels_list as $report_levels_lists) {
                    $ids[] = $report_levels_lists['LevelReporting']['report_level'];
                }
                $in_id = implode(',', $ids);
                $managers_list = $this->Employee->query('SELECT id, employee_number, first_name, last_name FROM employees WHERE employment_status not in ("r","t","b","q","o") and band_id IN (' . $in_id . ')');
                foreach ($managers_list as $managers_lists) {

                    $output .= '<option value="' . $managers_lists['employees']['id'] . '">' . $managers_lists['employees']['employee_number'] . '-' . $managers_lists['employees']['first_name'] . ' ' . $managers_lists['employees']['last_name'] . '</option>';
                }
            }
            echo $output;
        }
        Configure::write('debug', 0);
        $this->autoRender = false;
        exit();
    }

    function sbu_ssu_search() {
        $this->autoRender = false;
        Configure::write('debug', 0);
        $this->layout = "ajax";
        $sbu_ssu = $_POST['data'];
        $sbu_ssu_structure = $this->CompanyStructure->find('all', array('fields' => array('name'), 'conditions' => array('CompanyStructure.parent_id=' . $sbu_ssu . '')));
        foreach ($sbu_ssu_structure as $cs) {
            $cs_parent_sub[$cs['CompanyStructure']['id']] = $cs['CompanyStructure']['name'];
        }

        $avilable_sbu_ssu_structure = '<option value="">-- Select Practice --</option>';
        foreach ($cs_parent_sub as $key => $val) {
            if ($val != "Obsolete")
                $avilable_sbu_ssu_structure .= "<option value =" . $key . " >" . $val . "</option>";
        }
        return $avilable_sbu_ssu_structure;
    }

    function sub_sbu_ssu_search() {
        $this->autoRender = false;
        $sbu_ssu = $_POST['data'];
        $sbu_ssu_structure = $this->CompanyStructure->find('all', array('fields' => array('name'), 'conditions' => array('CompanyStructure.parent_id=' . $sbu_ssu . '')));
        foreach ($sbu_ssu_structure as $cs) {
            $sub_structures[$cs['CompanyStructure']['id']] = $cs['CompanyStructure']['name'];
        }
        $avilable_sub_sbu_ssu_structure = '<option value="">-- Select Sub Practice --</option>';
        foreach ($sub_structures as $key => $val) {
            $avilable_sub_sbu_ssu_structure .= "<option value =" . $key . ">" . $val . "</option>";
        }
        return $avilable_sub_sbu_ssu_structure;
    }

    function pm_review($emp_id = null, $kra_mapping_id = null, $view = null) {
        $this->layout = "kra";
        $this->layout = false;
        if (empty($emp_id) || ($emp_id == null)) {
            $emp_id = $this->Auth->user('employee_id');
        }
        $this->layout = "kra";
        $status = array('o' => 'Open', 'm' => 'Submitted', 'r' => 'Sent Back', 's' => 'Saved', 'a' => 'Closed', 'h' => 'Hold');
        $status_class = array('o' => 'badge badge-success white-space-normal', 'm' => 'badge badge-primary white-space-normal', 'r' => 'badge badge-warning white-space-normal', 's' => 'badge badge-info white-space-normal', 'a' => 'badge badge-danger white-space-normal', 'h' => 'badge badge-secondary white-space-normal');
        $this->set('status', $status);
        $this->set('status_class', $status_class);
        $edit_status = array('o', 's', 'r');
        $this->set('edit_status', $edit_status);
        $this->set('billable_status', $this->generateBillableEmployeeList());

        $employee_detail = $this->Employee->find('all', array('fields' => array('CONCAT(Manager.employee_number," - ",Manager.first_name," ", Manager.last_name) as manager_name',
                'CONCAT(Employee.employee_number," - ",Employee.first_name," ", Employee.last_name) as employee_name',
                'Designation.*', 'Employee.*', 'Band.*', 'CompanyStructure.*', 'CompanyStructureGroup.*', 'Country.*'),
            'conditions' => array('Employee.id' => $emp_id)));
        $this->set('employee_detail', $employee_detail);
        $employee_kra_details = $this->_get_pm_review_data($emp_id);
        $this->set('employee_kra_details', $employee_kra_details);
        If ($_POST) {
            $kra_mapping_id = $this->data['EmployeeKraDetail']['employee_kra_mapping_id']['0'];
            $pm_data = $this->_get_pm_review_data($this->Auth->user('employee_id'), $kra_mapping_id);

            $kraPmMaster ['project_id'] = $pm_data['0']['Project']['id'];
            $kraPmMaster ['pm_id'] = $this->Auth->user('employee_id');
            $kraPmMaster ['employee_kra_mapping_id'] = $pm_data['0']['EmployeeKraMapping']['id'];
            $kraPmMaster ['pta_id'] = $pm_data['0']['ProjectTeamAllocation']['id'];
            $kraPmMaster ['overall_rating'] = $this->data['EmployeeKraMapping']['overall_rating'];
            $this->KraPmMaster->create();
            if ($this->KraPmMaster->save($kraPmMaster)) {
                $kraMasterId = $this->KraPmMaster->getLastInsertId();
            }
            foreach ($this->data['EmployeeKraDetail']['employee_kra_mapping_id'] as $key => $val) {
                $employee_kra_data ['kra_pm_master_id'] = $kraMasterId;
                $employee_kra_data ['kra_template_id'] = $this->data['EmployeeKraDetail']['kra_template_id'][$key];
                $employee_kra_data ['pm_rating'] = (isset($this->data['EmployeeKraDetail']['manager_rating'][$key]) || !empty($this->data['EmployeeKraDetail']['manager_rating'][$key])) ? $this->data['EmployeeKraDetail']['manager_rating'][$key] : '';
                $employee_kra_data ['pm_comments'] = (isset($this->data['EmployeeKraDetail']['manager_comments'][$key]) || !empty($this->data['EmployeeKraDetail']['manager_comments'][$key])) ? trim($this->data['EmployeeKraDetail']['manager_comments'][$key]) : '';
                $this->KraPmReview->create();
                $this->KraPmReview->save($employee_kra_data);
            }
        }
    }

    function rm_feedback($emp_id = null, $kra_mapping_id = null, $view = null) {
        //  $this->layout = "kra";
        $this->layout = false;
        $this->autoRender = false;
        if (empty($emp_id) || ($emp_id == null)) {
            $emp_id = $this->Auth->user('employee_id');
        }
        //   $this->layout = "kra";
        $status = array('o' => 'Open', 'm' => 'Submitted', 'r' => 'Sent Back', 's' => 'Saved', 'a' => 'Closed', 'h' => 'Hold');
        $status_class = array('o' => 'badge badge-success white-space-normal', 'm' => 'badge badge-primary white-space-normal', 'r' => 'badge badge-warning white-space-normal', 's' => 'badge badge-info white-space-normal', 'a' => 'badge badge-danger white-space-normal', 'h' => 'badge badge-secondary white-space-normal');
        $this->set('status', $status);
        $this->set('status_class', $status_class);
        $edit_status = array('o', 's', 'r');
        $this->set('edit_status', $edit_status);
        $this->set('billable_status', $this->generateBillableEmployeeList());

        $employee_detail = $this->Employee->find('all', array('fields' => array('CONCAT(Manager.employee_number," - ",Manager.first_name," ", Manager.last_name) as manager_name',
                'CONCAT(Employee.employee_number," - ",Employee.first_name," ", Employee.last_name) as employee_name',
                'Designation.*', 'Employee.*', 'Band.*', 'CompanyStructure.*', 'CompanyStructureGroup.*', 'Country.*'),
            'conditions' => array('Employee.id' => $emp_id)));
        $this->set('employee_detail', $employee_detail);
        $employee_kra_details = $this->_get_rm_review_data($emp_id);
        $this->set('employee_kra_details', $employee_kra_details);
        if (($this->RequestHandler->isAjax())) {
            $kra_mapping_id = $this->data['EmployeeKraDetail']['employee_kra_mapping_id']['0'];
            $kraRmMaster ['rm_id'] = $this->Auth->user('employee_id');
            $kraRmMaster ['employee_id'] = $emp_id;
            $kraRmMaster ['employee_kra_mapping_id'] = $kra_mapping_id;
            $kraRmMaster ['overall_rating'] = $this->data['EmployeeKraMapping']['overall_rating'];
            $kraRmMaster ['submitted_on'] = date('Y-m-d H:i:s');

            $this->KraRmMaster->create();
            if ($this->KraRmMaster->save($kraRmMaster)) {
                $kraMasterId = $this->KraRmMaster->getLastInsertId();
            }
            foreach ($this->data['EmployeeKraDetail']['employee_kra_mapping_id'] as $key => $val) {
                $employee_kra_data ['kra_rm_master_id'] = $kraMasterId;
                $employee_kra_data ['kra_master_id'] = $kraMasterId;
                $employee_kra_data ['kra_template_id'] = $this->data['EmployeeKraDetail']['kra_template_id'][$key];
                $employee_kra_data ['rm_rating'] = (isset($this->data['EmployeeKraDetail']['manager_rating'][$key]) || !empty($this->data['EmployeeKraDetail']['manager_rating'][$key])) ? $this->data['EmployeeKraDetail']['manager_rating'][$key] : '';
                $employee_kra_data ['rm_comments'] = (isset($this->data['EmployeeKraDetail']['manager_comments'][$key]) || !empty($this->data['EmployeeKraDetail']['manager_comments'][$key])) ? trim($this->data['EmployeeKraDetail']['manager_comments'][$key]) : '';
                $this->KraRmReview->create();
                $this->KraRmReview->save($employee_kra_data);
            }
//            
            $beforesave = $this->Employee->find('first', array('fields' => array('Employee.manager', 'Employee.joined_date'), 'conditions' => array('Employee.id' => $emp_id)));
            $emp_history = $this->EmployeeInfoHistory->find('first', array('fields' => array('DATE(FROM_UNIXTIME(EmployeeInfoHistory.modified_time)) as modified_time'), 'conditions' => array('EmployeeInfoHistory.employee_id' => $emp_id, 'EmployeeInfoHistory.changed_to' => $beforesave['Employee']['manager'], 'EmployeeInfoHistory.field_changed' => 'manager'), 'order' => array('EmployeeInfoHistory.id DESC')));
            if (empty($emp_history)) {
                $from_date = $beforesave['Employee']['joined_date'];
            } else {
                $from_date = $emp_history[0]['modified_time'];
            }
            $save_data['employee_id'] = $emp_id;
            $save_data['changed_by'] = $this->Auth->user("employee_id");
            $save_data['from_date'] = $from_date;
            $save_data['to_date'] = date("Y-m-d", strtotime('-1 days'));
            $save_data['changed_from'] = $beforesave['Employee']['manager'];
            $save_data['changed_to'] = $this->data['Employee']['manager'];
            $save_data['approved_by'] = $this->data['Employee']['approved_by'];
            $save_data['modified_on'] = date('Y-m-d H:i:s');
            $save_data['status'] = 'm';
            $save_data['kra_change'] = 0;
            $save_data['deleted'] = 0;
            $this->EmployeeRmChange->save($save_data);
            echo "SUCCESS";
            Configure::write('debug', 0);
            $this->autoRender = false;
            exit();
        }
    }

    function _get_rm_review_data($pm_id, $kra_mapping_id = null) {

        $condition = 'EmployeeKraMapping.employee_id =KraRmMaster.employee_id';

        $employee_kra_details = array();
        $employee_kra_details = $this->EmployeeKraMapping->find('all', array('fields' => array('KraFrequency.*', 'EmployeeKraMapping.*', 'Band.name',
                'CompanyStructure.name', 'Employee.id',
                'concat(Employee.employee_number,"-",Employee.first_name," ",Employee.last_name) as employee_name',
                'CONCAT(EmployeeManager.employee_number," - ",EmployeeManager.first_name," ", EmployeeManager.last_name) as manager',
                'CONCAT(ReportingManager.employee_number," - ",ReportingManager.first_name," ", ReportingManager.last_name) as projectmanager'),
            'joins' => array(
                array(
                    'table' => 'kra_rm_masters',
                    'alias' => 'KraRmMaster',
                    'type' => 'INNER',
                    'conditions' => array('EmployeeKraMapping.employee_id  = KraRmMaster.employee_id')
                ),
                array(
                    'table' => 'bands',
                    'alias' => 'Band',
                    'type' => 'LEFT',
                    'conditions' => array('EmployeeKraMapping.band_id = Band.id')
                ),
                array(
                    'table' => 'company_structures',
                    'alias' => 'CompanyStructure',
                    'type' => 'LEFT',
                    'conditions' => array('CompanyStructure.id = EmployeeKraMapping.sub_sbu_id')
                ),
                array(
                    'table' => 'employees',
                    'alias' => 'Employee',
                    'type' => 'LEFT',
                    'conditions' => array('Employee.id = EmployeeKraMapping.employee_id')
                ),
                array(
                    'table' => 'employees',
                    'alias' => 'EmployeeManager',
                    'type' => 'LEFT',
                    'conditions' => array('EmployeeManager.id = EmployeeKraMapping.manager_id')
                ),
                array(
                    'table' => 'employees',
                    'alias' => 'ReportingManager',
                    'type' => 'LEFT',
                    'conditions' => array('ReportingManager.id = KraRmMaster.rm_id')
                ),
                array(
                    'table' => 'kra_masters',
                    'alias' => 'KraMaster',
                    'type' => 'LEFT',
                    'conditions' => array('KraMaster.id = EmployeeKraMapping.kra_master_id')
                ),
                array(
                    'table' => 'kra_frequencies',
                    'alias' => 'KraFrequency',
                    'type' => 'LEFT',
                    'conditions' => array('KraFrequency.id = KraMaster.frequency_id')
                ),
            ),
            'conditions' => array($condition,
                'EmployeeKraMapping.deleted' => 0,
                'DATE(KraRmMaster.submitted_on) BETWEEN concat(EmployeeKraMapping.year,"-",KraFrequency.start_month,"-01") 
                   AND  CURDATE()')));
        return $employee_kra_details;
    }

    function _get_pm_review_data($pm_id, $kra_mapping_id = null) {
        if ($kra_mapping_id) {
            $condition = 'EmployeeKraMapping.employee_id =ProjectTeamAllocation.employee_id and EmployeeKraMapping.id =' . $kra_mapping_id;
        } else {
            $condition = 'EmployeeKraMapping.employee_id =ProjectTeamAllocation.employee_id';
        }
        $employee_kra_details = array();
        $employee_kra_details = $this->EmployeeKraMapping->find('all', array('fields' => array('KraFrequency.*', 'ProjectTeamAllocation.employee_id', 'EmployeeKraMapping.*', 'Band.name',
                'CompanyStructure.name', 'Employee.id', 'Project.id', 'ProjectTeamAllocation.id',
                'concat(Project.project_code,"-",Project.project_name) as project_name',
                'concat(Employee.employee_number,"-",Employee.first_name," ",Employee.last_name) as employee_name',
                'CONCAT(EmployeeManager.employee_number," - ",EmployeeManager.first_name," ", EmployeeManager.last_name) as manager',
                'CONCAT(ProjectManager.employee_number," - ",ProjectManager.first_name," ", ProjectManager.last_name) as projectmanager'),
            'joins' => array(
                array(
                    'table' => 'projects',
                    'alias' => 'Project',
                    'type' => 'inner',
                    'conditions' => array('Project.project_manager = ' . $pm_id)
                ),
                array(
                    'table' => 'project_team_allocations',
                    'alias' => 'ProjectTeamAllocation',
                    'type' => 'inner',
                    'conditions' => array('ProjectTeamAllocation.project_id = Project.id')
                ),
                array(
                    'table' => 'kra_pm_masters',
                    'alias' => 'KraPmMaster',
                    'type' => 'INNER',
                    'conditions' => array('ProjectTeamAllocation.id  != KraPmMaster.pta_id')
                ),
                array(
                    'table' => 'bands',
                    'alias' => 'Band',
                    'type' => 'LEFT',
                    'conditions' => array('EmployeeKraMapping.band_id = Band.id')
                ),
                array(
                    'table' => 'company_structures',
                    'alias' => 'CompanyStructure',
                    'type' => 'LEFT',
                    'conditions' => array('CompanyStructure.id = EmployeeKraMapping.sub_sbu_id')
                ),
                array(
                    'table' => 'employees',
                    'alias' => 'Employee',
                    'type' => 'LEFT',
                    'conditions' => array('Employee.id = EmployeeKraMapping.employee_id')
                ),
                array(
                    'table' => 'employees',
                    'alias' => 'EmployeeManager',
                    'type' => 'LEFT',
                    'conditions' => array('EmployeeManager.id = EmployeeKraMapping.manager_id')
                ),
                array(
                    'table' => 'employees',
                    'alias' => 'ProjectManager',
                    'type' => 'LEFT',
                    'conditions' => array('ProjectManager.id = EmployeeKraMapping.pm_id')
                ),
                array(
                    'table' => 'kra_masters',
                    'alias' => 'KraMaster',
                    'type' => 'LEFT',
                    'conditions' => array('KraMaster.id = EmployeeKraMapping.kra_master_id')
                ),
                array(
                    'table' => 'kra_frequencies',
                    'alias' => 'KraFrequency',
                    'type' => 'LEFT',
                    'conditions' => array('KraFrequency.id = KraMaster.frequency_id')
                ),
            ),
            'conditions' => array($condition,
                'EmployeeKraMapping.deleted' => 0,
                'ProjectTeamAllocation.end_date BETWEEN concat(EmployeeKraMapping.year,"-",KraFrequency.start_month,"-01") 
                   AND  CURDATE()')));
        return $employee_kra_details;
    }

    function reportees_list($status) {
        $this->autoRender = false;
        $employee_kra_details = $this->EmployeeKraMapping->find('all', array('fields' => array('EmployeeKraMapping.*', 'KraTemplate.*', 'Kra.*', 'KraUom.*', 'Employee.*', 'EmployeeManager.*'),
            'joins' => array(
//                                            array(
//                                                    'table' => 'employee_kra_detailss',
//                                                    'alias' => 'EmployeeKraMapping',
//                                                    'type' => 'LEFT',
//                                                    'conditions' => array('EmployeeKraMapping.employee_kra_master_id = EmployeeKraMaster.id')
//                                                ),
                array(
                    'table' => 'kra_templates',
                    'alias' => 'KraTemplate',
                    'type' => 'LEFT',
                    'conditions' => array('KraTemplate.kra_master_id = EmployeeKraMapping.kra_master_id')
                ),
                array(
                    'table' => 'kras',
                    'alias' => 'Kra',
                    'type' => 'LEFT',
                    'conditions' => array('KraTemplate.kra_id = Kra.id')
                ),
                array(
                    'table' => 'kra_uoms',
                    'alias' => 'KraUom',
                    'type' => 'LEFT',
                    'conditions' => array('KraTemplate.uom_id = KraUom.id')
                ),
                array(
                    'table' => 'employees',
                    'alias' => 'Employee',
                    'type' => 'LEFT',
                    'conditions' => array('Employee.id = EmployeeKraMapping.employee_id')
                ),
                array(
                    'table' => 'employees',
                    'alias' => 'EmployeeManager',
                    'type' => 'LEFT',
                    'conditions' => array('EmployeeManager.id = Employee.manager')
                ),
            ),
            'conditions' => array('EmployeeKraMapping.manager_id' => $this->Auth->user('employee_id'),
                'EmployeeKraMapping.status' => $status,
                'EmployeeKraMapping.deleted' => 0)));
        $employeeList = array();
        foreach ($employee_kra_details as $employee_kra_detail) {


            $employeeList[$employee_kra_detail['Employee']['id'] . '/' . $employee_kra_detail['EmployeeKraMapping']['id']] = $employee_kra_detail['Employee']['employee_number'] . ' - ' . $employee_kra_detail['Employee']['first_name'] . ' ' . $employee_kra_detail['Employee']['last_name'];
            $kraMappingId[$employee_kra_detail['Employee']['id']] = $employee_kra_detail['EmployeeKraMapping']['id'];
        }
        echo json_encode($employeeList);
    }

    function set_selected_menu($val = "") {
        $_SESSION['selected_menu'] = $val;
        Configure::write('debug', 0);
        $this->autoRender = false;
        exit();
    }

    function event_record() {
        $this->autoRender = false;
        Configure::write('debug', 0);
        if (isset($_POST['type']) && !empty($_POST['type'])) {
            $emp_id = explode('/', $_POST['employee_id']);

            $event_records = $this->KraEventRecord->find('all', array('conditions' => array('KraEventRecord.employee_id' => $_POST['employee_id'], 'KraEventRecord.submitted_by' => $this->Auth->user('employee_id'))));
            $listdata = array();
            foreach ($event_records as $event) {
                $divdata = '<div class="post-item">';
                $divdata .= '<span class="post-date"> ' . date('d/m/Y @ H:i:s', strtotime($event['KraEventRecord']['date_time'])) . ' </span>';
                $divdata .= '<p class="tx-12 mg-b-0">' . $event['KraEventRecord']['comment'] . '</p>';
                $divdata .= '</div>';
                $listdata[] = $divdata;
            }
            echo json_encode($listdata);
        } else {
            $gmtTimezone = new DateTimeZone('IST');
            $myDateTime = new DateTime($_POST['date'], $gmtTimezone);
            $myDateTime->format('r');
//        $emp_id = explode('/', $_POST['employee_id']);
            $eventRecord['employee_id'] = $_POST['employee_id'];
            $eventRecord['date_time'] = $myDateTime->format('Y-m-d H:i:s');
            $eventRecord['comment'] = $_POST['comment'];
            $eventRecord['submitted_by'] = $this->Auth->user('employee_id');
            $eventRecord['deleted'] = '0';
            $this->KraEventRecord->create();
            $this->KraEventRecord->save($eventRecord);
        }
    }

    function employee_review() {
        if ($this->RequestHandler->isAjax()) {
            $employee_review = $this->data;
            if ($this->EmployeeKraMapping->save($employee_review))
                echo 'SUCCESS';
        }
        Configure::write('debug', 0);
        $this->autoRender = false;
        exit();
    }

    function calculate_overall_rating() {
        $error = false;
        $message = "SUCCESS";
        $get_employees = @mysql_query('SELECT  COUNT(kra.status) AS kras,empcount,kra.employee_id FROM employee_kra_mappings AS kra  
                                        INNER JOIN (SELECT COUNT(emp_count) AS empcount ,employee_id FROM  
                                        ( SELECT  COUNT(STATUS),COUNT(employee_id)AS emp_count,employee_id,STATUS FROM employee_kra_mappings GROUP BY employee_id,STATUS)
                                         AS emp GROUP BY employee_id ) AS kra1 ON (kra.employee_id = kra1.employee_id) WHERE kra.status ="a" AND kra1.empcount =1 GROUP BY kra.employee_id, kra.status;
                                        ');
        if (mysql_num_rows($get_employees) >= 1) {
            while ($get_employee_kras = mysql_fetch_array($get_employees)) {
                $kra_mapping_id = array();
                $kra_details = $this->EmployeeKraMapping->find('all', array(
                    'conditions' => array('EmployeeKraMapping.employee_id' => $get_employee_kras['employee_id'])
                ));
                if ($get_employee_kras['kras'] > 1) {
                    foreach ($kra_details as $key => $kra_data) {
                        $get_kra_frequency = $this->KraFrequency->find('first', array());
                        $kra_from_date = $kra_data['EmployeeKraMapping']['year'] . "-" . $get_kra_frequency['KraFrequency']['start_month'] . "-01";
                        $kra_last_date = date("Y-m-d", strtotime($kra_from_date . " +12 months -1 days"));
                        $dates_in_range = $this->Common->date_range($kra_from_date, $kra_last_date);
                        $kra_freq = count($dates_in_range);
                        $kra_duration = $this->Common->date_range($kra_data['EmployeeKraMapping']['from_date'], $kra_data['EmployeeKraMapping']['to_date']);
                        $kra_days = count($kra_duration);
                        $rate += ($kra_data['EmployeeKraMapping']['overall_rating'] / $kra_freq) * $kra_days;
                        $kra_mapping_id[] = $kra_data['EmployeeKraMapping']['id'];
                    }
                    $overall_rating = round($rate);
                } else {
                    //insert direct
                    $overall_rating = $kra_details[0]['EmployeeKraMapping']['overall_rating'];
                    $kra_mapping_id[] = $kra_details[0]['EmployeeKraMapping']['id'];
                }

                $this->data['EmployeeKraRating']['employee_id'] = $get_employee_kras['employee_id'];
                $this->data['EmployeeKraRating']['overall_rating'] = $overall_rating;
                $this->data['EmployeeKraRating']['year'] = date('Y');
                $this->data['EmployeeKraRating']['updated_by'] = $this->Auth->user('employee_id');
                $this->data['EmployeeKraRating']['updated_on'] = date('Y-m-d H:i:s');
                $this->data['EmployeeKraRating']['deleted'] = 0;
                $this->EmployeeKraRating->create();
                if (!$this->EmployeeKraRating->save($this->data['EmployeeKraRating'])) {
                    $error = true;
                }
                //update mapping status
                $this->EmployeeKraMapping->updateAll(array('EmployeeKraMapping.status' => "'c'"), array('EmployeeKraMapping.id' => $kra_mapping_id));
            }
        } else {
            $message = "NO_RECORDS";
        }
        if ($error) {
            $message = "FAILED";
        }
        echo json_encode($message);
        Configure::write('debug', 0);
        $this->autoRender = false;
        exit();
    }

//    function client_feedback($token='') {
//        $this->layout = "kra";
//        if($_GET){
//            $token=$_GET['token'];
//        }
//        $creation_data=$this->KraClientFeedbackCreation->find('first',array('conditions'=>array('KraClientFeedbackCreation.deleted'=>0,'KraClientFeedbackCreation.token'=>$token),'order'=>'KraClientFeedbackCreation.id'));
//        if(empty($creation_data)){
//            $error='error';
//            $this->set('invalid_link',$error);
//        }
//        $customer_id=$creation_data['KraClientFeedbackCreation']['customer_id'];
//        $project_id =!empty($creation_data['KraClientFeedbackCreation']['project_id'])?$creation_data['KraClientFeedbackCreation']['project_id']:'';
//        $customers =  $this->Customer->find('all', array('conditions' => array('Customer.status '=> "a",'Customer.deleted'=>"0",'Customer.id'=>$customer_id)));
//        for ($rating_count = 1; $rating_count <= KraMaster::RATING_MAX_LIMIT; $rating_count++) {
//            $ratings[$rating_count] = $rating_count;
//        }
//        $this->set('ratings', $ratings);
//        $this->set('customers', $customers);
//        $get_kra_frequency = $this->KraFrequency->find('first', array());
//        $kra_start_date = date("Y") . "-" . $get_kra_frequency['KraFrequency']['start_month'] . "-01";
//        $kra_end_date = date("Y-m-d", strtotime($kra_start_date . " +12 months -1 days"));
//
//
//        //-- Customer Details -- //
//        $get_customer_details = $this->Customer->find('all', array('fields' => array('Customer.customer_code', 'Customer.customer_name', 'Customer.division'),
//            'conditions' => array('Customer.id' => $customer_id)));
//        $customer_name = $get_customer_details[0]['Customer']['customer_name'];
//
//        if (!empty($get_customer_details[0]['Customer']['division'])) {
//            $customer_name = $customer_name . " - " . $get_customer_details[0]['Customer']['division'];
//        }
//
//        //-- Conditions -- //
//        if ($project_id == 0||$project_id='') {
//            $projects = $this->get_projects($customer_id);
//            $project_ids = array_keys($projects);
//        } else {
//            $project_ids = $project_id;
//        }
//        //-- Project and Employees Details -- //
//
//        $get_employee_details = $this->Customer->find('all', array('fields' => array('ProjectManager.*','Customer.*','Project.*', 'Employee.*', 'ReportingManager.*','KraClientFeedback.*'),
//            'joins' => array(
//                array(
//                    'table' => 'sales_orders',
//                    'alias' => 'SalesOrder',
//                    'type' => 'INNER',
//                    'conditions' => array(
//                        'SalesOrder.customer_id = Customer.id'
//                    )
//                ),
//                array(
//                    'table' => 'project_so_masters',
//                    'alias' => 'ProjectSoMaster',
//                    'type' => 'INNER',
//                    'conditions' => array(
//                        'ProjectSoMaster.sales_order_id = SalesOrder.id',
//                    )
//                ),
//                array(
//                    'table' => 'projects',
//                    'alias' => 'Project',
//                    'type' => 'INNER',
//                    'conditions' => array(
//                        'Project.id = ProjectSoMaster.project_id',
//                    )
//                ),
//                array(
//                    'table' => 'project_team_allocations',
//                    'alias' => 'ProjectTeamAllocation',
//                    'type' => 'LEFT',
//                    'conditions' => array('ProjectTeamAllocation.project_id = Project.id')
//                ),
//                array(
//                    'table' => 'employees',
//                    'alias' => 'Employee',
//                    'type' => 'LEFT',
//                    'conditions' => array('ProjectTeamAllocation.employee_id = Employee.id')
//                ),
//                array(
//                    'table' => 'employees',
//                    'alias' => 'ReportingManager',
//                    'type' => 'LEFT',
//                    'conditions' => array('Employee.manager = ReportingManager.id')
//                ),
//                array(
//                    'table' => 'employees',
//                    'alias' => 'ProjectManager',
//                    'type' => 'LEFT',
//                    'conditions' => array('Project.project_manager = ProjectManager.id')
//                ),
//                array(
//                    'table' => 'kra_client_feedbacks',
//                    'alias' => 'KraClientFeedback',
//                    'type' => 'LEFT',
//                    'conditions' => array('KraClientFeedback.employee_id = Employee.id')
//                ),
//            ),
//            'conditions' => array(
//                'Project.id' => $project_ids,
//                'Customer.id' => $customer_id,
//                'Employee.employment_status'=>'p',
//                array('OR'=>array('ProjectTeamAllocation.start_date BETWEEN "' . $kra_start_date . '" AND "' . $kra_end_date . '"',
//                    'ProjectTeamAllocation.end_date BETWEEN "' . $kra_start_date . '" AND "' . $kra_end_date . '"',
//                    '"' . $kra_start_date . '" BETWEEN ProjectTeamAllocation.start_date AND ProjectTeamAllocation.end_date',
//                    '"' . $kra_end_date . '" BETWEEN ProjectTeamAllocation.start_date AND ProjectTeamAllocation.end_date')),
//                'OR'=>array('KraClientFeedback.status !="m"','KraClientFeedback.status IS NULL'),
//            ),
//            'group'=>array('Employee.id','Project.id'),
//            'recursive' => -1
//        ));
//        $this->set('get_employee_details',$get_employee_details);
//        $this->set('customer_name',$customer_name);
//    }

    function get_projects($customer_id) {
        $get_kra_frequency = $this->KraFrequency->find('first', array());
        $kra_start_date = date("Y") . "-" . $get_kra_frequency['KraFrequency']['start_month'] . "-01";
        $kra_end_date = date("Y-m-d", strtotime($kra_start_date . " +12 months -1 days"));
        $project_list = $this->Customer->find('all', array(
            'fields' => array('Project.id', 'Project.project_code', 'Project.project_name'),
            'joins' => array(
                array(
                    'table' => 'sales_orders',
                    'alias' => 'SalesOrder',
                    'type' => 'INNER',
                    'conditions' => array(
                        'SalesOrder.customer_id = Customer.id'
                    )
                ),
                array(
                    'table' => 'project_so_masters',
                    'alias' => 'ProjectSoMaster',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ProjectSoMaster.sales_order_id = SalesOrder.id',
                    )
                ),
                array(
                    'table' => 'projects',
                    'alias' => 'Project',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Project.id = ProjectSoMaster.project_id',
                    )
                )),
            'conditions' => array('Customer.id' => $customer_id,
                'OR' => array(
                    'Project.planned_start_date BETWEEN "' . $kra_start_date . '" AND "' . $kra_end_date . '"',
                    'Project.planned_end_date BETWEEN "' . $kra_start_date . '" AND "' . $kra_end_date . '"',
                    '"' . $kra_start_date . '" BETWEEN Project.planned_start_date AND Project.planned_end_date',
                    '"' . $kra_end_date . '" BETWEEN Project.planned_start_date AND Project.planned_end_date'))));
        $projects = array();
        foreach ($project_list as $key => $project_data) {
            $projects[$project_data['Project']['id']] = $project_data['Project']['project_code'] . " - " . $project_data['Project']['project_name'];
        }
        if ($this->RequestHandler->isAjax()) {
            $mail_ids = $this->KraClientFeedbackCreation->find('list', array('fields' => array('KraClientFeedbackCreation.send_mail'), 'conditions' => array('KraClientFeedbackCreation.customer_id' => $customer_id, 'KraClientFeedbackCreation.deleted=0')));
            $response = array('mail' => $mail_ids,
                'project' => $projects);
            echo json_encode($response);
            exit();
        } else {
            return $projects;
        }
        return $projects;
    }

    function hr_kra_templates() {
        $this->layout = "kra";
        if ($this->RequestHandler->isAjax()) {
            $this->autoRender = false;
            $flag = false;
            $custom_array = array('y' => 1, 'n' => 0);
            if ($_POST['kra_master_name']) {
                $master_save_data = array();
                $master_save_data['template_name'] = $_POST['kra_master_name'];
                $master_save_data['custom'] = $custom_array[$_POST['custom_template']];
                $master_save_data['created_on'] = date('Y-m-d H:i:s');
                $master_save_data['status'] = 'a';
                $master_save_data['deleted'] = 0;
                $master_save_data['frequency_id'] = 1;
                $this->KraMaster->create();
                if ($this->KraMaster->save($master_save_data))
                    $flag = true;
            }
            if ($this->data) {
                if ($_POST['data_map'] == 'c') {
                    $kra_master_id = $this->KraMaster->getLastInsertId();
                    $form_data = $this->data['Template'];
                    $template_save_data = array();
                    $template_save_data['kra_master_id'] = $kra_master_id;
                    $template_save_data['created_on'] = date('Y-m-d H:i:s');
                    $template_save_data['status'] = 'a';
                    $template_save_data['deleted'] = 0;
                    foreach ($form_data as $form_data_temp) {
                        $template_save_data['kra_id'] = $form_data_temp['KraTemplate']['kra_id'];
                        $template_save_data['kra_description'] = $form_data_temp['KraTemplate']['kra_description'];
                        $template_save_data['uom_id'] = !empty($form_data_temp['KraTemplate']['uom_id']) ? $form_data_temp['KraTemplate']['uom_id'] : '';
                        $template_save_data['weightage'] = $form_data_temp['KraTemplate']['weightage'];
                        $template_save_data['target'] = !empty($form_data_temp['KraTemplate']['target']) ? $form_data_temp['KraTemplate']['target'] : '';
                        $template_save_data['actual'] = !empty($form_data_temp['KraTemplate']['actual']) ? $form_data_temp['KraTemplate']['actual'] : '';
                        $template_save_data['band_id'] = !empty($form_data_temp['KraTemplate']['band_id']) ? $form_data_temp['KraTemplate']['band_id'] : '';
                        $template_save_data['sub_sbu_id'] = !empty($form_data_temp['KraTemplate']['sub_band_id']) ? $form_data_temp['KraTemplate']['sub_band_id'] : '';
                        $this->KraTemplate->create();
                        if ($this->KraTemplate->save($template_save_data)) {
                            $flag = true;
                        }
                    }
                } else if ($_POST['data_map'] == 'a') {
                    $kra_master_id = $_POST['assigned_kra_master_id'];
                }
                $kra_template_mapping_data['kra_master_id'] = $kra_master_id;
                $kra_template_mapping_data['created_by'] = $this->Auth->user('employee_id');
                $kra_template_mapping_data['created_on'] = date('Y-m-d H:i:s');
                $kra_template_mapping_data['deleted'] = 0;

                $this->KraTemplateMapping->create();
                if ($this->KraTemplateMapping->save($kra_template_mapping_data)) {
                    $flag = true;
                }
                $kra_template_mapping_id = $this->KraTemplateMapping->getLastInsertId();

                $mapping_data = $this->data['mapping'];
                $mapping_saving_data['status'] = 'o';
                $mapping_saving_data['deleted'] = 0;
                $mapping_saving_data['kra_master_id'] = $kra_master_id;
                $conditions = array('((Employee.employment_status in ("p","e") or (Employee.employment_status ="v" and Employee.band_id not in(33,34))))');
                if ($mapping_data['type'] == 'unit') {

                    if (!empty($mapping_data['sbu'])) {
                        $conditions['Employee.structure_name'] = $mapping_data['sbu'];
                        $sbu_data['kra_template_mappings_id'] = $kra_template_mapping_id;
                        $sbu_ids = $mapping_data['sbu'];
                        foreach ($sbu_ids as $key => $value) {
                            $sbu_data['sbu_id'] = $value;
                            $this->SbuTemplateMapping->create();
                            $this->SbuTemplateMapping->save($sbu_data);
                        }
                    } else {
                        $sbu_list = $this->CompanyStructure->find('list', array('fields' => array('CompanyStructure.id'),
                            'conditions' => array('CompanyStructure.parent_id' => $mapping_data['Unit']), 'order' => array('CompanyStructure.parent_id')));
                        $conditions['Employee.structure_name'] = $sbu_list;
                    }
                    if (!empty($mapping_data['sub_sbu'])) {
                        $conditions['Employee.structure_name_subgroup'] = $mapping_data['sub_sbu'];
                        $sub_sbu_data['kra_template_mappings_id'] = $kra_template_mapping_id;
                        $sub_sbu_ids = $mapping_data['sub_sbu'];
                        foreach ($sub_sbu_ids as $key => $value) {
                            $sub_sbu_data['sub_sbu_id'] = $value;
                            $this->SubsbuTemplateMapping->create();
                            $this->SubsbuTemplateMapping->save($sub_sbu_data);
                        }
                    }
                    if (!empty($mapping_data['employee'])) {
                        $conditions['Employee.id'] = $mapping_data['employee'];
                        $employee_data['kra_template_mappings_id'] = $kra_template_mapping_id;
                        $employee_ids = $mapping_data['employee'];
                        foreach ($employee_ids as $key => $value) {
                            $employee_data['employee_id'] = $value;
                            $this->EmployeeTemplateMapping->create();
                            $this->EmployeeTemplateMapping->save($employee_data);
                        }
                    }
                    if (!empty($mapping_data['Unit'])) {
                        $unit_data['kra_template_mappings_id'] = $kra_template_mapping_id;
                        $unit_ids = $mapping_data['Unit'];
                        foreach ($unit_ids as $key => $value) {
                            $unit_data['unit_id'] = $value;
                            $this->UnitTemplateMapping->create();
                            $this->UnitTemplateMapping->save($unit_data);
                        }
                    }
                } elseif ($mapping_data['type'] == 'band') {
                    if (!empty($mapping_data['sub_bands'])) {
                        $conditions['Employee.band_id'] = $mapping_data['sub_bands'];
                        $sub_band_data['kra_template_mappings_id'] = $kra_template_mapping_id;
                        $sub_band_ids = $mapping_data['sub_bands'];
                        foreach ($sub_band_ids as $key => $value) {
                            $sub_band_data['sub_band_id'] = $value;
                            $this->SubbandTemplateMapping->create();
                            $this->SubbandTemplateMapping->save($sub_band_data);
                        }
                    } else {
                        $bands_list = $this->Band->find('list', array('fields' => array('Band.id'),
                            'conditions' => array('Band.parent_id' => $mapping_data['Band']), 'order' => array('Band.parent_id')));
                        $conditions['Employee.band_id'] = $bands_list;
                    }
                    if (!empty($mapping_data['employee'])) {
                        $conditions['Employee.id'] = $mapping_data['employee'];
                        $employee_data['kra_template_mappings_id'] = $kra_template_mapping_id;
                        $employee_ids = $mapping_data['employee'];
                        foreach ($employee_ids as $key => $value) {
                            $employee_data['employee_id'] = $value;
                            $this->EmployeeTemplateMapping->create();
                            $this->EmployeeTemplateMapping->save($employee_data);
                        }
                    }
                    if (!empty($mapping_data['Band'])) {
                        $band_data['kra_template_mappings_id'] = $kra_template_mapping_id;
                        $band_ids = $mapping_data['Band'];
                        foreach ($band_ids as $key => $value) {
                            $band_data['band_id'] = $value;
                            $this->BandTemplateMapping->create();
                            $this->BandTemplateMapping->save($band_data);
                        }
                    }
                } elseif ($mapping_data['type'] == 'employee') {
                    if (!empty($mapping_data['Employee'])) {
                        $conditions['Employee.id'] = $mapping_data['Employee'];
                        $employee_data['kra_template_mappings_id'] = $kra_template_mapping_id;
                        $employee_ids = $mapping_data['Employee'];
                        foreach ($employee_ids as $key => $value) {
                            $employee_data['employee_id'] = $value;
                            $this->EmployeeTemplateMapping->create();
                            $this->EmployeeTemplateMapping->save($employee_data);
                        }
                    }
                }
                $this->Employee->recursive = -1;
                $employee_mapping_list = $this->Employee->find('all', array('fields' => array('Employee.*'),
                    'conditions' => $conditions));
                $get_kra_frequency = $this->KraFrequency->find('first', array());
                $check_year=false;
                if (date('m') < $get_kra_frequency['KraFrequency']['start_month']) {
                    $kra_year = date("Y",strtotime("-1 year"));
                    $check_year=true;
                } else {
                    $kra_year = date('Y');
                }
                $actual_kra_from_date=$this->_kra_from_date($kra_year);
                $kra_year=$kra_year+1;
                $temp_kra_end_date = date('Y-m-d', strtotime("+9 months -1 days", strtotime($actual_kra_from_date)));
                $kra_from_date=date('Y-m-d', strtotime($_POST['kra_start_date']));
                foreach ($employee_mapping_list as $employee_mapping_data) {
                    $check_rm_change_request=$this->EmployeeRmChange->find('first', array('fields' => array('EmployeeRmChange.*'),
                        'conditions' => array('EmployeeRmChange.employee_id'=>$employee_mapping_data['Employee']['id'],'EmployeeRmChange.status in ("n","d")'),'order'=>array('EmployeeRmChange.id DESC')));
                    if(empty($check_rm_change_request)){
                        $check_exist = $this->EmployeeKraMapping->find('first', array('conditions' => array('EmployeeKraMapping.year'=>$kra_year,'EmployeeKraMapping.status'=>'o','EmployeeKraMapping.employee_id' => $employee_mapping_data['Employee']['id'], 'EmployeeKraMapping.deleted' => 0)));
                    }else{
                        $check_exist = $this->EmployeeKraMapping->find('first', array('conditions' => array('EmployeeKraMapping.year'=>$kra_year,'EmployeeKraMapping.status'=>'o','EmployeeKraMapping.employee_id' => $employee_mapping_data['Employee']['id'], 'EmployeeKraMapping.deleted' => 0)));
                        $this->EmployeeRmChange->updateAll(array('EmployeeRmChange.status' => "a"), array('EmployeeRmChange.employee_id' => $employee_mapping_data['Employee']['id']));
                    }
                    if($check_year){
                        if(strtotime($employee_mapping_data['Employee']['joined_date']) > strtotime($temp_kra_end_date)){
                            $final_kra_year=$kra_year+1;
                        }else{
                            $final_kra_year=$kra_year;
                        }
                    }else{
                        $final_kra_year=$kra_year;
                    }
                    $mapping_saving_data['id'] = !empty($check_exist) ? $check_exist['EmployeeKraMapping']['id'] : '';
                    $mapping_saving_data['from_date'] = (strtotime($employee_mapping_data['Employee']['joined_date']) >strtotime($kra_from_date))? $employee_mapping_data['Employee']['joined_date']:$kra_from_date;
                    $mapping_saving_data['year'] = $final_kra_year;
                    $mapping_saving_data['employee_id'] = $employee_mapping_data['Employee']['id'];
                    $mapping_saving_data['manager_id'] = $employee_mapping_data['Employee']['manager'];
                    $mapping_saving_data['band_id'] = $employee_mapping_data['Employee']['band_id'];
                    $mapping_saving_data['sub_sbu_id'] = $employee_mapping_data['Employee']['structure_name_subgroup'];
                    //$this->EmployeeKraMapping->create();
                    if ($this->EmployeeKraMapping->save($mapping_saving_data)) {
                        $flag = true;
                    }
                }
            }
            if ($flag) {
                echo 'SUCCESS';
            }
        } else {
            $kra_name = $this->Kra->find('list', array('fields' => array('Kra.id', 'Kra.kra_name'),
                'conditions' => array('Kra.deleted' => 0, 'Kra.status' => 'a')));
            $kra_uom_name = $this->KraUom->find('list', array('fields' => array('KraUom.id', 'KraUom.uom_name'),
                'conditions' => array('KraUom.deleted' => 0, 'KraUom.status' => 'a')));
            $mapping_array = array('unit' => 'Organization Structure', 'band' => 'Band', 'employee' => 'Employee');
            $company_unit_list = $this->CompanyStructure->find('list', array('fields' => array('CompanyStructure.id', 'CompanyStructure.name'),
                'conditions' => array('CompanyStructure.parent_id' => 0)));
            $band = $this->Band->find('list', array('fields' => array('Band.id', 'Band.name'), 'conditions' => array('Band.parent_id IS NULL', 'Band.name <> "C"')));
            $this->Employee->recursive = -1;
            $employee_list = $this->Employee->find('all', array('fields' => array('Employee.id', 'CONCAT(Employee.employee_number," - ",Employee.first_name," ", Employee.last_name) as employee',),
                'conditions' => array('OR' => array('Employee.employment_status' => array('p', 'e'),
                        'AND' => array('Employee.employment_status' => 'V', 'Employee.band_id Not In (33,34)')))));
            foreach ($employee_list as $employees) {
                $employees_list[$employees['Employee']['id']] = $employees[0]['employee'];
            }
            
            $get_kra_frequency = $this->KraFrequency->find('first', array());
            if (date('m') < $get_kra_frequency['KraFrequency']['start_month']) {
                $kra_year = date("Y",strtotime("-1 year"));
            } else {
                $kra_year = date('Y');
            }
            $kra_from_date=$this->_kra_from_date($kra_year);

            $kraTemplates = $this->KraMaster->find('list', array('fields' => array('KraMaster.id', 'KraMaster.template_name'), 'conditions' => array('KraMaster.custom' => 0)));
            $this->set('employees_list', $employees_list);
            $this->set('band', $band);
            $this->set('company_unit_list', $company_unit_list);
            $this->set('kra_name', $kra_name);
            $this->set('kra_uom_name', $kra_uom_name);
            $this->set('mapping_array', $mapping_array);
            $this->set('kraTemplates', $kraTemplates);
            $this->set('kra_from_date', $kra_from_date);
        }
    }

    function ajax_add_kra_template($type = '') {
        if ($this->RequestHandler->isAjax()) {
            $saving_data = array();
            if ($type == 'kras') {
                $saving_data['kra_name'] = $this->data['KraTemplate']['name_new'];
                $saving_data['created_on'] = date('Y-m-d H:i:s');
                $saving_data['status'] = 'a';
                $saving_data['deleted'] = 0;
                $this->Kra->create();
                if ($this->Kra->save($saving_data)) {
                    $kra_name = $this->Kra->find('list', array('fields' => array('Kra.id', 'Kra.kra_name'),
                        'conditions' => array('Kra.deleted' => 0, 'Kra.status' => 'a'), 'order' => array('Kra.id DESC')));
                    $kra_name_options = '<option value="">-- Select Kra Name --</option>';
                    foreach ($kra_name as $key => $val) {
                        $kra_name_options .= "<option value =" . $key . ">" . $val . "</option>";
                    }
                    $message = array('SUCCESS', 'kras');
                    $response = array('message' => $message,
                        'options' => $kra_name_options);
                    echo json_encode($response);
                }
            } elseif ($type == 'kra_uom') {
                $saving_data['uom_name'] = $this->data['KraTemplate']['uom_new'];
                $saving_data['uom_unit'] = '';
                $saving_data['created_on'] = date('Y-m-d H:i:s');
                $saving_data['status'] = 'a';
                $saving_data['deleted'] = 0;
                $this->KraUom->create();
                if ($this->KraUom->save($saving_data)) {
                    $kra_uom_name = $this->KraUom->find('list', array('fields' => array('KraUom.id', 'KraUom.uom_name'),
                        'conditions' => array('KraUom.deleted' => 0, 'KraUom.status' => 'a'), 'order' => array('KraUom.id DESC')));
                    $kra_uom_name_options = '<option value="">-- Select Kra Name --</option>';
                    foreach ($kra_uom_name as $key => $val) {
                        $kra_uom_name_options .= "<option value =" . $key . ">" . $val . "</option>";
                    }
                    $message = array('SUCCESS', 'kra_uom');
                    $response = array('message' => $message,
                        'options' => $kra_uom_name_options);
                    echo json_encode($response);
                }
            }
        }
        Configure::write('debug', 0);
        $this->autoRender = false;
        exit();
    }

    function hr_index() {
        $this->layout = "kra";
        $this->Employee->recursive = -1;
        $employee_list = $this->Employee->find('all', array('fields' => array('Employee.id', 'CONCAT(Employee.employee_number,'
                . '" - ",Employee.first_name," ", Employee.last_name) as employee', 'CompanyStructure.*', 'CompanyLevel1.*',
                'CompanyLevel2.*'),
            'joins' => array(
                array(
                    'table' => 'company_structures',
                    'alias' => 'CompanyStructure',
                    'type' => 'left',
                    'conditions' => array(
                        'CompanyStructure.id = Employee.structure_name_subgroup'
                    )),
                array(
                    'table' => 'company_structures',
                    'alias' => 'CompanyLevel1',
                    'type' => 'left',
                    'conditions' => array(
                        'CompanyLevel1.id = CompanyStructure.parent_id'
                    )),
                array(
                    'table' => 'company_structures',
                    'alias' => 'CompanyLevel2',
                    'type' => 'left',
                    'conditions' => array(
                        'CompanyLevel2.id = CompanyLevel1.parent_id'
                    )
                )),
            'conditions' => array('((Employee.employment_status in ("p","e") or (Employee.employment_status ="v" and Employee.band_id not in(33,34))))')));
    }

    function ajax_kra_mapping_data($view = '') {
        if ($this->RequestHandler->isAjax()) {
            if ($view == 'unit') {
                $company_unit_list = $this->CompanyStructure->find('list', array('fields' => array('CompanyStructure.id', 'CompanyStructure.name'),
                    'conditions' => array('CompanyStructure.parent_id' => 0)));
                $message = array('SUCCESS', 'Unit');
                $response = array('message' => $message,
                    'options' => $company_unit_list);
                echo json_encode($response);
            } elseif ($view == 'band') {
                $band = $this->Band->find('list', array('fields' => array('Band.id', 'Band.name'), 'conditions' => array('Band.parent_id IS NULL', 'Band.name <> "C"')));
                $message = array('SUCCESS', 'Band');
                $response = array('message' => $message,
                    'options' => $band);
                echo json_encode($response);
            } elseif ($view == 'employee') {
                $this->Employee->recursive = -1;
                $employee_list = $this->Employee->find('all', array('fields' => array('Employee.id', 'CONCAT(Employee.employee_number," - ",Employee.first_name," ", Employee.last_name) as employee',),
                    'conditions' => array('OR' => array('Employee.employment_status' => array('p', 'e'),
                            'AND' => array('Employee.employment_status' => 'V', 'Employee.band_id Not In (33,34)')))));
                foreach ($employee_list as $employees) {
                    $employees_list[$employees['Employee']['id']] = $employees[0]['employee'];
                }
                $message = array('SUCCESS', 'Employee');
                $response = array('message' => $message,
                    'options' => $employees_list);
                echo json_encode($response);
            }
        }
        Configure::write('debug', 0);
        $this->autoRender = false;
        exit();
    }

    function sub_band_dynamic($type) {
        $this->autoRender = false;
        if ($this->RequestHandler->isAjax()) {
            $parent_id = $_POST['data'];
            if ($type == 'band') {
                $bands = $this->Band->find('list', array('fields' => array('Band.id', 'Band.name'),
                    'conditions' => array('Band.parent_id' => $parent_id), 'order' => array('Band.parent_id')));
                $message = array('SUCCESS');
                $response = array('message' => $message,
                    'options' => $bands);
                echo json_encode($response);
            } elseif ($type == 'employee') {
                $this->Employee->recursive = -1;
                $employee_list = $this->Employee->find('all', array('fields' => array('Employee.id', 'CONCAT(Employee.employee_number," - ",Employee.first_name," ", Employee.last_name) as employee',),
                    'conditions' => array('((Employee.employment_status in ("p","e") or (Employee.employment_status ="v" and Employee.band_id not in(33,34))))', 'Employee.band_id' => $parent_id)));
                foreach ($employee_list as $employees) {
                    $employees_list[$employees['Employee']['id']] = $employees[0]['employee'];
                }
                $message = array('SUCCESS');
                $response = array('message' => $message,
                    'options' => $employees_list);
                echo json_encode($response);
            }
        }
    }

    function unit_dynamic($type) {
        $this->autoRender = false;
        if ($this->RequestHandler->isAjax()) {
            $parent_id = $_POST['data'];
            if ($type == 'sbu') {
                $sbu = $this->CompanyStructure->find('list', array('fields' => array('CompanyStructure.id', 'CompanyStructure.name'),
                    'conditions' => array('CompanyStructure.parent_id' => $parent_id), 'order' => array('CompanyStructure.parent_id')));
                $message = array('SUCCESS');
                $response = array('message' => $message,
                    'options' => $sbu);
                echo json_encode($response);
            } elseif ($type == 'employee') {
                $this->Employee->recursive = -1;
                $employee_list = $this->Employee->find('all', array('fields' => array('Employee.id', 'CONCAT(Employee.employee_number," - ",Employee.first_name," ", Employee.last_name) as employee',),
                    'conditions' => array('((Employee.employment_status in ("p","e") or (Employee.employment_status ="v" and Employee.band_id not in(33,34))))', 'Employee.structure_name_subgroup' => $parent_id)));
                foreach ($employee_list as $employees) {
                    $employees_list[$employees['Employee']['id']] = $employees[0]['employee'];
                }
                $message = array('SUCCESS');
                $response = array('message' => $message,
                    'options' => $employees_list);
                echo json_encode($response);
            }
        }
    }

    function _saveEmployeeHistory($employee_id = null, $rm = null, $template = null) {

        $this->data['Employee']['id'] = $employee_id;
        $change_fields = array();
        unset($this->data['Employee']['parent_band_id']);
        unset($this->data['Employee']['company_structure_id']);
        $beforesave = $this->Employee->find('first', array('fields' => array('Employee.*'), 'conditions' => array('Employee.id' => $employee_id)));
        if ($beforesave['Employee']['manager'] != $this->data['Employee']['manager']) {
            $reason = 'manager changed';
            $insert_qry = sprintf("INSERT into employee_info_histories(employee_id,changed_by,reason,changed_from,changed_to,field_changed,modified_time,created_time) values(%d,%d,'%s','%s','%s','%s',%d,%d)", $employee_id, $this->Auth->user("employee_id"), $reason, $beforesave['Employee']['manager'], $this->data['Employee']['manager'], "manager", time(), time());
            $this->Employee->query($insert_qry);
            $change_fields[] = 'manager_id';
        }
        $this->Employee->updateAll(array('Employee.manager' => $this->data['Employee']['manager']), array('Employee.id' => $employee_id));
        $check_trigger = $this->KraRmTrigger->find('first',array('conditions'=>array('KraRmTrigger.employee_id'=>$employee_id,'KraRmTrigger.deleted'=>0,'KraRmTrigger.status'=>'o')));
            if(!empty($check_trigger)){
               $this->KraRmTrigger->updateAll(array('KraRmTrigger.status' => "'c'"), array('KraRmTrigger.employee_id' => $employee_id));
            }
        //  $this->_changeKraManger($employee_id, $change_fields, $template);
        //$this->Employee->save($this->data['Employee']);
    }

    function kra_template_mapping_list() {
        $this->layout = 'kra';
    }

    function _changeKraManger($employeeId, $change_fields, $template) {
        $existKra = $this->EmployeeKraMapping->find('first', array('conditions' => array('EmployeeKraMapping.employee_id' => $employeeId), 'order' => 'EmployeeKraMapping.id desc'));
        if ($template == 0) {
            $this->EmployeeKraMapping->updateAll(array('EmployeeKraMapping.manager_id' => $this->data['Employee']['manager']), array('EmployeeKraMapping.id' => $existKra['EmployeeKraMapping']['id']));
        } else {
            $this->EmployeeKraMapping->updateAll(array('EmployeeKraMapping.to_date' => "'" . date('Y-m-d') . "'"), array('EmployeeKraMapping.id' => $existKra['EmployeeKraMapping']['id']));
            $Kra['EmployeeKraMapping']['kra_master_id'] = $template;
            $Kra['EmployeeKraMapping']['employee_id'] = $employeeId;
            $Kra['EmployeeKraMapping']['manager_id'] = $this->data['Employee']['manager'];
            $Kra['EmployeeKraMapping']['band_id'] = $existKra['EmployeeKraMapping']['band_id'];
            $Kra['EmployeeKraMapping']['pm_id'] = $existKra['EmployeeKraMapping']['pm_id'];
            $Kra['EmployeeKraMapping']['sub_sbu_id'] = $existKra['EmployeeKraMapping']['sub_sbu_id'];
            $Kra['EmployeeKraMapping']['from_date'] = date('Y-m-d');
            $Kra['EmployeeKraMapping']['to_date'] = null;
            $Kra['EmployeeKraMapping']['year'] = date('Y');
            $Kra['EmployeeKraMapping']['reviewed_date'] = '';
            $Kra['EmployeeKraMapping']['overall_rating'] = '';
            $Kra['EmployeeKraMapping']['submitted_date'] = '';
            $Kra['EmployeeKraMapping']['agree'] = null;
            $Kra['EmployeeKraMapping']['status'] = 'o';
            if ($template != 0) {
                $existKra['EmployeeKraMapping']['kra_master_id'] = $template;
            }
            $this->EmployeeKraMapping->create();
            $this->EmployeeKraMapping->save($Kra);
        }
//        $existKra = $this->EmployeeKraMapping->find('first', array('conditions' => array('EmployeeKraMapping.employee_id' => $employeeId, 'EmployeeKraMapping.status' => array('o', 'm', 's', 'r','h')),'order'=>'EmployeeKraMapping.id desc'));
//        if ($existKra) {
//            $this->_saveKraMapping($existKra, $change_fields, 'o',$template);
//        } else {
//            $existKraclosed = $this->EmployeeKraMapping->find('first', array('conditions' => array('EmployeeKraMapping.employee_id' => $employeeId, 'EmployeeKraMapping.status' => array('c', 'a')),'order'=>'EmployeeKraMapping.id desc' ));
//            if ($existKraclosed) {
//                $this->_saveKraMapping($existKraclosed, $change_fields, 'o',$template);
//            }
//        }
    }

    function kra_template_list_datatables() {
        $this->autoRender = false;
        if ($this->RequestHandler->isAjax()) {
            $output = $this->KraMaster->template_list($_GET);
            echo json_encode($output);
        }
    }

    function _saveKraMapping($existKra, $change_fields, $status, $template) {
        $existKra['EmployeeKraMapping']['to_date'] = date('Y-m-d');
        $this->EmployeeKraMapping->save($existKra);
        unset($existKra['EmployeeKraMapping']['id']);
        foreach ($change_fields as $change_field) {
            switch ($change_field) {
                case 'band_id' :
                    $existKra['EmployeeKraMapping'][$change_field] = $this->data['Employee']['band_id'];
                    break;
                case 'manager_id' :
                    $existKra['EmployeeKraMapping'][$change_field] = $this->data['Employee']['manager'];
                    break;
                case 'sub_sbu_id' :
                    $existKra['EmployeeKraMapping'][$change_field] = $this->data['Employee']['structure_name_subgroup'];
                    break;
                default:
                    $existKra['EmployeeKraMapping'][$change_field] = $this->data['Employee'][$change_field];
            }
        }
        $existKra['EmployeeKraMapping']['from_date'] = date('Y-m-d');
        $existKra['EmployeeKraMapping']['to_date'] = null;
        $existKra['EmployeeKraMapping']['reviewed_date'] = '';
        $existKra['EmployeeKraMapping']['overall_rating'] = '';
        $existKra['EmployeeKraMapping']['submitted_date'] = '';
        $existKra['EmployeeKraMapping']['agree'] = null;
        $existKra['EmployeeKraMapping']['status'] = $status;
        if ($template != 0) {
            $existKra['EmployeeKraMapping']['kra_master_id'] = $template;
        }
        $this->EmployeeKraMapping->create();
        $this->EmployeeKraMapping->save($existKra);
    }

    function kra_mail_reportees() {

        $rm_change_request = $this->EmployeeRmChange->find('all', array('conditions' => array('EmployeeRmChange.employee_id' => $this->data['Employee']['id'], 'EmployeeRmChange.status' => 'd')));
        if (!empty($rm_change_request)) {
            echo "EXISTS";
            exit;
        }
        $employeeKraStatus = $this->EmployeeKraMapping->find('first', array('conditions' => array('EmployeeKraMapping.id' => $this->data['Employee']['kra_mapping_id'])));
        $kraCompletedStatus = array("a", "c","h","n");
        $kraPendingStatus = array("o", "s", "r");
        if (in_array($employeeKraStatus['EmployeeKraMapping']['status'], $kraCompletedStatus)) {
            echo "COMPLETED";
        } else {
            if (in_array($employeeKraStatus['EmployeeKraMapping']['status'], $kraPendingStatus)) {
                $cc_list_emp = array($this->data['Employee']['manager'], $this->data['Employee']['approver_id']); //approver_id
                Configure::load('messages');
                $subjectBody = Configure::read('KRA_EMPLOYEE_NOTIFICATION');
                $this->Email->to = $this->Employee->field('Employee.work_email_address', array('Employee.id =' => $this->data['Employee']['id']));
                $cc_list = $this->Employee->find('all', array('fields' => array('Employee.work_email_address'), 'conditions' => array('Employee.id' => $cc_list_emp)));
                $cc_email = array();
                foreach ($cc_list as $key => $emp_email) {
                    $cc_email[] = $emp_email['Employee']['work_email_address'];
                }
                $this->Email->html_body = sprintf($subjectBody['body'], $this->Employee->field('concat_ws(" ",Employee.first_name,Employee.last_name)', array('Employee.id =' => $this->data['Employee']['id'])));
                $this->Email->subject = $subjectBody['subject'];
                $this->Email->cc = $cc_email;
                //$result = $this->Email->send(true);
                echo "PENDING_EMP";
            } else {
                echo "PENDING_RM";
            }
        }
        Configure::write('debug', 0);
        $this->autoRender = false;
        exit();
    }

    function client_feedback_creation() {

        if ($this->RequestHandler->isAjax()) {
            $this->autoRender = false;
            $save_data = array();
            $encryted_token = md5($this->data['ClientFeedback']['customer_id']) . md5($this->data['ClientFeedback']['project_id']) . md5(date('Y-m-d H:i:s'));
            $save_data['customer_id'] = $this->data['ClientFeedback']['customer_id'];
            $save_data['project_id'] = !empty($this->data['ClientFeedback']['project_id']) ? $this->data['ClientFeedback']['project_id'] : '';
            $save_data['send_mail'] = $this->data['ClientFeedback']['customer_mail'];
            $save_data['token'] = $encryted_token;
            $save_data['created_by'] = $this->Auth->user("employee_id");
            $save_data['created_on'] = date('Y-m-d H:i:s');
            $save_data['deleted'] = 0;
            $this->KraClientFeedbackCreation->create();

            $get_customer_details = $this->Customer->find('all', array('fields' => array('Customer.customer_code', 'Customer.customer_name', 'Customer.division'),
                'conditions' => array('Customer.id' => $this->data['ClientFeedback']['customer_id'])));
            $customer_name = $get_customer_details[0]['Customer']['customer_name'];

            if (!empty($get_customer_details[0]['Customer']['division'])) {
                $customer_name = $customer_name . " - " . $get_customer_details[0]['Customer']['division'];
            }


            $tokenized_url = "<a href='" . BASE_PATH . "client_feedback/client_feedback_entry?token=" . $encryted_token . "'>" . BASE_PATH . "client_feedback_entry?token=" . $encryted_token . "</a>";
            $to_mail = $this->data['ClientFeedback']['customer_mail'];
            $to_name = $customer_name;
            Configure::load('messages');
            $subjectBody = Configure::read('KRA_CLIENT_FEEDBACK');
            $this->Email->to = $to_mail;
            $this->Email->cc = "ideal.mobility@hindujatech.com";
            $this->Email->html_body = sprintf($subjectBody['body'], $to_name, $tokenized_url);

            $this->Email->subject = $subjectBody['subject'];

            if ($this->KraClientFeedbackCreation->save($save_data) && $this->Email->send()) {
                echo 'SUCCESS';
            }
        } else {
            $this->layout = 'kra';
            $customers = $this->get_customer();
            $this->set('customers', $customers);
        }
    }

    function get_mail_ids($project_id) {
        if ($this->RequestHandler->isAjax()) {
            $this->autoRender = false;
            $mail_ids = $this->KraClientFeedbackCreation->find('list', array('fields' => array('KraClientFeedbackCreation.send_mail'), 'conditions' => array('KraClientFeedbackCreation.project_id' => $project_id, 'KraClientFeedbackCreation.deleted=0')));
            echo json_encode($mail_ids);
            exit();
        }
    }

    function get_customer() {
        $get_kra_frequency = $this->KraFrequency->find('first', array());
        $kra_start_date = date("Y") . "-" . $get_kra_frequency['KraFrequency']['start_month'] . "-01";
        $kra_end_date = date("Y-m-d", strtotime($kra_start_date . " +12 months -1 days"));
        $custArr = $this->Customer->find('all', array(
            'fields' => array('Customer.*'),
            'joins' => array(
                array(
                    'table' => 'sales_orders',
                    'alias' => 'SalesOrder',
                    'type' => 'INNER',
                    'conditions' => array(
                        'SalesOrder.customer_id = Customer.id'
                    )
                ),
                array(
                    'table' => 'project_so_masters',
                    'alias' => 'ProjectSoMaster',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ProjectSoMaster.sales_order_id = SalesOrder.id',
                    )
                ),
                array(
                    'table' => 'projects',
                    'alias' => 'Project',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Project.id = ProjectSoMaster.project_id',
                    )
                ),
                array(
                    'table' => 'project_team_allocations',
                    'alias' => 'ProjectTeamAllocation',
                    'type' => 'INNER',
                    'conditions' => array('ProjectTeamAllocation.project_id = Project.id')
                )),
            'conditions' => array('Customer.status ' => "a", 'Customer.deleted' => "0", 'ProjectTeamAllocation.id IS NOT NULL',
                array('OR' => array(
                        'Project.planned_start_date BETWEEN "' . $kra_start_date . '" AND "' . $kra_end_date . '"',
                        'Project.planned_end_date BETWEEN "' . $kra_start_date . '" AND "' . $kra_end_date . '"',
                        '"' . $kra_start_date . '" BETWEEN Project.planned_start_date AND Project.planned_end_date',
                        '"' . $kra_end_date . '" BETWEEN Project.planned_start_date AND Project.planned_end_date')),
                'OR' => array(
                    'ProjectTeamAllocation.start_date BETWEEN "' . $kra_start_date . '" AND "' . $kra_end_date . '"',
                    'ProjectTeamAllocation.end_date BETWEEN "' . $kra_start_date . '" AND "' . $kra_end_date . '"',
                    '"' . $kra_start_date . '" BETWEEN ProjectTeamAllocation.start_date AND ProjectTeamAllocation.end_date',
                    '"' . $kra_end_date . '" BETWEEN ProjectTeamAllocation.start_date AND ProjectTeamAllocation.end_date')
            )
            , 'order' => array('Customer.customer_name')));

        foreach ($custArr as $cust_arr) {
            $customer_name = $cust_arr['Customer']['customer_name'];

            if (!empty($cust_arr['Customer']['division']))
                $customer_name = $customer_name . " - " . $cust_arr['Customer']['division'];

            if (!empty($cust_arr['Customer']['customer_code'])) {
                $customers[$cust_arr['Customer']['id']] = $customer_name . " - " . $cust_arr['Customer']['customer_code'];
                //$customers[$cust_arr['Customer']['id'].'_division'] = $cust_arr['Customer']['division'];
            } else {
                //                                              $customers[$cust_arr['Customer']['id'].'_division'] = $cust_arr['Customer']['division'];
                $customers[$cust_arr['Customer']['id']] = $customer_name;
            }
        }
        return $customers;
    }

    function get_rm_comments($rm_master_id = '') {
        if ($this->RequestHandler->isAjax()) {
            $this->autoRender = false;
            $rm_reviews = $this->EmployeeKraMapping->find('all', array('fields' => array('EmployeeKraDetail.*', 'Kra.kra_name',
                    'CONCAT(ReportingManager.employee_number," - ",ReportingManager.first_name," ", ReportingManager.last_name) as reporting_manager'),
                'joins' => array(
                    array(
                        'table' => 'kra_masters',
                        'alias' => 'KraMaster',
                        'type' => 'INNER',
                        'conditions' => array('KraMaster.id = EmployeeKraMapping.kra_master_id')
                    ),
                    array(
                        'table' => 'employee_kra_details',
                        'alias' => 'EmployeeKraDetail',
                        'type' => 'INNER',
                        'conditions' => array('EmployeeKraDetail.employee_kra_mapping_id = EmployeeKraMapping.id')
                    ),
                    array(
                        'table' => 'kra_templates',
                        'alias' => 'KraTemplate',
                        'type' => 'LEFT',
                        'conditions' => array('KraTemplate.id = EmployeeKraDetail.kra_template_id')
                    ),
                    array(
                        'table' => 'kras',
                        'alias' => 'Kra',
                        'type' => 'LEFT',
                        'conditions' => array('KraTemplate.kra_id = Kra.id')
                    ),
                    array(
                        'table' => 'employees',
                        'alias' => 'Employee',
                        'type' => 'LEFT',
                        'conditions' => array('Employee.id = EmployeeKraMapping.employee_id')
                    ),
                    array(
                        'table' => 'employees',
                        'alias' => 'ReportingManager',
                        'type' => 'LEFT',
                        'conditions' => array('ReportingManager.id = EmployeeKraMapping.manager_id')
                    ),
                    array(
                        'table' => 'bands',
                        'alias' => 'Band',
                        'type' => 'LEFT',
                        'conditions' => array('EmployeeKraMapping.band_id = Band.id')
                    ),
                    array(
                        'table' => 'kra_frequencies',
                        'alias' => 'KraFrequency',
                        'type' => 'LEFT',
                        'conditions' => array('KraFrequency.id = KraMaster.frequency_id')
                    ),
                ),
                'conditions' => array('EmployeeKraMapping.id' => $rm_master_id)
                    )
            );
            $this->log($rm_reviews);
            echo json_encode($rm_reviews);
            exit();
        }
    }

    function rm_approve($request_id = null) {
        $this->layout = "kra";
        $EmployeeRmChange = $this->EmployeeRmChange->find('all', array('fields' => array('EmployeeRmChange.id', 'EmployeeRmChange.employee_id', 'EmployeeRmChange.*',
                'CONCAT(RequestedBy.employee_number," - ",RequestedBy.first_name," ", RequestedBy.last_name) as requested_by',
                'CONCAT(RequestedOn.employee_number," - ",RequestedOn.first_name," ", RequestedOn.last_name) as requested_on',
                'CONCAT(ChangedFrom.employee_number," - ",ChangedFrom.first_name," ", ChangedFrom.last_name) as changed_from',
                'CONCAT(ApprovedBy.employee_number," - ",ApprovedBy.first_name," ", ApprovedBy.last_name) as approved_by',
                'CONCAT(ChangedTo.employee_number," - ",ChangedTo.first_name," ", ChangedTo.last_name) as changed_to'),
            'joins' => array(
                array(
                    'table' => 'employees',
                    'alias' => 'RequestedBy',
                    'type' => 'INNER',
                    'conditions' => array('EmployeeRmChange.changed_by = RequestedBy.id')
                ),
                array(
                    'table' => 'employees',
                    'alias' => 'RequestedOn',
                    'type' => 'INNER',
                    'conditions' => array('EmployeeRmChange.employee_id = RequestedOn.id')
                ),
                array(
                    'table' => 'employees',
                    'alias' => 'ChangedFrom',
                    'type' => 'INNER',
                    'conditions' => array('EmployeeRmChange.changed_from = ChangedFrom.id')
                ),
                array(
                    'table' => 'employees',
                    'alias' => 'ChangedTo',
                    'type' => 'INNER',
                    'conditions' => array('EmployeeRmChange.changed_to = ChangedTo.id')
                ),
                array(
                    'table' => 'employees',
                    'alias' => 'ApprovedBy',
                    'type' => 'INNER',
                    'conditions' => array('EmployeeRmChange.approved_by = ApprovedBy.id')
                ),
            ),
            'conditions' => array('EmployeeRmChange.deleted' => 0, 'EmployeeRmChange.changed_to' => $this->Auth->user('employee_id'), 'EmployeeRmChange.status' => 'm')));

        $this->set('EmployeeRmChange', $EmployeeRmChange);
        $emp_change = $last_promotion = $last_rating = $work_experience = array();
        $last_promotion_date = '-';
        foreach ($EmployeeRmChange as $key => $details) {
            $emp_change[] = $details['EmployeeRmChange']['employee_id'];
        }
        foreach ($emp_change as $key => $emp_id) {
            $employee_detail[$emp_id] = $this->Employee->find('first', array('fields' => array('CONCAT(Manager.employee_number," - ",Manager.first_name," ", Manager.last_name) as manager_name',
                    'CONCAT(Employee.employee_number," - ",Employee.first_name," ", Employee.last_name) as employee_name',
                    'Designation.*', 'Employee.*', 'Band.*', 'CompanyStructure.*', 'CompanyStructureGroup.*', 'Country.*'),
                'conditions' => array('Employee.id' => $emp_id)));
            $work_experience[$emp_id] = $this->get_employee_work_experience($emp_id);
            $last_rating[$emp_id] = $this->get_last_rating($emp_id);
            $fields_changed = array('Designation', 'Sub-Band', 'Band');
            $emp_last_promotion = $this->EmployeeInfoHistory->find('first', array('fields' => array('EmployeeInfoHistory.*'), 'conditions' => array('EmployeeInfoHistory.employee_id' => $emp_id, 'EmployeeInfoHistory.field_changed' => $fields_changed), 'order' => array('EmployeeInfoHistory.id DESC')));
            if ($emp_last_promotion['EmployeeInfoHistory']['field_changed'] == "Designation") {
                $last_promotion[$emp_id] = $this->Designation->field('Designation.designation', array('Designation.id =' => $emp_last_promotion['EmployeeInfoHistory']['changed_from']));
            }
            if (!empty($emp_last_promotion)) {
                $last_promotion_date[$emp_id] = date('d-m-Y', $emp_last_promotion['EmployeeInfoHistory']['modified_time']);
            }
        }
        $kraTemplates = $this->KraMaster->find('list', array('fields' => array('KraMaster.id', 'KraMaster.template_name'), 'conditions' => array('KraMaster.custom' => 0)));
        $this->set('kraTemplates', $kraTemplates);
        $this->set('last_promotion', $last_promotion_date);
        $this->set('last_rating', $last_rating);
        $this->set('work_experience', $work_experience);
        $this->set('employee_detail', $employee_detail);

        if ((!empty($request_id)) || (!empty($this->data))) {

            $employeeId = $this->EmployeeRmChange->field('EmployeeRmChange.employee_id', array('EmployeeRmChange.id' => $request_id));
            $existKra = $this->EmployeeKraMapping->find('first', array('conditions' => array('EmployeeKraMapping.employee_id' => $employeeId), 'order' => 'EmployeeKraMapping.id desc'));
            $this->EmployeeKraMapping->updateAll(array('EmployeeKraMapping.to_date' => "'" . date('Y-m-d') . "'"), array('EmployeeKraMapping.id' => $existKra['EmployeeKraMapping']['id']));
            $Kra['EmployeeKraMapping']['kra_master_id'] = $template;
            $Kra['EmployeeKraMapping']['employee_id'] = $employeeId;
            $Kra['EmployeeKraMapping']['manager_id'] = $this->EmployeeRmChange->field('EmployeeRmChange.changed_to', array('EmployeeRmChange.id' => $request_id));
            $Kra['EmployeeKraMapping']['band_id'] = $existKra['EmployeeKraMapping']['band_id'];
            $Kra['EmployeeKraMapping']['pm_id'] = $existKra['EmployeeKraMapping']['pm_id'];
            $Kra['EmployeeKraMapping']['sub_sbu_id'] = $existKra['EmployeeKraMapping']['sub_sbu_id'];
            $Kra['EmployeeKraMapping']['from_date'] = date('Y-m-d');
            $Kra['EmployeeKraMapping']['to_date'] = null;
            $Kra['EmployeeKraMapping']['year'] = date('Y');
            $Kra['EmployeeKraMapping']['reviewed_date'] = '';
            $Kra['EmployeeKraMapping']['overall_rating'] = '';
            $Kra['EmployeeKraMapping']['submitted_date'] = '';
            $Kra['EmployeeKraMapping']['agree'] = null;
            $Kra['EmployeeKraMapping']['status'] = 'o';
            if (!empty($this->data['RmApprove']['template_id'])) {
                $Kra['EmployeeKraMapping']['kra_master_id'] = $this->data['RmApprove']['template_id'];
                $message = 'New KRA Applied';
            } else {
                $Kra['EmployeeKraMapping']['kra_master_id'] = $existKra['EmployeeKraMapping']['kra_master_id'];
                $message = 'Same KRA Applied';
            }
            $this->EmployeeKraMapping->create();
            $this->EmployeeKraMapping->save($Kra);
            $this->EmployeeRmChange->updateAll(array('EmployeeRmChange.status' => "'a'"), array('EmployeeRmChange.id' => $request_id));
            echo $message; // do not remove
            Configure::write('debug', 0);
            $this->autoRender = false;
            $this->layout = false;
            exit();
        }
    }

    function get_employee_work_experience($employeeId) {
        $years = array();
        $years['other'] = 0;
        $emp_work_exp = $this->DtlEmployeeWorkexperience->find('all', array('fields' => array('MIN(DtlEmployeeWorkexperience.start_date) as start_date', 'MAX(DtlEmployeeWorkexperience.end_date) as end_date'), 'conditions' => array('DtlEmployeeWorkexperience.employee_id' => $employeeId)));
        $emp_work_exp_end = $this->DtlEmployeeWorkexperience->query("SELECT * FROM dtl_employee_workexperience WHERE employee_id=".$employeeId." AND "
                                        . " start_date=(SELECT MAX(start_date) FROM  dtl_employee_workexperience "
                                        . "WHERE employee_id=".$employeeId.")");

        if (empty($emp_work_exp_end[0]['dtl_employee_workexperience']['end_date']) || $emp_work_exp_end[0]['dtl_employee_workexperience']['end_date'] == '') {
            $prev_end_date = $this->Employee->field('Employee.joined_date', array('Employee.id' => $employeeId));
        } else {
            $prev_end_date = $emp_work_exp[0][0]['end_date'];
        }
        if (!empty($emp_work_exp[0][0]['start_date'])) {
            $start = new DateTime($emp_work_exp[0][0]['start_date']);
            $end = new DateTime($prev_end_date);
            $days = round(abs($end->format('U') - $start->format('U')) / (60 * 60 * 24));
            $other_years = floor($days / 365);
            $other_months = floor(($days - ($other_years * 365))/30.5);
            $years['other'] = $other_years." Years ".$other_months." Months";
        }
        $emp_htl_joined_date = $this->Employee->field('Employee.joined_date', array('Employee.id' => $employeeId));
        $htl_start = new DateTime($emp_htl_joined_date);
        $htl_end = new DateTime();
        $htl_days = round(($htl_end->format('U') - $htl_start->format('U')) / (60 * 60 * 24));
        $htl_years = floor($htl_days / 365);
        $htl_months = floor(($htl_days - ($htl_years * 365))/30.5);
        $years['htl'] = $htl_years." Years ".$htl_months." Months";
        $total_yrs  = $other_years + $htl_years;
        $total_months = $other_months + $htl_months;
        $remaining_months = $total_months % 12;
        if($total_months >= 12 && $remaining_months >= 0){
            $total_yrs = $total_yrs + 1;
        }
        $years['total'] = $total_yrs." Years ".$remaining_months." Months";
        return $years;
    }

    function get_last_rating($employeeId) {
        $get_kra_frequency = $this->KraFrequency->find('first', array());
        if (date('m') < $get_kra_frequency['KraFrequency']['start_month']) {
            $kra_year = date('Y');
        } else {
            $kra_year = date("Y",strtotime("+1 year"));
        }
        $aa_details = $this->AaEligibleAssociate->field('AES_DECRYPT(normalisedReviewerRating,appraiseeId)', array('appraiseeId' => $employeeId, 'YEAR' => $kra_year));
        if (empty($aa_details)) {
            $ca_details = $this->CaEligibleAssociate->find('first', array('fields' => array('AES_DECRYPT(normalisedReviewerRating,appraiseeId) as rating'), 'conditions' => array('appraiseeId' => $employeeId), 'order' => array('qpdId DESC')));
            if (empty($ca_details)) {
                $last_kra = $this->EmployeeKraNormalization->field('normalizer_rating', array('employee_id' => $employeeId, 'YEAR' => $kra_year));
                $last_rating = $last_kra;
            } else {
                $last_rating = $ca_details[0]['rating'];
            }
        } else {
            $last_rating = $aa_details;
        }
        return $last_rating;
    }

    function get_transfer_approvers($employee_id) {
        $reporting_managers = $this->Employee->find('all', array('fields' => array('Employee.id', 'Employee.employee_number', 'Employee.first_name', 'Employee.last_name',
                'RM1.id', 'RM1.employee_number', 'RM1.first_name', 'RM1.last_name',
                'RM2.id', 'RM2.employee_number', 'RM2.first_name', 'RM2.last_name'),
            'joins' => array(
                array(
                    'table' => 'employees',
                    'alias' => 'RM1',
                    'type' => 'INNER',
                    'conditions' => array('RM1.id = Employee.manager')
                ),
                array(
                    'table' => 'employees',
                    'alias' => 'RM2',
                    'type' => 'INNER',
                    'conditions' => array('RM2.id = RM1.manager')
                ),
            ),
            'conditions' => array('Employee.id' => $employee_id),
            'order' => array('Employee.first_name'))
        );
        foreach ($reporting_managers as $key => $empDetails) {
            $approvers[$empDetails['RM1']['id']] = $empDetails['RM1']['employee_number'] . ' - ' . $empDetails['RM1']['first_name'] . ' ' . $empDetails['RM1']['last_name'];
            $approvers[$empDetails['RM2']['id']] = $empDetails['RM2']['employee_number'] . ' - ' . $empDetails['RM2']['first_name'] . ' ' . $empDetails['RM2']['last_name'];
        }
        return $approvers;
    }

    function template_view($request_id) {
        if (isset($_POST['template_id'])) {
            $template_id = $_POST['template_id'];
        } else {
            $EmployeeRmChange = $this->EmployeeRmChange->find('first', array('conditions' => array('EmployeeRmChange.id' => $request_id)));
            $empTemplate = $this->EmployeeKraMapping->find('first', array('conditions' => array('EmployeeKraMapping.employee_id' => $EmployeeRmChange['EmployeeRmChange']['employee_id']))); //,'manager_id'=>$EmployeeRmChange['EmployeeRmChange']['changed_from']
            $template_id = $empTemplate['EmployeeKraMapping']['kra_master_id'];
        }

        $templateDetails = $this->KraMaster->find('all', array(
            'fields' => array('KraMaster.*', 'KraTemplate.*', 'Kra.*', 'KraUom.*'),
            'joins' => array(
                array(
                    'table' => 'kra_templates',
                    'alias' => 'KraTemplate',
                    'type' => 'LEFT',
                    'conditions' => array('KraTemplate.kra_master_id = KraMaster.id')
                ),
                array(
                    'table' => 'kras',
                    'alias' => 'Kra',
                    'type' => 'LEFT',
                    'conditions' => array('KraTemplate.kra_id = Kra.id')
                ),
                array(
                    'table' => 'kra_uoms',
                    'alias' => 'KraUom',
                    'type' => 'LEFT',
                    'conditions' => array('KraTemplate.uom_id = KraUom.id')
                )
            ),
            'conditions' => array('KraMaster.id' => $template_id)
        ));
        $this->set('emp_kra_details', $templateDetails);
        $target_visibility = false;
        $kra_target = $this->KraTemplate->find('all', array('conditions' => array('KraTemplate.kra_master_id' => $template_id, 'KraTemplate.uom_id != ""')));
        if (!empty($kra_target)) {
            $target_visibility = true;
        }
        $this->set('target_visibility', $target_visibility);
        Configure::write('debug', 0);
    }

    function employee_file_upload() {
//        echo WEB_ROOT_DIRECTORY;exit;
        $this->autoRender = false;
        Configure::write('debug', 0);
        mysql_query("INSERT INTO temp(message) values('" . serialize($_FILES) . "')");
        if ($_FILES['Upload']['size'] > PROJECT_ATTACHMENT_SIZE || $_FILES['Upload']['size'] == 0 || $_FILES['Upload']['error'] != 0) {
            echo "error";
        } else {
            $resFile = Sanitize::paranoid($_FILES['Upload']['name']);
            $ext = strtolower(end(explode('.', $_FILES['Upload']['name'])));
            $file = date('YmdHis');
            $filename = substr($resFile, 0, -(strlen($ext))) . '_' . $file . '.' . $ext;
            if (move_uploaded_file($_FILES['Upload']['tmp_name'], WEB_ROOT_DIRECTORY . 'kra_uploads/' . $filename))
                echo $filename;
            else
                echo $filename; //@todo handle later
        }
        exit;
    }

    function remove_upload() {
        $this->autoRender = false;
        Configure::write('debug', 0);
        $path_to_file = WEB_ROOT_DIRECTORY . 'kra_uploads/';
        $old = getcwd(); // Save the current directory
        chdir($path_to_file);
        unlink($_POST['name']);
        chdir($old);
        if ($this->KraUpload->updateAll(array('KraUpload.deleted' => "1"), array('KraUpload.attachement_name' => $_POST['name'])))
            echo 'SUCCESS';
    }

    function reviewer_comments() {
        $this->layout = 'kra';
        $this->Employee->recursive=-1;
        $get_kra_frequency = $this->KraFrequency->find('first', array());
            if (date('m') < $get_kra_frequency['KraFrequency']['start_month']) {
                $kra_year = date('Y');
            } else {
                $kra_year = date("Y",strtotime("+1 year"));
            }
        $normailzer_name=$this->Employee->find('all',array('fields'=>array( 'Normalizer.id', 'Normalizer.employee_number', 'Normalizer.first_name', 'Normalizer.last_name','HeadConfiguration.*'),
            'joins' => array(
                array(
                    'table' => 'employees',
                    'alias' => 'Normalizer',
                    'type' => 'INNER',
                    'conditions' => array('Normalizer.id = Employee.manager','Normalizer.designation_id !='.Configure::read('CEO.designation_id'))
                ),
                array(
                    'table' => 'head_configurations',
                    'alias' => 'HeadConfiguration',
                    'type' => 'INNER',
                    'conditions' => array('HeadConfiguration.employee_id = Normalizer.id','Employee.structure_name_subgroup=HeadConfiguration.sub_sbu','HeadConfiguration.head_structure_id in (1,2)')
                )
                ),
            'conditions'=>array('Employee.id'=>$this->Auth->user("employee_id"))));
        $employeeManagerEntrieList = $this->_employeeRecursive($this->Auth->user("employee_id"));
        $reporting_managers = $this->EmployeeKraMapping->find('all', array('fields' => array('Employee.id', 'Employee.employee_number', 'Employee.first_name', 'Employee.last_name',
            ),
            'joins' => array(
                array(
                    'table' => 'employees',
                    'alias' => 'Employee',
                    'type' => 'INNER',
                    'conditions' => array('EmployeeKraMapping.employee_id = Employee.id','EmployeeKraMapping.status'=>'a')
                ),
                array(
                    'table' => 'employee_kra_normalizations',
                    'alias' => 'EmployeeKraNormalization',
                    'type' => 'LEFT',
                    'conditions' => array('(EmployeeKraNormalization.employee_id = Employee.id)','EmployeeKraMapping.id = EmployeeKraNormalization.employee_kra_mapping_id')
                ),
            ),
            'conditions' => array( 'EmployeeKraNormalization.employee_id IS NULL',
                '((Employee.employment_status in ("p","e") or (Employee.employment_status ="v" and Employee.band_id not in(33,34))))',
                'Employee.id' => $employeeManagerEntrieList
            ),
            'order' => array('Employee.first_name'))
        );
        $normalizer_id = !empty($normailzer_name[0]['Normalizer']['id']) ? $normailzer_name[0]['Normalizer']['id'] : $this->Auth->user('employee_id');
        $approvers = array();
        foreach ($reporting_managers as $key => $empDetails) {
            $approvers[$empDetails['Employee']['id']]['name'] = $empDetails['Employee']['employee_number'] . ' - ' . $empDetails['Employee']['first_name'] . ' ' . $empDetails['Employee']['last_name'];
        }
        foreach ($approvers as $employee_id => $employee_name) {
            $kra_mapping_id = array();
            $kra_details = $this->EmployeeKraMapping->find('all', array(
                'conditions' => array('EmployeeKraMapping.employee_id' => $employee_id, 'EmployeeKraMapping.status' => 'a','EmployeeKraMapping.year'=>$kra_year),
                'order' => 'EmployeeKraMapping.id'));
            if (!empty($kra_details)) {
                $manager_id=array();
                if (sizeof($kra_details) > 1) {
                    foreach ($kra_details as $key => $kra_data) {
                        $get_kra_frequency = $this->KraFrequency->find('first', array());
                        $kra_from_date = $kra_data['EmployeeKraMapping']['year'] . "-" . $get_kra_frequency['KraFrequency']['start_month'] . "-01";
                        $kra_last_date = date("Y-m-d", strtotime($kra_from_date . " +12 months -1 days"));
                        $dates_in_range = $this->Common->date_range($kra_from_date, $kra_last_date);
                        $kra_freq = count($dates_in_range);
                        $kra_duration = $this->Common->date_range($kra_data['EmployeeKraMapping']['from_date'], $kra_data['EmployeeKraMapping']['to_date']);
                        $kra_days = count($kra_duration);
                        $rate += ($kra_data['EmployeeKraMapping']['overall_rating'] / $kra_freq) * $kra_days;
                        $kra_mapping_id = $kra_data['EmployeeKraMapping']['id'];
                        $manager_id[] = $kra_data['EmployeeKraMapping']['manager_id'];
                    }
                    $overall_rating = round($rate);
                } else {
                    $kra_mapping_id = $kra_details[0]['EmployeeKraMapping']['id'];
                    $overall_rating = $kra_details[0]['EmployeeKraMapping']['overall_rating'];
                    $manager_id[] =  $kra_details[0]['EmployeeKraMapping']['manager_id'];
                }
                $manager_id = implode(',', $manager_id);
                $this->data['EmployeeKraNormalization']['employee_kra_mapping_id'] = $kra_mapping_id;
                $this->data['EmployeeKraNormalization']['manager_ids'] = $manager_id;
                $this->data['EmployeeKraNormalization']['employee_id'] = $employee_id;
                $this->data['EmployeeKraNormalization']['normalized_rating'] = $overall_rating;
                $this->data['EmployeeKraNormalization']['year'] = $kra_year;
                $this->data['EmployeeKraNormalization']['reviewer_id'] = $this->Auth->user('employee_id');
                $this->data['EmployeeKraNormalization']['normalizer_id'] = $normalizer_id;
                $this->data['EmployeeKraNormalization']['status'] = 'a';
                $this->data['EmployeeKraNormalization']['deleted'] = 0;
                $this->EmployeeKraNormalization->create();
                $this->EmployeeKraNormalization->save($this->data['EmployeeKraNormalization']);
            }
        }
        $normalization_employee_list = $this->EmployeeKraNormalization->find('all', array(
            'fields' => array('CONCAT(Employee.employee_number," - ",Employee.first_name," ", Employee.last_name) as employee_name', 'EmployeeKraNormalization.*'
            ),
            'joins' => array(
                array(
                    'table' => 'employees',
                    'alias' => 'Employee',
                    'type' => 'INNER',
                    'conditions' => array('EmployeeKraNormalization.employee_id = Employee.id')
                )),
            'conditions' => array('EmployeeKraNormalization.reviewer_id' => $this->Auth->user('employee_id'), 'EmployeeKraNormalization.deleted' => 0, 'EmployeeKraNormalization.status' => array('a', 'q'))
        ));
        $index = 0;
        foreach ($normalization_employee_list as $managers) {
            $manager_id_list = explode(',', $managers['EmployeeKraNormalization']['manager_ids']);
            $managers_list = $this->Employee->find('all', array('fields' => array('CONCAT(Employee.employee_number," - ",Employee.first_name," ", Employee.last_name) as employee_name'
                ), 'conditions' => array('Employee.id' => $manager_id_list)));
            $managers_list_name = array();
            foreach ($managers_list as $manager_name) {
                $managers_list_name[] = $manager_name[0]['employee_name'];
            }
            $managers_name_list = implode(',', $managers_list_name);
            $normalization_employee_list[$index++]['manager'] = $managers_name_list;
        }
        for ($rating_count = 1; $rating_count <= KraMaster::RATING_MAX_LIMIT; $rating_count++) {
            $ratings[$rating_count] = $rating_count;
        }
        $this->set('ratings', $ratings);
        $this->set('normalization_employee_list', $normalization_employee_list);
        $this->set('reviewer_page', true);
    }

    function notify_hr($request_id,$request_type) {
        $this->autoRender = false;
        $EmployeeRmChange = $this->EmployeeRmChange->find('first', array('conditions' => array('EmployeeRmChange.id' => $request_id)));
        $cc_emp = array();
        if($request_type == 1){
            $this->EmployeeRmChange->updateAll(array('EmployeeRmChange.status' => "'d'"), array('EmployeeRmChange.id' => $request_id));
            echo "Your disagreement on rating by previous RM sent to HR";
            $message ="There is a dis agreement by";
            $subject = "REG: KRA RM Rating Dis-agreement";
        }
        else if($request_type == 2){
//            $this->EmployeeRmChange->updateAll(array('EmployeeRmChange.status' => "'n'"), array('EmployeeRmChange.id' => $request_id));
            echo "New Template Request sent to HR!!!";
            $message = "New template request raised by ";
            $subject = "REG: KRA New Template Creation Request";
        }
                Configure::load('messages');
                $subjectBody = Configure::read('KRA_HR_NOTIFICATION');
                $hr_dl = $this->_getHrMailList();
                $employee_number = $this->Employee->field('Employee.employee_number', array('Employee.id =' => $EmployeeRmChange['EmployeeRmChange']['employee_id']));
                $employeeName = $this->Employee->field('CONCAT(Employee.first_name," ",Employee.last_name)', array('Employee.id =' => $EmployeeRmChange['EmployeeRmChange']['employee_id']));
                        
                $this->Email->to = $hr_dl;
                $this->Email->html_body = sprintf($subjectBody['body'],'Team',$message,$employee_number, $employeeName);
                $this->Email->subject = sprintf($subjectBody['subject'],$subject);
//                $this->Email->cc = $this->Employee->field('Employee.work_email_address', array('Employee.id =' => $EmployeeRmChange['EmployeeRmChange']['changed_to']));
                $result = $this->Email->send();
                Configure::write('debug', 0);
                exit();
        //to do email part
        
    }

    function normalizer_comments() {
        $this->layout = 'kra';
        $normalization_employee_list = $this->EmployeeKraNormalization->find('all', array(
            'fields' => array('CONCAT(Employee.employee_number," - ",Employee.first_name," ", Employee.last_name) as employee_name', 'EmployeeKraNormalization.*',
                'CONCAT(EmployeeReviewer.employee_number," - ",EmployeeReviewer.first_name," ", EmployeeReviewer.last_name) as reviewer_name'
            ),
            'joins' => array(
                array(
                    'table' => 'employees',
                    'alias' => 'Employee',
                    'type' => 'INNER',
                    'conditions' => array('EmployeeKraNormalization.employee_id = Employee.id')
                ),
                array(
                    'table' => 'employees',
                    'alias' => 'EmployeeReviewer',
                    'type' => 'INNER',
                    'conditions' => array('EmployeeKraNormalization.normalizer_id = EmployeeReviewer.id')
                )),
            'conditions' => array('EmployeeKraNormalization.normalizer_id' => $this->Auth->user('employee_id'), 'EmployeeKraNormalization.status' => array('h', 'p'), 'EmployeeKraNormalization.deleted' => 0)
        ));
        $index = 0;
        foreach ($normalization_employee_list as $managers) {
            $manager_id_list = explode(',', $managers['EmployeeKraNormalization']['manager_ids']);
            $managers_list = $this->Employee->find('all', array('fields' => array('CONCAT(Employee.employee_number," - ",Employee.first_name," ", Employee.last_name) as employee_name'
                ), 'conditions' => array('Employee.id' => $manager_id_list)));
            $managers_list_name = array();
            foreach ($managers_list as $manager_name) {
                $managers_list_name[] = $manager_name[0]['employee_name'];
            }
            $managers_name_list = implode(',', $managers_list_name);
            $normalization_employee_list[$index++]['manager'] = $managers_name_list;
        }
        for ($rating_count = 1; $rating_count <= KraMaster::RATING_MAX_LIMIT; $rating_count++) {
            $ratings[$rating_count] = $rating_count;
        }
        $this->set('ratings', $ratings);
        $this->set('normalization_employee_list', $normalization_employee_list);
        $this->set('normalizer_page', true);
    }

    function save_normalization($type, $page_type) {
        $this->autoRender = false;
        Configure::write('debug', 0);
        if ($this->RequestHandler->isAjax()) {
            $save_feedback = array();
            $get_kra_frequency = $this->KraFrequency->find('first', array());
            if (date('m') > $get_kra_frequency['KraFrequency']['start_month']) {
                    $kra_year = date('Y');
                } else {
                    $kra_year = date("Y",strtotime("+1 year"));
                }
            if ($page_type == 'Normalizer') {
                foreach ($this->data['EmployeeKraNormalization'] as $id => $ratings) {
                    if (in_array($id, $this->data['selected_employee_id'])) {
                        $save_feedback['id'] = $id;
                        $save_feedback['normalizer_rating'] = $ratings['rating'];
                        $save_feedback['normalizer_comment'] = $ratings['comments'];
                        $save_feedback['normalized_on'] = date('Y-m-d H:i:s');
                        $save_feedback['status'] = ($type == 'submit') ? 'n' : 'p';
                        if ($this->EmployeeKraNormalization->save($save_feedback))
                            $flag = true;
                        if ($type == 'submit') {
                            $this->EmployeeKraMapping->updateAll(array('EmployeeKraMapping.status' => "'n'"), array('EmployeeKraMapping.employee_id' => $ratings['employee_id'],'EmployeeKraMapping.year'=>$kra_year));
                        }
                    }
                }
            } else if ($page_type == 'Reviewer') {
                foreach ($this->data['EmployeeKraNormalization'] as $id => $ratings) {
                    if (in_array($id, $this->data['selected_employee_id'])) {
                        $normalizer_employee_id = $this->EmployeeKraNormalization->find('list', array('conditions' => array('EmployeeKraNormalization.reviewer_id=EmployeeKraNormalization.normalizer_id', 'EmployeeKraNormalization.id' => $id)));
                        $save_feedback['id'] = $id;
                        $save_feedback['reviewer_rating'] = $ratings['rating'];
                        $save_feedback['reviewer_comment'] = $ratings['comments'];
                        if (!empty($normalizer_employee_id)) {
                            $save_feedback['normalizer_rating'] = $ratings['rating'];
                            $save_feedback['normalizer_comment'] = 'Auto Approval';
                            $save_feedback['status'] = ($type == 'submit') ? 'n' : 'p';
                            $save_feedback['normalized_on'] = date('Y-m-d H:i:s');
                        } else {
                            $save_feedback['status'] = ($type == 'submit') ? 'h' : 'q';
                        }
                        $save_feedback['reviewed_on'] = date('Y-m-d H:i:s');
                        if ($this->EmployeeKraNormalization->save($save_feedback))
                            $flag = true;
                        if ($type == 'submit') {
                            $this->EmployeeKraMapping->updateAll(array('EmployeeKraMapping.status' => "'h'"), array('EmployeeKraMapping.employee_id' => $ratings['employee_id'],'EmployeeKraMapping.year'=>$kra_year));
                        }
                    }
                }
            }
            if ($flag) {
                echo 'SUCCESS';
            }
        }
    }

    function template_view_hr() {
        $this->layout = "kra";
    }

    function template_view_datatables($view) {
        $this->autoRender = false;
        if ($this->RequestHandler->isAjax()) {
            $mapping_status = $this->ConfigurationValue->find('list', array('fields' => array('Configuration_inself.configuration_key', 'Configuration_inself.configuration_value'),
                'joins' => array(array(
                        'table' => 'configuration_values',
                        'alias' => 'Configuration_inself',
                        'type' => 'INNER',
                        'conditions' => array('Configuration_inself.parent_id = ConfigurationValue.id')
                    )),
                'conditions' => array('ConfigurationValue.configuration_value' => 'template_status'
                ))
            );
            $output = $this->KraMaster->template_list($_GET, $mapping_status);
            echo json_encode($output);
        }
    }
    
    function _kra_from_date($year){
        $get_kra_frequency = $this->KraFrequency->find('first', array());
        $mapping_end_month = $get_kra_frequency['KraFrequency']['month_interval'];
        $from_date = $year . "-" . $get_kra_frequency['KraFrequency']['start_month'] . "-01";
        $to_date = date('Y-m-d', strtotime("+$mapping_end_month months -1 days", strtotime($from_date)));
        $current_date = date('Y-m-d');
        if (strtotime($from_date) <= strtotime($current_date) && strtotime($to_date) >= strtotime($current_date)) {
            $kra_from_date = $from_date;
        } else {
            $ts1 = strtotime($from_date);
            $ts2 = strtotime($current_date);

            $year1 = date('Y', $ts1);
            $year2 = date('Y', $ts2);

            $month1 = date('m', $ts1);
            $month2 = date('m', $ts2);

            $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
            if ($diff > 1) {
                $mapping_month = (floor(($diff / $mapping_end_month)) * $mapping_end_month) + $get_kra_frequency['KraFrequency']['start_month'];
                $kra_from_date = date("Y") . "-" . $mapping_month . "-01";
            } else if ($diff <= 1) {
                $kra_from_date = $from_date;
            }
        }
        return $kra_from_date;
    }

    function _employeeRecursive($parent, $output = null) {
		if ($parent != "") {
			$companystruct = $this->Employee->query(" select Employee.id from employees as Employee where Employee.manager in ($parent) and ((Employee.employment_status in ('p','e') or (Employee.employment_status ='v' and Employee.band_id not in(33,34)))) and structure_name_subgroup != ".Configure::read('CompanyStructure.CRMG')."");
			if (count($companystruct)) {
				$parent_ids = array();
				foreach ($companystruct as $companystructkey) {
					//print_R($companystruct);
					$output[] = $companystructkey['Employee']['id'];
					$parent_ids[] = $companystructkey['Employee']['id'];
					$z = array_unique($parent_ids);
				}
				if (count($parent_ids)) {
					$output = $this->employeeManagerRecursive(implode(",", $parent_ids), $output);
				}
				return $output;
			} else {
				return $output;
				exit;
			}
		}
	}
        
        function _getHrMailList(){
            $mail_list = $this->DlFunctionMaster->find('all',array(
                                    'fields' =>array('DlFunctionMaster.*,DlMaster.*'),
                                    'joins' => array( 
                                                        array(
                                                            'table' => 'dl_master',
                                                            'alias' => 'DlMaster',
                                                            'type' => 'LEFT',
                                                            'conditions' => array('DlMaster.id = DlFunctionMaster.dl_id')
                                                        ),
                                                     array(
                                                            'table' => 'function_names',
                                                            'alias' => 'FuntionName',
                                                            'type' => 'LEFT',
                                                            'conditions' => array('DlFunctionMaster.function_id = FuntionName.id')
                                                        )
                                                        ),
                                    'conditions' => array('FuntionName.name ="kra_hr_mail_triggers"')
                            ));
                $hr_dl = array();
               foreach($mail_list as $key => $details){
                   $hr_dl[] = $details['DlMaster']['dl_mail'];
               }
               return $hr_dl;
        }
        
        function kra_rm_trigger($mappingId){
            $this->autoRender = false;
            if ($this->RequestHandler->isAjax()) {
                $flag=0;
                $kra_mapping =  $this->EmployeeKraMapping->find('first', array('conditions' => array('EmployeeKraMapping.id' => $mappingId, 'EmployeeKraMapping.deleted' => 0)));
                $check_trigger = $this->KraRmTrigger->find('first',array('conditions'=>array('KraRmTrigger.employee_id'=>$kra_mapping['EmployeeKraMapping']['employee_id'],'KraRmTrigger.deleted'=>0,'KraRmTrigger.status'=>'o')));
                if(!empty($check_trigger)){
                    //delete existing
                    $this->KraRmTrigger->updateAll(array('KraRmTrigger.deleted' => "1"), array('KraRmTrigger.employee_id' => $employee_id));
                }
                $rm_trigger['employee_id'] = $kra_mapping['EmployeeKraMapping']['employee_id'];
                $rm_trigger['triggered_by'] = $kra_mapping['EmployeeKraMapping']['manager_id'];
                $rm_trigger['kra_mapping_id'] = $kra_mapping['EmployeeKraMapping']['id'];
                $rm_trigger['triggered_on'] = date('Y-m-d H:i:s');
                $rm_trigger['status'] = 'o';
                $rm_trigger['deleted'] = 0;
                $this->KraRmTrigger->create();
                if($this->KraRmTrigger->save($rm_trigger)){
                    $flag++;
                }
                //mail to be added;
                Configure::load('messages');
                $subjectBody = Configure::read('KRA_RM_TRIGGER');
                $employeeName = $this->Employee->field('CONCAT(Employee.first_name," ",Employee.last_name)', array('Employee.id =' => $kra_mapping['EmployeeKraMapping']['employee_id']));
                $this->Email->to = $this->Employee->field('Employee.work_email_address', array('Employee.id =' => $kra_mapping['EmployeeKraMapping']['employee_id']));
                $this->Email->cc = $this->Employee->field('Employee.work_email_address', array('Employee.id =' => $kra_mapping['EmployeeKraMapping']['manager_id']));
                $this->Email->html_body = sprintf($subjectBody['body'], $employeeName);
                $this->Email->subject = $subjectBody['subject'];
                if($this->Email->send()&& $flag){
                    echo 'SUCCESS';
                }
            }
        }
        
        function kra_record_comments($type){
            $this->autoRender = false;
            if ($this->RequestHandler->isAjax() &&!empty($_POST)) {
                if($type=='savecomment'){
                    $master_id=$this->KraCommentsMaster->field('KraCommentsMaster.id',array('KraCommentsMaster.employee_kra_mapping_id'=>$_POST['mapping_id']));
                    if(empty($master_id)){
                        $this->KraCommentsMaster->save(array('employee_kra_mapping_id'=>$_POST['mapping_id'],'deleted'=>0));
                        $master_id=$this->KraCommentsMaster->getLastInsertId();
                    }
                    $save_comment=array();
                    $save_comment['master_id']=$master_id;
                    $save_comment['parent_id']=$_POST['parent'];
                    $save_comment['comment']=trim($_POST['comments']);
                    $save_comment['created_by']=$this->Auth->user("employee_id");
                    $save_comment['created_on']=date('Y-m-d H:i:s');
                    $save_comment['deleted']=0;
                    $this->KraComment->save($save_comment);
                }
                elseif($type=='getcomment'){
                    $master_id=$this->KraCommentsMaster->field('KraCommentsMaster.id',array('KraCommentsMaster.employee_kra_mapping_id'=>$_POST['mapping_id']));
                    if(!empty($master_id)){
                        $data_array = $this->KraComment->find('all', array('fields' => array('KraComment.*'),
                        'conditions' => array('KraComment.master_id' =>$master_id
                        ))
                        );
                        $comments_array_temp=array();
                        foreach ($data_array as $comments){
                             $creater_name = $this->Employee->field('Employee.first_name', array('Employee.id =' => $comments['KraComment']['created_by']));
                             $username_new = $this->Employee->field('Employee.employee_photo', array('Employee.id =' => $comments['KraComment']['created_by']));
                             $image_name = !empty($username_new) ? file_exists(WEB_ROOT_DIRECTORY . "employee_photos/" . $username_new) ? BASE_PATH . "uploads/employee_photos/" . $username_new : BASE_PATH . "img/profile_pic.jpg" : BASE_PATH . "img/profile_pic.jpg";
                             $comments_array['id']=$comments['KraComment']['id'];
                             $comments_array['parent']=!empty($comments['KraComment']['parent_id'])?$comments['KraComment']['parent_id']:null;
                             $comments_array['created']=date("Y-m-d H:i:s", strtotime($comments['KraComment']['created_on']));
                             $comments_array['modified']=date("Y-m-d H:i:s", strtotime($comments['KraComment']['created_on']));
                             $comments_array['content']=$comments['KraComment']['comment'];
                             $comments_array['pings']=array();
                             $comments_array['creator']=$comments['KraComment']['created_by'];
                             $comments_array['profile_picture_url']=$image_name;
                             $comments_array['fullname']=$creater_name;
                             $comments_array['created_by_admin']=false;
                             $comments_array['created_by_current_user']=($comments['KraComment']['created_by']==$this->Auth->user('employee_id'))?true:false;
                             $comments_array_temp[]=$comments_array;
                             
                        }
                         echo json_encode($comments_array_temp);
                    }
                    else{
                        $comments_array=array();
                        echo json_encode($comments_array);
                    }
                       
                    
                }
                
            }
        }
}

?>