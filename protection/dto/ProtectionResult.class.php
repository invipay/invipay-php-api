<?php

/**
*	Copyright (C) 2016 inviPay.com
*	
*	http://www.invipay.com
*
*	@author Kuba Pilecki (kpilecki@invipay.com)
* 	@version 1.0.5
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

require_once(dirname(__FILE__) ."/ProtectionStatus.enum.php");

class ProtectionResult
{
	protected $documentNumber;
	protected $priceGross;
	protected $currency;
	protected $exId;
	protected $transactionId;
	protected $status;
	protected $message;

	public function getDocumentNumber(){ return $this->documentNumber; }
	public function setDocumentNumber($documentNumber){ $this->documentNumber = $documentNumber; }

	public function getPriceGross(){ return $this->priceGross; }
	public function setPriceGross($priceGross){ $this->priceGross = $priceGross; }

	public function getCurrency() { return $this->currency; }
	public function setCurrency($currency) { $this->currency = $currency; }

	public function getExId(){ return $this->exId; }
	public function setExId($exId){ $this->exId = $exId; }

	public function getTransactionId(){ return $this->transactionId; }
	public function setTransactionId($transactionId){ $this->transactionId = $transactionId; }

	public function getStatus(){ return $this->status; }
	public function setStatus($status){ $this->status = $status; }

	public function getMessage(){ return $this->message; }
	public function setMessage($message){ $this->message = $message; }
}

?>