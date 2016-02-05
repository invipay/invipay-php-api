<?php

/**
*	Copyright (C) 2016 inviPay.com
*	
*	http://www.invipay.com
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

require_once(dirname(__FILE__) ."/TransactionType.enum.php");
require_once(dirname(__FILE__) ."/TransactionSide.enum.php");

class TransactionsFilter
{
	protected $type = TransactionType::INVOICE;
	protected $side = TransactionSide::PURCHASE;
	protected $noRiskState = null;
	protected $contractorTaxPayerNumber = null;
	protected $contractorCompanyGovId = null;

	public function getType() { return $this->type; }
	public function setType(TransactionType $type){ $this->type = $type; }

	public function getSide() { return $this->side; }
	public function setSide($side){ $this->side = $side; }

	public function getNoRiskState(){ return $this->noRiskState; }
	public function setNoRiskState($noRiskState){ $this->noRiskState = $noRiskState; }

	public function getContractorTaxPayerNumber(){ return $this->contractorTaxPayerNumber; }
	public function setContractorTaxPayerNumber($contractorTaxPayerNumber){ $this->contractorTaxPayerNumber = $contractorTaxPayerNumber; }

	public function getContractorCompanyGovId(){ return $this->contractorCompanyGovId; }
	public function setContractorCompanyGovId($contractorCompanyGovId){ $this->contractorCompanyGovId = $contractorCompanyGovId; }
}

?>