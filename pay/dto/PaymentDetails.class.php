<?php

/**
*	Copyright (C) 2016 inviPay.com
*	
*	http://www.invipay.com
*
*	@author Kuba Pilecki (kpilecki@invipay.com)
* 	@version 2.0
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

require_once(dirname(__FILE__) ."/../../common/dto/Contractor.class.php");
require_once(dirname(__FILE__) ."/../../common/dto/EmployeeDetails.class.php");

class PaymentDetails
{
    protected $paymentId;
    protected $documentNumber;
    protected $issueDate;
    protected $dueDate;
    protected $priceGross;
    protected $currency = "PLN";
    protected $note;
    protected $noRisk = null;
    protected $isInvoice = false;
    protected $buyer;
    protected $employees = array();

    public function getPaymentId() { return $this->paymentId; }
    public function setPaymentId($paymentId) { $this->paymentId = $paymentId; }

    public function getDocumentNumber(){ return $this->documentNumber; }
    public function setDocumentNumber($documentNumber){ $this->documentNumber = $documentNumber; }

    public function getDueDate(){ return $this->dueDate; }
    public function setDueDate($dueDate){ $this->dueDate = $dueDate; }

    public function getIssueDate(){ return $this->issueDate; }
    public function setIssueDate($issueDate){ $this->issueDate = $issueDate; }

    public function getPriceGross(){ return $this->priceGross; }
    public function setPriceGross($priceGross){ $this->priceGross = $priceGross; }

    public function getCurrency(){ return $this->currency; }
    public function setCurrency($currency){ $this->currency = $currency; }
    
    public function getNote(){ return $this->note; }
    public function setNote($note){ $this->note = $note; }

    public function getNoRisk(){ return $this->noRisk; }
    public function setNoRisk($noRisk){ $this->noRisk = $noRisk; }

    public function getIsInvoice(){ return $this->isInvoice; }
    public function setIsInvoice($isInvoice){ $this->isInvoice = $isInvoice; }

    public function getBuyer(){ return $this->buyer; }
    public function setBuyer(Contractor $buyer = null){ $this->buyer = $buyer; }

    public function getEmployees() { return $this->employees; }
    public function setEmployees(array $employees, EmployeeDetails $typeHint = null) { $this->employees = $employees; }
}

?>