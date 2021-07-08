<?php
include_once("../../app/Database.php");
include_once("../../app/App.php");
include_once("../../app/Auth.php");
$auth = new \app\Auth();
$app = new \app\App();
$area_info = $app->getCadtInfo($_POST['cadt_id'], $_POST['cycle_id']);
$ts = time();
$title = $area_info['cadt_name'] . ' ' . $area_info['cycle_name'] . ' ' . $area_info['batch'].'_'.$ts;
?>
<div class="table-responsive">
    <table id="generated_checklist" class="table table-bordered table-sm generatedFindings bg-white table-sm text-dark" width="100%px;" style="border-collapse: collapse;">
        <thead>
        <tr>
            <th>Activity</th>
            <th>Form</th>
            <th >Target</th>
            <th >Actual</th>
            <th >%</th>
            <?php
            //display areas/forms//
            $icc = $app->ipcdd_col($_POST['cadt_id'], $_POST['cycle_id'], 'municipal');
            foreach ($icc as $item) {
                echo '<th style="width: 50px;">' . ucwords(strtolower($item['mun_name'])) . '</th>';
            }
            $icc = $app->ipcdd_col($_POST['cadt_id'], $_POST['cycle_id'], 'barangay');
            foreach ($icc as $item) {
                echo '<th style="width: 50px;">' . ucwords(strtolower($item['brgy_name'] . ' ' . $item['sitio_name'])) . '</th>';
            }
            ?>
        </tr>
        </thead>
        <tbody>
        <?php
        //load per row data//
        $forms = $app->checklistRowIpcdd($_POST['cadt_id'],$_POST['cycle_id']);
        foreach ($forms as $form) {
            $bg_text = ($form['percentage'] >= 100) ? 'text-navy' : 'text-danger';
            echo '<tr>';
            echo '<td>' . $form['activity_name'] . '</td>';
            echo '<td class="font-bold align-middle">' . $form['form_name'] . '</td>';
            echo '<td  class="text-center align-middle">' . $form['tot_target'] . '</td>';
            echo '<td  class="text-center align-middle">' . $form['tot_actual'] . '</td>';
            echo '<td  class="text-center align-middle ' . $bg_text . ' font-bold">' . $form['percentage'] . '%</td>';
            //add colspan if barangay//
            if ($form['form_type'] == 'barangay') {
                $colspan = $app->checklistColspanIpcdd($_POST['cadt_id'], $_POST['cycle_id']);
                echo '<td colspan="' . $colspan . '"></td>';
            }

            //load per municipal and barangay data//
            $perRows = $app->checklistRowBrgyIpcdd($_POST['cadt_id'], $_POST['cycle_id'], $form['fk_form']);
            if ($perRows) {
                foreach ($perRows as $perRow) {
                    $bg_background = ($perRow['actual'] >= 1) ? 'bg-white' : 'bg-danger';
                    if ($perRow['form_type'] == 'municipal') {
                        echo '<td class="text-center align-middle ' . $bg_background . '">' . $perRow['actual'] . '</td>';
                    } elseif ($perRow['form_type'] == 'barangay') {
                        echo '<td class="text-center align-middle ' . $bg_background . '">' . $perRow['actual'] . '</td>';
                    }else{
                        echo '<td>n/a</td>';
                    }
                }
            }
        }
        ?>
        </tbody>
    </table>
</div>
<script>
    var tableToExcel = (function () {
        var uri = 'data:application/vnd.ms-excel;base64,'
        template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><meta charset="utf-8"/><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'            , base64 = function (s) {
            return window.btoa(unescape(encodeURIComponent(s)))
        }
            , format = function (s, c) {
            return s.replace(/{(\w+)}/g, function (m, p) {
                return c[p];
            })
        }
        return function (table, name) {
            if (!table.nodeType) table = document.getElementById(table)
            var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}

            document.getElementById("dlink").href = uri + base64(format(template, ctx));
            document.getElementById("dlink").download = '<?php echo $title; ?>';
            document.getElementById("dlink").click();
        }
    })()
</script>