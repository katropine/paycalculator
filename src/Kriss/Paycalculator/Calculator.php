<?php
namespace Kriss\Paycalculator;
/**
 * Description of Calculator
 *
 * @author kriss
 * @since Mar 12, 2017
 */
class Calculator {
    
    protected $people;
    protected $taxTable;

    
    public function __construct($people, $taxTable) {
        $this->people = $people;
        $this->taxTable = $taxTable;
    }

    public function calculate() {
        
        usort($this->taxTable, function ($a1, $a2) {
            return $a1->getStartPrice() > $a2->getStartPrice();
        });
        
        $output = [];
        foreach ($this->people as $payee) {
            $output[] = $this->calculateTaxPerMonth($payee);
        }
        
        return $output;
    }
    
    protected function calculateTaxPerMonth($payee){
        
        $accumulatedTax = 0;
        $taxPerMonth = 0;

        $grossPerMonth = round($payee->getAnnualSalary() / 12);
        $superPerMonth = round($grossPerMonth * $payee->getSuper());
        
        $lastTopEndPrice = 0;
        foreach ($this->taxTable as $taxClass) {
            if (!$taxClass->getEndPrice() || ($taxClass->getEndPrice() > $payee->getAnnualSalary())) {
                // top chunk of the income.
                $taxPerMonth = round(($accumulatedTax + ($payee->getAnnualSalary() - $lastTopEndPrice) * $taxClass->getTax())/12);
                break;
            } else {
                // tax so far
                $accumulatedTax += $taxClass->calculateTax();
                $lastTopEndPrice = $taxClass->getEndPrice();
            }
        }
        $netPerMonth = $grossPerMonth - $taxPerMonth;
        
        
        $bd = new Model\SalaryBrakeDown();
        $bd->setPayee($payee);
        $bd->setNet($netPerMonth);
        $bd->setGross($grossPerMonth);
        $bd->setSuper($superPerMonth);
        $bd->setTax($taxPerMonth);
        return $bd;
    }
    
    
}
