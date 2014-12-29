<?php
$this->load->view("include/header")
?>

<!--link href="/assets/style.css" rel="stylesheet" type="text/css">
<link href="/assets/js/jquery.horizontal.scroll.css" rel="stylesheet" type="text/css"/-->
<link href="/assets/css/c3.css" rel="stylesheet" type="text/css" media="all"/>
<!--link href="/assets/js/jquery-ui-datepicker/css/smoothness/jquery-ui-1.10.3.custom.css" rel="stylesheet" type="text/css">
<link href="http://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css"-->

<!-- Google CDN jQuery with fallback to local -->

<!--script src="/assets/js/jquery-1.11.0.min.js" type="text/javascript"></script-->
<!--script src="/assets/js/jquery.horizontal.scroll.js" type="text/javascript"></script-->
<script src="/assets/js/Chart.js" type="text/javascript"></script>
<!--script src="/assets/js/modernizer-custom.js" type="text/javascript"></script-->

<!--script type="text/javascript" src="//use.typekit.net/pjn4zge.js"></script-->
<!--script type="text/javascript">try{Typekit.load();}catch(e){}</script-->
<script src="/assets/js/d3.min.js" type="text/javascript"></script>
<script src="/assets/js/c3.min.js" type="text/javascript"></script>
<!--script src="/assets/js/jquery.horizontal.scroll.js" type="text/javascript"></script>
<script src="/assets/js/jquery-ui-datepicker/js/jquery-ui-1.10.3.custom.js" type="text/javascript"></script-->
<style>
    .report-graph_click {
        background: url("/assets/images/report-graphreport.png") repeat scroll 0 -316px rgba(0, 0, 0, 0);
        float: left;
        height: 182px;
        width: 162px;
    }

    .listreport_click {
        background: url("/assets/images/report-listreport.png") repeat scroll 0 -316px rgba(0, 0, 0, 0);
        float: left;
        height: 182px;
        width: 162px;
    }
</style>
<script type="text/javascript">
    function validation() {

        report_name = $("#report_name").val().trim();
        domain = $("#select_domain").val().trim();
        start_date = $("#start_date").val().trim();
        end_date = $("#end_date").val().trim();
        graph = $("#report_graph").val().trim();
        list = $("#list_report").val().trim();

        if (report_name == "" || domain == "" || start_date == "" || end_date == "") {
            alert("Please fill in all fields");
            return false;
        }
        if (graph == 0 && list != 1) {
            alert("Please select a report type");
            return false;
        }
        if (graph != 1 && list == 0) {
            alert("Please select a report type");
            return false;
        }
        $(".overlay").show();
    }
    jQuery(function ($) {

        $('#start_date').datepicker({
            beforeShow: function () {
                $(this).datepicker('option', 'maxDate', $('#end_date').val());
            }
        });
        $('#end_date').datepicker({
            beforeShow: function () {
                $(this).datepicker('option', 'minDate', $('#start_date').val());
            }
        });

        $("#weather").click(function () {
            $("#weatherpopup").toggle();
        });

        $(".reportadddomain").click(function () {
            domain = $("#common_domain").html();
            $(".reportadddomain").before('<div>' + domain + '<div  style="float: left;    left: -118px;    position: relative;" class="report-listdelete remove"></div></div> ');
            $(".remove").click(function () {
                $(this).parent().remove();
            });

            $(".domain").change(function () {

                var current = $(this).val();
                var results = [];

                $("select[name='select_domain[]']").not(this).each(function (index, val) {
                    if ($(val).val() == "") {
                        add_result = "";
                    }
                    else {
                        if ($(val).val().trim() != "") {
                            results.push($(val).val());
                        }
                    }
                });

                if ($.inArray(current, results) > -1) {

                    alert('Please select from different domains');
                    $(this).prop('selectedIndex', 0);
                    return false;
                }
            });
        });

        $(".report-graphreport").click(function () {
            if ($(this).attr('class') == 'report-graphreport') {
                $('.listreport_click').addClass('report-listreport').removeClass('listreport_click');
                $(this).addClass('report-graph_click').removeClass('report-graphreport');
                $("#report_graph").val('1');
                $("#list_report").val('0');
            }
            else {
                $(this).addClass('report-graphreport').removeClass('report-graph_click');
                $("#report_graph").val('0');
            }

        });

        $(".report-listreport").click(function () {

            if ($(this).attr('class') == 'report-listreport') {
                $('.report-graph_click').addClass('report-graphreport').removeClass('report-graph_click');
                $(this).addClass('listreport_click').removeClass('report-listreport');
                $("#list_report").val('1');
                $("#report_graph").val('0');
            }
            else {
                $(this).addClass('report-listreport').removeClass('listreport_click');
                $("#list_report").val('0');
            }

        });


    });
    function Delete(id, file) {
        if (confirm("Bestätigen")) {

            $.ajax({
                url: "/ranktracker/delete_reports",
                data: ({id: id, file: file}),
                type: "POST",
                success: function () {

                    $("#id_" + id).remove();
                    alert("The report was successfully deleted");

                }
            })

        }
    }
</script>
<body>
<?php
error_reporting(E_STRICT | E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 300); //300 seconds = 5 minutes

$user_database['id'] = $user_database['id'];
$query = $this->pgsql->query('SELECT * FROM tbl_project where "userId"=\'' . $user_database['id'] . '\'');
$project_id = $query->result_array();
$id_list = array();
foreach ($project_id as $id) {
    $id_list[] = '\'' . $id['id'] . '\'';

}

if (count($id_list) > 0) {
    $query = $this->pgsql->query('SELECT DISTINCT keyword FROM tbl_project_keywords where project_id in (' . implode(",", $id_list) . ') ');
    $keywordlist = $query->result_array();
    $total_keywords = count($keywordlist);
} else {
    $total_keywords = 0;
}

$result = '';
$pdf_content = "";

if (isset($_POST['create_report'])) {

    //ob_end_clean();
    $report_name = $_POST['report_name'];
    $domain_id = $_POST['select_domain'];
    $report_graph = $_POST['report_graph'];
    $list_report = $_POST['list_report'];
    $domainlist = array();
    foreach ($domain_id as $domain_data) {
        if ($domain_data != "") {
            $domainlist[] = $domain_data;
        }

    }
    foreach ($domainlist as $domain_id) {
        $data = array();
        $reports = array();
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $query = $this->pgsql->query('SELECT * FROM tbl_project where id=\'' . $domain_id . '\'');
        $project_data = $query->result_array();
        $domain_name = $project_data[0]['domain_url'];
        $project_name = $project_data[0]['project_name'];

        $key_query = "SELECT * FROM tbl_project_keywords as pk join project_keyword_relation as pkr on pkr.keyword_id=pk.unique_id where pkr.project_id='" . $domain_id . "'";
        $query = $this->pgsql->query($key_query);

        $keyword_data = $query->result_array();

        $keyword = '';
        $current_pos = '';
        $day7 = '';
        $day28 = '';
        $ert = '';
        $competition = '';
        $kei = '';
        $comp_pages = '';
        $search_volume = '';
        $cpc = '';


        $data_r['companyName'] = $user_database['companyName'];
        $data_r['companyLogo'] = $user_database['companyLogo'];
        $data_r['start_date'] = $start_date;
        $data_r['end_date'] = $end_date;
        $data_r['domain_id'] = $domain_id;
        $data_r['user_id'] = $user_database['id'];
        $pdf_content = '<table>';
        // $data[0] = ', Project Name, Domain, Keyword, Current position, 7-day, 28-day, ERT, Competition, KEI, Comp. Pages, Search Volume, CPC ';
        $data[0] = ' Project name, Domain, Keyword, Current position, 7-day, 28-day, ERT, Competition, KEI, Comp. Pages, Search Volume, CPC ';
        $pdf_content .= '<tr><td> Project name</td> <td>Domain </td><td> Keyword </td><td>Current Position</td> <td>7-day</td><td> 28-day</td><td> ERT</td><td> Competition</td><td> KEI</td><td> Comp. Pages</td><td> Search Volume</td><td> CPC</td></tr> ';;
        foreach ($keyword_data as $row) {

            $query = $this->pgsql->query('SELECT * FROM project_keywords_adwordinfo where keyword_id=\'' . $row['unique_id'] . '\'');
            $keyword_other = $query->result_array();

            if (!empty($keyword_other)) {
                $competition = $keyword_other[0]['competition'];
                $search_volume = $keyword_other[0]['volume'];
                $cpc = $keyword_other[0]['CPC'];
            } else {

                $competition = 'n/a';
                $search_volume = 'n/a';
                $cpc = 'n/a';
            }
            $keyword = $row['keyword'];
            $unique_id = $row['unique_id'];
            $domain_url = $domain_name;
            $date = date("Y-m-d", strtotime($start_date));
            $end_date = date("Y-m-d", strtotime($end_date));
            $date7 = date('Y-m-d', strtotime('-7 days', strtotime($start_date)));
            $date28 = date('Y-m-d', strtotime('-28 days', strtotime($start_date)));
            $query = $this->pgsql->query('SELECT * FROM crawled_sites  where keyword_id=\'' . $unique_id . '\' and  host = \'' . $domain_url . '\' and crawled_date >=\'' . $date . '\'  and crawled_date <=\'' . $end_date . '\'');
            $keyword_rank = $query->result_array();
            if (!empty($keyword_rank)) {
                $comp_pages = $keyword_rank[0]['total_records'];
                $current_pos = $keyword_rank[0]['rank'];
                switch ($current_pos) {
                    case 1:
                        $percent = 30;
                        break;
                    case 2:
                        $percent = 16;
                        break;
                    case 3:
                        $percent = 10;
                        break;
                    case 4:
                        $percent = 8;
                        break;
                    case 5:
                        $percent = 6;
                        break;
                    case 6:
                        $percent = 4;
                        break;
                    case 7:
                        $percent = 3;
                        break;
                    case 8:
                        $percent = 3;
                        break;
                    case 9:
                        $percent = 2;
                        break;
                    case 10:
                        $percent = 2;
                        break;
                    case 11:
                        $percent = 1;
                        break;
                    case 12:
                        $percent = 0.7;
                        break;
                    case 13:
                        $percent = 0.7;
                        break;
                    case 14:
                        $percent = 0.6;
                        break;
                    case 15:
                        $percent = 0.4;
                        break;
                    case 16:
                        $percent = 0.35;
                        break;
                    case 17:
                        $percent = 0.33;
                        break;
                    case 18:
                        $percent = 0.27;
                        break;
                    case 19:
                        $percent = 0.27;
                        break;
                    case 20:
                        $percent = 0.29;
                        break;
                    case 21:
                        $percent = 0.1;
                        break;
                    case 22:
                        $percent = 0.1;
                        break;
                    case 23:
                        $percent = 0.08;
                        break;
                    case 24:
                        $percent = 0.06;
                        break;
                    case 25:
                        $percent = 0.06;
                        break;
                    case 26:
                        $percent = 0.05;
                        break;
                    case 27:
                        $percent = 0.05;
                        break;
                    case 28:
                        $percent = 0.05;
                        break;
                    case 29:
                        $percent = 0.04;
                        break;
                    case 30:
                        $percent = 0.06;
                        break;
                    default:
                        $percent = 0;


                }
                if ($percent == 0) {
                    $ert = "Low Rank";

                } else {
                    $ert = $keyword_other[0]['volume'] * $percent;
                }
            } else {
                $current_pos = "n/a";
                $ert = "Low Rank";
                $comp_pages = $row['total_records'];
            }
            $query = $this->pgsql->query('SELECT * FROM crawled_sites  where keyword_id=\'' . $unique_id . '\' and  host = \'' . $domain_url . '\' and crawled_date >=\'' . $date7 . '\'  and crawled_date <=\'' . $end_date . '\'');
            $keyword_rank = $query->result_array();
            if (!empty($keyword_rank)) {
                $day7 = $keyword_rank[0]['rank'];
            } else {
                $day7 = "n/a";
            }
            $query = $this->pgsql->query('SELECT * FROM crawled_sites  where keyword_id=\'' . $unique_id . '\' and  host = \'' . $domain_url . '\' and crawled_date >=\'' . $date28 . '\'  and crawled_date <=\'' . $end_date . '\'');
            $keyword_rank = $query->result_array();
            if (!empty($keyword_rank)) {
                $day28 = $keyword_rank[0]['rank'];
            } else {
                $day28 = "n/a";
            }

            $search_val = 1;
            //$comp_pages = $row['total_records'];
            if (!empty($keyword_other)) {
                if ($keyword_other[0]['volume'] == 0) {
                    $search_val = 0;

                } else {
                    $search_val = $keyword_other[0]['volume'];
                }
            }
            if ($search_val != 0) {
                $kei = round($row['total_records'] / $search_val, 2);
            } else {
                $kei = "n/a";
            }
            $data[] = $project_name . '; ' . $domain_name . '; ' . $keyword . '; ' . $current_pos . '; ' . $day7 . '; ' . $day28 . '; ' . $ert . '; ' . $competition . '; ' . $kei . '; ' . $comp_pages . '; ' . $search_volume . '; ' . $cpc;
        }

        $csvfile = 'csv/rank_cvs.csv';
        $newfilename = $report_name . time();
        $newfilename = md5($newfilename);
        $target = "csv/" . $newfilename . ".csv";
        copy($csvfile, $target);
        chmod($target, 0777);
        $report_array['report_name'] = $report_name;
        $report_array['domain_name'] = $domain_name;
        $report_array['userid'] = $user_database['id'];
        $report_array['start_date'] = $_POST['start_date'];
        $report_array['end_date'] = $_POST['end_date'];
        $report_array['filename'] = $newfilename;

        if (!$error = $this->report_model->save($report_array)) {
            $result = "A unknown error has occurred. ";
        } else {
            $result = "Your report has been created successfully";
        }


        $handle = fopen($target, "a");
        foreach ($data as $line) {

            $line = str_replace("\n", "", $line);
            $line = str_replace("\r", "", $line);
            fwrite($handle, $line . "\n");
        }
        fclose($handle);
        $target_pdf = "csv/" . $newfilename . ".pdf";
        copy("csv/report.pdf", $target_pdf);
        chmod($target_pdf, 0777);
        if ($report_graph == 1 and $list_report == 0) {
            $data_r['grabzIt'] = null;
            $pdf_content = $this->load->view("dashboard/report_format", $data_r, true);
        } else {
            $data_r['ert'] = $ert;
            $pdf_content = $this->load->view("dashboard/details_report", $data_r, true);
        }

        require_once("dompdf/dompdf_config.inc.php");
        $dompdf = new DOMPDF();
        $dompdf->load_html($pdf_content);
        $dompdf->render();
        file_put_contents($target_pdf, $dompdf->output());
        flush();


    }
}

$temp = "";
$date = "";
$google_temps_data = "";
$google_temps = $this->analytical->getGoogleTemperature();
foreach ($google_temps as $temps) {
    $temp = $temp . $temps['temperature'] . ",";
    $date = $date . "'" . $temps['date'] . "'" . ",";
}
$temp = rtrim($temp, ",");
$date = rtrim($date, ",");
$google_temps_data .= "var graphData = { temps: [" . $temp . "],dates: [" . $date . "]};";
?>
<div class="overlay">
    <div id="spinner"></div>
</div>
<div class="yellowtopline"></div>
<div id="weatherpopup">
    <div class="weatherpopup-top"></div>
    <div class="weatherpopup-bg">

        <ul class="nav five-day" style=" margin-left: 32px;">
            <?php
            $temps_array = array();
            $count = 1;
            foreach ($google_temps as $value) {
                $temps_array[] = $value;
                if ($count >= 5) {
                    break;
                }
                $count++;
            }
            krsort($temps_array);
            ?>

            <?php foreach ($temps_array as $value): ?>
                <li class="row" style="list-style: none outside none;  text-align: center; float: left;color: rgb(86, 179, 217);   font: bolder 12px;  width: 100px;">
                    <div class="span2 icon">
                        <?php if ($value['temperature'] <= 15) {
                            echo '<img src="/assets/images/sunny.png" >';

                        } elseif ($value['temperature'] > 15 AND $value['temperature'] <= 21) {
                            echo '<img src="/assets/images/sunny_cloudy.png">';

                        } elseif ($value['temperature'] > 21 AND $value['temperature'] <= 26) {
                            echo '<img src="/assets/images/cloudy.png">';

                        } elseif ($value['temperature'] > 26) {
                            echo '<img src="/assets/images/thunder.png">';

                        }; ?>
                    </div>
                    <div class="span2 temp" style="font-size:30px;padding-left: 10px;">
                        <?php echo $value['temperature'] ?> °C
                    </div>

                    <div class="span2 date" style="margin-left: 5px;   padding-top: 10px;">
                        <?php echo $value['date'] ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
        <br/>

        <div style="clear: both;"></div>


        <script>
            <?php echo $google_temps_data?>
            graphData.temps.reverse();
            graphData.dates.reverse();
        </script>
        <div class="chart chart-thirtyday"></div>
        <script src="<?php echo base_url(); ?>assets/js/weather_graph/highcharts.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/weather_graph/graph.js"></script>
    </div>

</div>


<div class="topinfobar">
    <a href="#" id="weather">
        <div class="weathericon">
            <img src="<?php echo base_url(); ?>assets/images/weather/sun.png">
        </div>
        <div class="weathertext">Google Weather</div>
    </a>

    <div class="toptitlebar">KEYWORD REPORTS</div>
</div>

<div class="projectbackground">
    <div class="reportsdashcontent">
        <div class="settingsbluetop" style="">
            <div class="subscription-accountlevel" style="margin-left:77px;">ACCOUNT LEVEL: <?= strtoupper($sub_info['plan']); ?></div>
            <div class="subscription-keywordlimit">KEYWORD LIMIT: <span><?= $sub_info['crawl_limit']; ?></span></div>
            <div class="subscription-keywordsused">USED KEYWORDS: <span><?php echo $total_keywords; ?></span></div>
            <?php if ($sub_info['plan'] !== 'starter') { ?>
                <div class="subscription-billingrenewal">EXPIRES ON:
                    <span><?= date('d/m/Y', $sub_info['expires_on']); ?></span>
                    <!-- <br/><a href="javascript:void(0);" onclick="cancel_subscription();">Cancel Subscription</a>-->
                </div>
            <?php } ?>
        </div>
        <div class="subscriptiontextlocation" style="width:98%;">
            <?php //print_r($user_database) ; ?>
            <?php echo $result; ?>
            <div class="subscriptiontext" style="margin-left:51px;padding-left:0;">PROJECT REPORT CONFIGURATION</div>
            <div class="reportstitleunder">Fill out the form for a preview or to download your reports</div>
            <div class="reportslinefull"></div>
        </div>
        <div class="subscriptionwrap" style="margin-left:65px;">
            <form action="" method="post" onsubmit="return validation()">
                <input class="report-nameofreport" type="text" id="report_name" name="report_name" placeholder="Name des Reports">
                <?php
                if ($user_database['userRole'] == 'admin') {
                    $trigger = "";
                } else {
                    // print_r( $user_database);

                    $trigger = ' where "userId"=\'' . $user_database['id'] . '\'';
                    //echo 'SELECT * FROM tbl_project' . $trigger ;

                }
                $query = $this->pgsql->query('SELECT * FROM tbl_project' . $trigger);
                $data['domain_url'] = $query->result_array();
                ?>
                <select style="margin-right:  200px;" class="report-choosedomain2 domain" id="select_domain" name="select_domain[]">
                    <option value="">Select Domain</option>
                    <?php
                    if ($data['domain_url']) {
                        foreach ($data['domain_url'] as $domain) {
                            echo '<option value="' . $domain['id'] . '">' . $domain['domain_url'] . '</option>';
                        }
                    }
                    ?>
                </select>
                <!--  below code for add more domain  -->
                <div id="common_domain" style="display:none">

                    <select style="margin-top: 10px;margin-right:  132px;" class="report-choosedomain2 domain" name="select_domain[]">
                        <option value="">Select Domain</option>
                        <?php
                        if ($data['domain_url']) {
                            foreach ($data['domain_url'] as $domain) {
                                echo '<option value="' . $domain['id'] . '">' . $domain['domain_url'] . '</option>';
                            }
                        }
                        ?>
                    </select>


                </div>
                <!-- end code by blb -->
                <!-- <input class="report-choosedomain" type="text" name="username" placeholder="Type in Domain"> -->

                <div class="reportadddomain"></div>

                <div class="reports-selectdatetitle">REPORT DATE</div>
                <input class="report-choosedate " type="text" placeholder="Start Datum" id="start_date" name="start_date">

                <div class="report-choosedateto">to</div>
                <input class="report-choosedate" type="text" placeholder="End Datum" id="end_date" name="end_date">

                <div class="report-reportwrapper">
                    <div class="report-reporttitles">GRAPHIC REPORT</div>
                    <div class="report-reportsub">Graphical report on the daily activity of your keywords</div>
                    <div class="report-graphreport">

                        <input type="hidden" name="report_graph" id="report_graph"/>
                    </div>
                </div>

                <div class="report-reportwrapper" style="clear:none;">
                    <div class="report-reporttitles">DETAILED REPORT</div>
                    <div class="report-reportsub">List-style layout on the daily activity of your keywords</div>
                    <div class="report-listreport">

                        <input type="hidden" name="list_report" id="list_report"/>
                    </div>
                </div>
                <input type="hidden" name="create_report" value="1">
                <input type="submit" value="" class="report-createreportbutton">
            </form>

            <div class="reportslinefull" style="margin-top:10px;margin-left:0px;"></div>
            <div class="report-listtop">
                <div class="report-listtoptitle">Previous Reports</div>
            </div>
            <div class="report-listunderwrap">


                <?php



                $userlist['role'] = $user_database['userRole'];
                $userlist['id'] = $user_database['id'];
                $this->load->view("dashboard/reportlist", $userlist);?>

            </div>
        </div>
    </div>
</div>
<?php $this->load->view("dashboard/common/footer") ?>
</div>


<style type="text/css">
    .report-choosedomain2 {
        float: left;
        width: 223px;
        height: 39px;
        border: 1px solid #DDDDDD;
        border-bottom: 3px solid #DDDDDD;
        padding: 10px 10px;
        font-size: 11px;
        color: #555555;
        margin-right: 40px;
        background-color: #FFFFFF;
    }

    select.report-choosedomain2 option {
        height: 20px;
        padding: 10px 5px;
    }

    /*
        #weatherpopup {
        border-bottom-left-radius: 3px;
        border-bottom-right-radius: 3px;
        box-shadow: 0 1px 2px rgb(187, 187, 187);
        display: none;
        margin-left: 0;
        margin-top: 166px;
        position: absolute;
        width: 620px;
        z-index: 9999;
    } */


</style>

</body>
</html>