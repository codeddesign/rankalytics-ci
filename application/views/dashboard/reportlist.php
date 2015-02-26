<?php
if ($role == 'admin') {
    $trigger = "";
} else {
  // print_r( $user_database);
    
    $trigger = ' where userid=' . $id;
    //echo 'SELECT * FROM tbl_project' . $trigger ;
    
}
$query = $this->db->query('SELECT * FROM tbl_rt_report '.$trigger );
$data['reports'] = $query->result_array();
if ($data['reports']) {
    foreach ($data['reports'] as $report_data) {
        ?>
                                <div class="report-listunder" id="id_<?php echo $report_data['id'] ?>">
                                    <div class="report-reportnamewrap">
                                        <div class="report-reportnametitle"><?php echo $report_data['report_name']; ?></div>
                                        <div class="report-reportnamesmalltitle">Previous Reports: <?php echo $report_data['domain_name']; ?></div>
                                    </div>
                                    <div class="report-reportselecteddate"><?php echo date('d, M. Y', strtotime($report_data['start_date'])) . " - " . date('d, M. Y', strtotime($report_data['end_date'])) ?></div>
                                    <div onclick="Delete(<?php echo $report_data['id'] . " , '" . $report_data['filename'] . "'"; ?>)" class="report-listdelete"></div>
                                    <a target="_blank" href="/csv/<?php echo $report_data['filename'] . ".csv"; ?>" class="report-listcsvbutton"></a>
                                    <a target="_blank" href="/csv/<?php echo $report_data['filename'] . ".pdf"; ?>" class="report-listpdfbutton"></a>
                                </div>
    <?php }
} else {
    echo '<div style="padding:10px;" class="report-reportnametitle">No report available</div>';
} ?>