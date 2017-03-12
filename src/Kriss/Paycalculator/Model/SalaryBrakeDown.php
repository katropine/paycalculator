<?php

namespace Kriss\Paycalculator\Model;

/**
 * Description of SalaryBrakeDown
 *
 * @author kriss
 * @since Mar 12, 2017
 */
class SalaryBrakeDown {
    // calculated prices
    protected $gross;
    protected $net;
    protected $tax;
    protected $super;
    protected $payee;
    
    public function getGross() {
        return $this->gross;
    }

    public function getNet() {
        return $this->net;
    }

    public function getTax() {
        return $this->tax;
    }

    public function getSuper() {
        return $this->super;
    }

    public function getPayee() {
        return $this->payee;
    }

    public function setGross($gross) {
        $this->gross = $gross;
        return $this;
    }

    public function setNet($net) {
        $this->net = $net;
        return $this;
    }

    public function setTax($tax) {
        $this->tax = $tax;
        return $this;
    }

    public function setSuper($super) {
        $this->super = $super;
        return $this;
    }

    public function setPayee($payee) {
        $this->payee = $payee;
        return $this;
    }
}
