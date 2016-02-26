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

require_once(dirname(__FILE__) ."/ProtectionResult.class.php");

class ProtectionResults
{
	protected $operationId;
	protected $itemsLeft;
	protected $startedAt;
	protected $completedAt;
	protected $items;

	public function getOperationId(){ return $this->operationId; }
	public function setOperationId($operationId){ $this->operationId = $operationId; }

	public function getItemsLeft(){ return $this->itemsLeft; }
	public function setItemsLeft($itemsLeft){ $this->itemsLeft = $itemsLeft; }

	public function getStartedAt(){ return $this->startedAt; }
	public function setStartedAt($startedAt){ $this->startedAt = $startedAt; }

	public function getCompletedAt(){ return $this->completedAt; }
	public function setCompletedAt($completedAt) { $this->completedAt = $completedAt; }

	public function getItems(){ return $this->items; }
	public function setItems(array $items = null, ProtectionResult $itemTypeHint = null){ $this->items = $items; }
}

?>