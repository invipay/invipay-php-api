<?php

/**
*	Copyright (C) 2016 inviPay.com
*	
*	http://www.invipay.com
*
*	@author Kuba Pilecki (kpilecki@invipay.com)
* 	@version 2.1
*
*	Redistribution and use in source and binary forms, with or
*	without modification, are permitted provided that the following
*	conditions are met: Redistributions of source code must retain the
*	above copyright notice, this list of conditions and the following
*	disclaimer. Redistributions in binary form must reproduce the above
*	copyright notice, this list of conditions and the following disclaimer
*	in the documentation and/or other materials provided with the
*	distribution.
*	
*	THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESS OR IMPLIED
*	WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
*	MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN
*	NO EVENT SHALL CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
*	INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
*	BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS
*	OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
*	ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR
*	TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE
*	USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH
*	DAMAGE.
*/

class PayoffCost
{
	protected $transactionId;
	protected $cost;
	protected $costCurrency;
	protected $payoffValue;
	protected $payoffValueCurrency;
	protected $totalValue;
	protected $totalValueCurrency;
	protected $canPayoff;

	public function getTransactionId() { return $this->transactionId; }
	public function setTransactionId($transactionId) { $this->transactionId = $transactionId; }

	public function getCost() { return $this->cost; }
	public function setCost($cost){ $this->cost = $cost; }

	public function getCostCurrency() { return $this->costCurrency; }
	public function setCostCurrency($costCurrency) { $this->costCurrency = $costCurrency; }

	public function getPayoffValue() { return $this->payoffValue; }
	public function setPayoffValue($payoffValue){ $this->payoffValue = $payoffValue; }

	public function getPayoffValueCurrency() { return $this->payoffValueCurrency; }
	public function setPayoffValueCurrency($payoffValueCurrency) { $this->payoffValueCurrency = $payoffValueCurrency; }

	public function getTotalValue() { return $this->totalValue; }
	public function setTotalValue($totalValue){ $this->totalValue = $totalValue; }

	public function getTotalValueCurrency() { return $this->totalValueCurrency; }
	public function setTotalValueCurrency($totalValueCurrency) { $this->totalValueCurrency = $totalValueCurrency; }

	public function getCanPayoff() { return $this->canPayoff; }
	public function setCanPayoff($canPayoff){ $this->canPayoff = $canPayoff; }
}

?>