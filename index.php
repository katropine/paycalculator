<?php
require_once "./vendor/autoload.php";

/**
 * Description of Test
 *
 * @author kriss
 * @since Mar 12, 2017
 */
use Kriss\Paycalculator\Model\Payee;
use Kriss\Paycalculator\Model\TaxGrade;

$year = '2012';

if (isset($_POST) && isset($_FILES['csvinput'])) {

    $taxTable = [];
    $input = [];

    foreach ($_POST['start'] as $k => $v) {
        $tg = new TaxGrade();
        $tg->setStartPrice((int) $v);
        $tg->setEndPrice((int) $_POST['end'][$k]);
        $tg->setTax((float) $_POST['tax'][$k] / 100);
        $taxTable[] = $tg;
    }

    //var_dump($_FILES['csvinput']); exit;
    $csv = array_map('str_getcsv', file($_FILES['csvinput']['tmp_name']));
    foreach ($csv as $k => $row) {
        if ($k > 0) {
            $date = explode('-', $row[4]);

            $startDate = new \DateTime(rtrim($date[0]) . " {$year}");
            $endDate = new \DateTime(ltrim($date[1]) . " {$year}");
            $super = str_replace('%', '', $row[3]) / 100;

            $person = new Payee();
            $person->setFirstname($row[0]);
            $person->setLastname($row[1]);
            $person->setAnnualSalary((int) $row[2]);
            $person->setSuper($super);
            $person->setStartDate($startDate);
            $person->setEndDate($endDate);
            $input[] = $person;
        }
    }



    $calculator = new \Kriss\Paycalculator\Calculator($input, $taxTable);
    $output = $calculator->calculate();

    $myOutput = [];
    foreach ($output as $out) {
        $myOutput[] = [
            $out->getPayee()->getFullname(),
            $out->getPayee()->getPeriod("d F"),
            (int) $out->getGross(),
            (int) $out->getTax(),
            (int) $out->getNet(),
            (int) $out->getSuper()
        ];
    }
    $csvTitles = ['name', 'pay period', 'gross income', 'income tax', 'net income', 'super'];

    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=output.csv");
    header("Pragma: no-cache");
    header("Expires: 0");
    echo implode(',', $csvTitles) . "\r";
    foreach ($myOutput as $row) {
        echo implode(',', $row) . "\r";
    }
    exit;
}
?>
<!doctype html>
<html lang="en">
    <head>
        <title>AUS Pay Calculator - Kristian Beres</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    </head>
    <body>
        <div class="container">
            <form action="" method="post" enctype="multipart/form-data">
                <table class="table">
                    <tr>
                        <td>
                            Select CSV file:
                            <input class="btn btn-default" name="csvinput" type="file">
                        </td>
                        <td>
                            Tax table:
                            <table>
                                <tr>
                                    <td>
                                        <input class="form-control" type="text" name="start[]" value="0"> 
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" name="end[]" value="18200">
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" name="tax[]" value="0">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input class="form-control" type="text" name="start[]" value="18201"> 
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" name="end[]" value="37000">
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" name="tax[]" value="19">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input class="form-control" type="text" name="start[]" value="37001"> 
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" name="end[]" value="80000">
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" name="tax[]" value="32.5">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input class="form-control" type="text" name="start[]" value="80001"> 
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" name="end[]" value="180000">
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" name="tax[]" value="37">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input class="form-control" type="text" name="start[]" value="180001"> 
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" name="end[]" value="">
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" name="tax[]" value="45">
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>
                    <tr>
                        <td><input class="btn btn-default" type="submit" value="Calculate"></td>
                    </tr>
                </table>
            </form>
        </div>
    </body>
</html>