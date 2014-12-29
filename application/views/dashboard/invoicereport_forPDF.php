<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8"/>
<title>Rechnungsbericht</title>

<style>

body {
    margin: 0;
    background: #EFF4F7;
}

/** REPORTS PDF PAGE **/

.reportspdfwrap {

}

.reportspdf-logo {

}

.reportspdf-rightreport {
    float: right;
    width: 354px;
    border-bottom: 4px solid #404C5D;
    height: 74px;
}

.reportspdf-rightreporttext {
    float: left;
    color: #3F4C5D;
    font-size: 21px;
    font-family: Helvetica, Arial, Sans-serif;
    width: 100%;
    margin-left: 2px;
}

.reportspdf-rightreportdate {
    float: left;
    color: #3F4C5D;
    font-size: 14px;
    font-family: Helvetica, Arial, Sans-serif;
    width: 100%;
    margin-top: 8px;
    margin-left: 2px;
}

.reportspdf-rightbottomline {
    float: left;
    width: 100%;
    height: 4px;
    background: #404C5D;
    margin-top: 10px;
}

#pdfchart {
    border: 1px solid #EEEEEE;
    float: left;
    height: 300px;
    margin-top: 56px;
    width: 100%;
    font-family: Helvetica, Arial, Sans-serif;
}

.reportspdf-boxwrap {
    float: left;
    margin-top: 40px;
    width: 100%;
    border: 1px solid #DDDDDD;
    height: 108px;
    margin-bottom: 100px;
}

.reportspdf-boxwrapinner {
    float: left;
    width: 245px;
    margin-top: 26px;
}

.reportspdf-boxwraptitle {
    float: left;
    width: 100%;
    text-align: center;
    color: #000000;
    font-size: 12px;
    font-weight: 600;
    font-family: Helvetica, Arial, Sans-serif;
    margin-bottom: 8px;
}

.reportspdf-boxwrapsub {
    float: left;
    width: 100%;
    text-align: center;
    color: #000000;
    font-size: 40px;
    font-weight: 600;
    font-family: Helvetica, Arial, Sans-serif;
}

.reportspdf-clientname {
    float: left;
    margin-top: 60px;
    width: 100%;
    font-size: 11px;
    color: #545454;
    font-family: Helvetica, Arial, Sans-serif;
    margin-bottom: 15px;
    font-weight: 600;
}

.reportspdf-infoline {
    float: left;
    border-bottom: 29px solid #DDDDDD;
    height: 1px;
    width: 100%;
}

.reportpdf-datetitle {
    color: #545454;
    float: left;
    font-family: Helvetica, Arial, Sans-serif;
    font-size: 11px;
    font-weight: 600;
    width: 350px;
    line-height: 33px;
    text-align: left;
    margin-left: 20px;
}

.reportpdf-chantitle {
    color: #545454;
    float: left;
    font-family: Helvetica, Arial, Sans-serif;
    font-size: 11px;
    font-weight: 600;
    width: 110px;
    line-height: 33px;
    text-align: center;
}

.reportpdf-camptitle {
    color: #545454;
    float: left;
    font-family: Helvetica, Arial, Sans-serif;
    font-size: 11px;
    font-weight: 600;
    width: 114px;
    line-height: 33px;
    text-align: center;
}

.reportpdf-adgrouptitle {
    color: #545454;
    float: left;
    font-family: Helvetica, Arial, Sans-serif;
    font-size: 11px;
    font-weight: 600;
    width: 114px;
    line-height: 33px;
    text-align: center;
}

.reportpdf-cpctitle {
    color: #545454;
    float: left;
    font-family: Helvetica, Arial, Sans-serif;
    font-size: 11px;
    font-weight: 600;
    width: 106px;
    line-height: 29px;
    text-align: center;
}

.reportpdf-clickstitle {
    color: #545454;
    float: left;
    font-family: Helvetica, Arial, Sans-serif;
    font-size: 11px;
    font-weight: 600;
    width: 59px;
    line-height: 29px;
    text-align: center;
}

.reportpdf-imptitle {
    color: #545454;
    float: left;
    font-family: Helvetica, Arial, Sans-serif;
    font-size: 11px;
    font-weight: 600;
    width: 58px;
    line-height: 29px;
    text-align: center;
}

.reportpdf-ctrtitle {
    color: #545454;
    float: left;
    font-family: Helvetica, Arial, Sans-serif;
    font-size: 11px;
    font-weight: 600;
    width: 58px;
    line-height: 29px;
    text-align: center;
}

.reportspdf-list {
    float: left;
    width: 100%;
    height: 30px;
    border-bottom: 1px solid #DDDDDD;
}

.reportspdf-list ul {
    margin: 0px;
    padding: 0px;
    margin-bottom: 25px;
    float: left;
    width: 100%;
}

.reportspdf-list ul li {
    float: left;
    width: 100%;
    height: 38px;
    background: #FFFFFF;
    list-style-type: none;
}

.reportspdf-list ul li:nth-child(even) {
    background: #F5F5F5;
}

.reportpdf-keywordtitle {
    color: #545454;
    float: left;
    font-family: Helvetica, Arial, Sans-serif;
    font-size: 13px;
    font-weight: 500;
    line-height: 38px;
    margin-left: 20px;
    text-align: left;
    width: 350px;
}

.reportpdf-keywordstart {
    color: #545454;
    float: left;
    font-family: Helvetica, Arial, Sans-serif;
    font-size: 13px;
    font-weight: 500;
    line-height: 38px;
    text-align: center;
    width: 110px;
}

.reportpdf-keywordcurrent {
    color: #545454;
    float: left;
    font-family: Helvetica, Arial, Sans-serif;
    font-size: 13px;
    font-weight: 500;
    line-height: 38px;
    text-align: center;
    width: 114px;
}

.reportpdf-keywordchange {
    color: #545454;
    float: left;
    font-family: Helvetica, Arial, Sans-serif;
    font-size: 13px;
    font-weight: 500;
    line-height: 38px;
    text-align: center;
    width: 114px;
}

.reportspdf-createdby {
    float: left;
    font-family: Helvetica, Arial, Sans-serif;
    font-size: 12px;
    font-weight: 300;
    letter-spacing: 0.3px;
    margin-top: 8px;
    width: 100%;
    margin-left: 2px;
}

.reportslogo {

}

/************ INVOICE STYLESHEET ***********/

.invoicepdf-createdby {
    float: left;
    font-family: Helvetica, Arial, Sans-serif;
    font-size: 15px;
    font-weight: 300;
    letter-spacing: 0.3px;
    margin-left: 2px;
    margin-top: 5px;
    width: 100%;
    color: #3F4C5D;
}

.invoicepdf-billedto {
    color: #3F4C5D;
    float: left;
    font-family: Helvetica, Arial, Sans-serif;
    font-size: 14px;
    line-height: 22px;
    margin-top: 59px;
    width: 180px;
}

.invoicepdf-billedfrom {
    color: #3F4C5D;
    float: left;
    font-family: Helvetica, Arial, Sans-serif;
    font-size: 14px;
    line-height: 22px;
    margin-top: 27px;
    width: 180px;
    /*margin-left: 204px;*/
}

table {
    width: 100%;
}

.invoicepdf-billingwrap {
    clear: both;
    color: #3F4C5D;
    float: left;
    font-family: Helvetica, Arial, Sans-serif;
    font-size: 14px;
    line-height: 22px;
    margin-bottom: 50px;
    margin-top: 24px;
    width: 100%;
}

.invoicepdf-billingwrap span {
    font-weight: bold;
}

.border {
    border: 1px solid gray
}

.table-header {
    background: none repeat scroll 0 0 #DDDDDD;
}

.white-back {
    background: #FFFFFF;
}

.gray-back {
    background: #F5F5F5;
}
</style>
</head>
<body style="background:#FFFFFF;">

<div class="reportspdfwrap" style="width: 735px;margin: 0 auto;margin-top: 45px;">
    <table>
        <tr>
            <td style="" valign="top">
                <div class="reportspdf-logo " style="float: left;color: #3F4C5D;font-size: 21px;font-family: Helvetica, Arial, Sans-serif;width: 375px;">
                    <div class="reportslogo" style="background: url('<?php echo base_url() ?>assets/images/reportslogo.png');
                        width: 204px;
                        height: 36px;
                        float: left;"></div>
                </div>
            </td>
            <td style="" valign="top">
                <div class="reportspdf-rightreport " style="border-bottom:none;float:right">
                    <div class="reportspdf-rightreporttext">Rankalytics Subscription</div>
                    <div class="invoicepdf-createdby">Account</div>
                </div>
            </td>
        </tr>
        <tr>
            <td style="">
                <div class="invoicepdf-billedto">
                    BILLING ADDRESS
                    <p></p>
                    <?php echo $user_database['firstName'] . " " . $user_database['lastName']; ?>
                    <br>
                    <?php echo $user_database['streetAddress']; ?>
                    <br>
                    <?php echo $user_database['city'], ", " . $user_database['country']; ?>
                    <br>
                    Telephone: <?php echo $user_database['phoneNumber']; ?>
                    <br>
                    E-mail: <?php echo $user_database['emailAddress']; ?>
                </div>
            </td>
            <td width="">
                <div class="invoicepdf-billedfrom">
                    <strong>Rankalytics.com</strong>
                    <br>
                    www.rankalytics.com
                    <p></p>
                    Lindenstrasse 2d
                    <br>
                    82216 Maisach
                    <br>
                    Telefon: +49(8141)-3150375
                    <br>
                    Email: support@rankalytics.com
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="invoicepdf-billingwrap">
                    <span>Report created at:</span> <?php echo date("d M Y H:i:s"); ?>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <!--div class="reportspdf-infoline" -->
                <table width="100%">
                    <tr class="table-header">
                        <td width="33%" class="table-header">
                            <div class="reportpdf-datetitle">PRODUCT</div>
                        </td>
                        <td width="33%" class="table-header">
                            <div class="reportpdf-chantitle">CHARGE DATE</div>
                        </td>
                        <td width="33%" class="table-header">
                            <div class="reportpdf-adgrouptitle" style="margin-left:130px;">CHARGE AMOUNT</div>
                        </td>
                </table>
                <!--/div -->
                <!--div class="reportspdf-list"-->
                <?php if (count($subscriptions) > 0) {
                    ?>
                    <table width="100%">
                        <?php
                        foreach ($subscriptions as $s_no => $sub) {
                            if ($s_no % 2 == 0) {
                                $class = "white-back";
                            } else {
                                $class = "gray-back";
                            }
                            ?>
                            <tr class="<?php echo $class; ?>">
                                <td width="33%">
                                    <div class="reportpdf-keywordtitle"><?= strtoupper($sub['service']); ?></div>
                                </td>
                                <td width="33%">
                                    <div class="reportpdf-keywordstart"><?= date('m/d/Y', strtotime($sub['started_on'])); ?></div>
                                </td>
                                <td width="33%">
                                    <div class="reportpdf-keywordchange" style="margin-left:130px;">&euro;<?= $sub['paid']; ?></div>
                                </td>
                            </tr>
                        <?php } //endforeach ?>
                    </table>
                <?php } // endif ?>
                <!--/div -->
            </td>
        </tr>
    </table>
</div>

</body>
</html>