<?php
namespace Tests;
require_once "../vendor/autoload.php";
/**
 * Description of Test
 *
 * @author kriss
 * @since Mar 12, 2017
 */

use Kriss\Paycalculator\Model\Payee;
use Kriss\Paycalculator\Model\TaxGrade;

class Test extends \PHPUnit_Framework_TestCase{
    
    protected $people = [];
    protected $taxTable = [];

    public function setUp(){
        
        $csv = array_map('str_getcsv', file('./data/input.csv'));
        $year = '2012';
        foreach ($csv as $k=>$row) {
            if ($k > 0) {
                $date = explode('-', $row[4]);
                
                $startDate = new \DateTime(rtrim($date[0])." {$year}");
                $endDate = new \DateTime(ltrim($date[1])." {$year}");
                $super = str_replace('%', '', $row[3]) / 100;
                
                $person = new Payee();
                $person->setFirstname($row[0]);
                $person->setLastname($row[1]);
                $person->setAnnualSalary((int)$row[2]);
                $person->setSuper($super);
                $person->setStartDate($startDate);
                $person->setEndDate($endDate);
                $this->people[] = $person;
            }    
        }
        
        $csvTax = array_map('str_getcsv', file('./data/taxtable.csv'));
        foreach ($csvTax as $k=>$row) {
            if ($k > 0) {
                $tax = new TaxGrade();
                $tax->setStartPrice($row[0]);
                $tax->setEndPrice($row[1]);
                $tax->setTax($row[2]/100);
                
                $this->taxTable[] = $tax;
            }
        }
        // we sort the taxgrades from lowest to highest
        usort($this->taxTable, function ($a1, $a2) {
            return $a1->getStartPrice() > $a2->getStartPrice();
        });
        
        $csvExpected = array_map('str_getcsv', file('./data/output.csv'));
        foreach ($csvExpected as $k=>$row) {
            if ($k > 0) {
                $this->outputExpected[] = [
                    $row[0],
                    $row[1],
                    (int) $row[2],
                    (int) $row[3],
                    (int) $row[4],
                    (int) $row[5],
                ];
            }
        }  
    }
    
    public function testFirst(){
        
        $calculator = new \Kriss\Paycalculator\Calculator($this->people, $this->taxTable);
        $output = $calculator->calculate();
        
        $myOutput = [];
        foreach ($output as $out) {
            $myOutput[] = [
                $out->getPayee()->getFullname(),
                $out->getPayee()->getPeriod("d F"),
                (int)$out->getGross(),
                (int)$out->getTax(),
                (int)$out->getNet(),
                (int)$out->getSuper()
            ];
        } 
        
        $this->assertEquals($this->outputExpected, $myOutput);   
    }
}
