<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8"/>
    <title>Reports Page PDF</title>
    <link href="http://rankalytics.com//assets/css/style.css" rel="stylesheet" type="text/css">

    <script type="text/javascript" src="http://code.jquery.com/jquery-1.6.2.js"></script>

    <style type="text/css">

        .charlab {
            font-face: helvetica, arial;
            color: black;
            margin: 0;
        }

        .date {
            font-size: 10px;
            font-weight: normal
        }

        .visits {
            color: #5Af;
            font-weight: normal
        }

        .hits {
            color: #F80;
            font-weight: normal
        }

        .label {
            font-size: 12px;
            line-height: 14px;
            margin-left: 2px;
            font-weight: bold;
            text-align: center;
        }

        #chart div svg {
            z-index: 0;
            display: none
        }

        .reportspdf-list table {
            position: relative;
            top: -10px;
        }

        .reportspdf-infoline {
            border-bottom: 30px solid #DDDDDD;
            float: left;
            height: 8px;
            width: 100%;
        }

        .reportspdf-list {
            border-bottom: 8px solid #DDDDDD;
            float: left;
            height: 30px;
            width: 100%;
        }

        .reportspdf-list ul li {
            list-style-type: none;
            height: 40px;
        }
    </style>


</head>

<?php
$this->pgsql = $this->load->database('pgsql', true);
$query = $this->pgsql->query('SELECT * FROM tbl_project where id=\'' . $domain_id . '\'');
$project_data = $query->result_array();
$domain_name = $project_data[0]['domain_url'];
$project_name = $project_data[0]['project_name'];

$key_query = "SELECT * FROM tbl_project_keywords as pk join project_keyword_relation as pkr on pkr.keyword_id=pk.unique_id where pkr.project_id='" . $domain_id . "'";
$query = $this->pgsql->query($key_query);
$keyword_data = $query->result_array();
?>
<body style="background:#FFFFFF;">

<div class="reportspdfwrap" style="margin-top: 2px;">
<table>
    <tr>
        <td style="width:360px">
            <div class="reportspdf-logo" style="float: left">
                <?php if (!empty($companyLogo)) { ?>

                    <img src="http://rankalytics.com/uploads/logos/thumbnails/<?php echo $companyLogo; ?>"/>

                <?php
                } else {
                    echo "<div class='reportslogo' ></div>";
                }?>
            </div>
        </td>
        <td style="width:300px">
            <div class="reportspdf-rightreport" style="float: right">
                <div class="reportspdf-rightreporttext">CAMPAIGN REPORT</div>
                <div class="reportspdf-createdby" style="text-transform:uppercase;">CREATED BY: <?php if (!empty($companyName)) {
                        echo $companyName;
                    } else {
                        echo "RANKALYTICS RANK TRACKER";
                    }
                    ?></div>
                <div class="reportspdf-rightreportdate">FROM: <?php echo date('M. d, Y', strtotime($start_date)) ?> TO: <?php echo date('M. d, Y', strtotime($end_date)) ?></div>
            </div>
        </td>
    <tr>
</table>
<!-- the div where we are going to plot -->

<?php
$u_date = date('Y-m-d', strtotime($start_date));
while(strtotime($u_date) <= strtotime($end_date)) {
    $q = 'SELECT avg(rank::int) AS avg FROM crawled_sites WHERE host=\''.$domain_name.'\'';
    $q .= ' AND crawled_date::text LIKE \''.$u_date.'%\'';
    $result = $this->pgsql->query($q)->result_array();
    if(is_numeric($result[0]['avg'])) {
        $the_value = number_format($result[0]['avg'], 2);
    } else {
        $the_value = '';
    }

    $date_info[date('d/m', strtotime($u_date))] = $the_value; // <- save to array
    $u_date = date("Y-m-d", strtotime("+1 day", strtotime($u_date))); // <- increment
}

$for_chart = array (
    'data' => $date_info,
);
?>

<img style="margin-top:45px;" src="https://rankalytics.com/GoogleChart/view?<?= http_build_query($for_chart);?>"/>

<?php
$date = date("Y-m-d", strtotime($start_date));
$end_date = date("Y-m-d", strtotime($end_date));
$avg_rank = 0;
$avg = 0;
$start_pos = 0;
$end_pos = 0;
$count = 0;
$total = count($keyword_data);
$total_key = array();
if (!empty($keyword_data)) {
    foreach ($keyword_data as $row) {
        $keyword = $row['keyword'];
        $unique_id = $row['unique_id'];

        $query = $this->pgsql->query('SELECT avg(rank::int) AS avg FROM crawled_sites where keyword_id=\'' . $unique_id . '\' AND host = \'' . $domain_name . '\' AND crawled_date >=\'' . $date . ' 00:00:00\'  and crawled_date <=\'' . $end_date . ' 23:59:59\'  group by keyword_id');
        $keyword_rank = $query->result_array();
        if (!empty($keyword_rank)) {
            if ($count == 0) {

                $start_pos = $keyword_rank[0]['avg'];
            }
            $end_pos = $keyword_rank[0]['avg'];
            $avg_rank = $avg_rank + $keyword_rank[0]['avg'];
            $total_key[] = $keyword_rank[0]['avg'];
        }
    }

    $avg = round($avg_rank / $total, 1);
    $avg = number_format((float)$avg, 1, '.', '');
    rsort($total_key);
}
?>

<div class="reportspdf-boxwrap">
    <table>
        <tr>
            <td>
                <div class="reportspdf-boxwrapinner">
                    <div class="reportspdf-boxwraptitle">AVERAGE POSITION</div>
                    <div class="reportspdf-boxwrapsub"><?php echo $avg ?></div>
                </div>
            </td>

            <td>
                <div class="reportspdf-boxwrapinner">
                    <div class="reportspdf-boxwraptitle">KEYWORDS IN TOP 10</div>
                    <div class="reportspdf-boxwrapsub"><?php


                        if (!empty($keyword_rank)) {

                            if ($total > 10) {
                                $top_val = $total_key[9];
                                $top_ten = 0;
                                foreach ($total_key as $val) {
                                    if ($val >= $top_val) {
                                        $top_ten++;
                                    }
                                }

                                echo $top_ten;
                            } else {
                                echo $total;

                            }

                        } else {
                            echo "0";
                        }

                        ?></div>
                </div>
            </td>

            <td>
                <div class="reportspdf-boxwrapinner">
                    <div class="reportspdf-boxwraptitle">POSITIONS CHANGE</div>
                    <div class="reportspdf-boxwrapsub"><?php $pos = $start_pos - $end_pos;
                        if ($start_pos == $end_pos) {
                            echo '0';
                        }
                        if ($start_pos > $end_pos) {
                            echo '+' . $pos;
                        }
                        if ($start_pos < $end_pos) {
                            echo $pos;
                        }
                        ?></div>
                </div>
            </td>
        </tr>
    </table>
</div>


<table style=" margin-top: 300px;">
    <tr>
        <td style="width:360px ;">
            <div class="reportspdf-logo" style="float: left">
                <?php if (!empty($companyLogo)) { ?>

                    <img src="http://rankalytics.com/uploads/logos/thumbnails/<?php echo $companyLogo; ?>"/>

                <?php
                } else {
                    echo "<div class='reportslogo' ></div>";
                }?>
            </div>
        </td>
        <td style="width:300px">
            <div class="reportspdf-rightreport" style="float: right">
                <div class="reportspdf-rightreporttext">CAMPAIGN REPORT</div>
                <div class="reportspdf-createdby" style="text-transform:uppercase;">CREATED BY: <?php if (!empty($companyName)) {
                        echo $companyName;
                    } else {
                        echo "RANKALYTICS RANK TRACKER";
                    }
                    ?></div>
                <div class="reportspdf-rightreportdate">FROM: <?php echo date('M. d, Y', strtotime($start_date)) ?> TO: <?php echo date('M. d, Y', strtotime($end_date)) ?></div>
            </div>
        </td>
    <tr>
</table>

<div class="reportspdf-clientname">PROJECT: <?php echo $project_name; ?></div>
<div class="reportspdf-infoline">
    <table>


        <tr>
            <td>
                <div class="reportpdf-datetitle">KEYWORD</div>
            </td>
            <td>
                <div class="reportpdf-chantitle">START</div>
            </td>
            <td>
                <div class="reportpdf-camptitle">END</div>
            </td>
            <td>
                <div class="reportpdf-adgrouptitle">CHANGE</div>
            </td>
        </tr>
    </table>
</div>
<div class="reportspdf-list">
    <ul>

        <?php
        foreach ($keyword_data as $row) {
            $keyword = $row['keyword'];
            $unique_id = $row['unique_id'];

            $date = date("Y-m-d", strtotime($start_date));
            $end_date = date("Y-m-d", strtotime($end_date));

            $query = $this->pgsql->query('SELECT * FROM crawled_sites  where keyword_id=\'' . $unique_id . '\' and  host = \'' . $domain_name . '\' and crawled_date =\'' . $date . '\'  ');
            $keyword_rank = $query->result_array();

            if (!empty($keyword_rank)) {
                $start_rank = $keyword_rank[0]['rank'];
            } else {
                $start_rank = "n/a";
            }

            $query = $this->pgsql->query('SELECT * FROM crawled_sites  where keyword_id=\'' . $unique_id . '\' and  host = \'' . $domain_name . '\'   and crawled_date <=\'' . $end_date . '\'');
            $keyword_rank = $query->result_array();

            if (!empty($keyword_rank)) {
                $end_rank = $keyword_rank[0]['rank'];
            } else {
                $end_rank = "n/a";
            }

            $change = 'n/a';
            if ($start_rank != "n/a" or $end_rank != 'n/a') {
                if ($start_rank != "n/a" and $end_rank == 'n/a') {
                    $change = "No Change";
                }
                if ($start_rank == "n/a" and $end_rank != 'n/a') {
                    $change = $end_rank;
                }

            }

            if ($start_rank != "n/a" and $end_rank != 'n/a') {
                $change = $start_rank - $end_rank;
            }
            ?>
            <li>
            <table>
                <tr>
                    <td>
                        <div class="reportpdf-keywordtitle"><?php echo $keyword; ?></div>
                    </td>
                    <td>
                        <div class="reportpdf-keywordstart"><?php echo $start_rank; ?></div>
                    </td>
                    <td>
                        <div class="reportpdf-keywordcurrent"><?php echo $end_rank ?></div>
                    </td>
                    <td>
                        <div class="reportpdf-keywordchange"><?php echo $change; ?></div>
                    </td>
                </tr>
            </table> </li>
        <?php
        }
        ?>


    </ul>
</div>
</div>

</body>
</html>
