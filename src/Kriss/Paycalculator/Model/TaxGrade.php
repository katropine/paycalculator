<?php
namespace Kriss\Paycalculator\Model;
/**
 * Description of TaxGrade
 *
 * @author kriss
 * @since Mar 12, 2017
 */
class TaxGrade {
    
    public $startPrice;
    public $endPrice;
    public $tax;
    
    public function getStartPrice() {
        return $this->startPrice;
    }

    public function getEndPrice() {
        return $this->endPrice;
    }

    public function getTax() {
        return $this->tax;
    }

    public function setStartPrice($startPrice) {
        $this->startPrice = $startPrice;
        return $this;
    }

    public function setEndPrice($endPrice) {
        $this->endPrice = $endPrice;
        return $this;
    }

    public function setTax($tax) {
        $this->tax = $tax;
        return $this;
    }
    
    /**
     * tax for full range
     */
    public function calculateTax(){
        if ($this->tax > 0) {
            return abs($this->endPrice - $this->startPrice)*$this->tax;
        }
        return 0;
    }

}
