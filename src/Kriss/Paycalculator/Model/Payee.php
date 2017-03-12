<?php
namespace Kriss\Paycalculator\Model;
/**
 * Description of Payee
 *
 * @author kriss
 * @since Mar 12, 2017
 */
class Payee {
    
    protected $firstname;
    protected $lastname;
    protected $annualSalary;
    protected $super;
    protected $startDate;
    protected $endDate;
    
    
    public function getFirstname() {
        return $this->firstname;
    }

    public function getLastname() {
        return $this->lastname;
    }

    public function getAnnualSalary() {
        return $this->annualSalary;
    }

    public function getSuper() {
        return $this->super;
    }

    public function getStartDate() {
        return $this->startDate;
    }

    public function getEndDate() {
        return $this->endDate;
    }

    public function setFirstname($firstname) {
        $this->firstname = $firstname;
        return $this;
    }

    public function setLastname($lastname) {
        $this->lastname = $lastname;
        return $this;
    }

    public function setAnnualSalary($annualSalary) {
        $this->annualSalary = $annualSalary;
        return $this;
    }

    public function setSuper($super) {
        $this->super = $super;
        return $this;
    }

    public function setStartDate($startDate) {
        $this->startDate = $startDate;
        return $this;
    }

    public function setEndDate($endDate) {
        $this->endDate = $endDate;
        return $this;
    }
    
    public function getFullname(){
        return ltrim(rtrim($this->firstname." ".$this->lastname));
    }
    
    public function getPeriod($format = null) {
        if (empty($format)){
            $format = "Y-m-d H:i:s";
        } 
        return $this->startDate->format($format). " - ". $this->endDate->format($format);
    }
}
