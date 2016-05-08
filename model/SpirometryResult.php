<?php

/* Simple class to hold measurement results
 */
class SpirometryResult {

	private $vcIN;
	private $fvc;
	private $fev1;
	private $fev1VCMax;
	private $PEF;
	private $MEF75;
	private $MEF50;
	private $MEF25;

	function __construct() {

	}

   // Getters

   public function getVcIN() {
   	 return $this->$vcIN;
   }

   public  function getFvc() {
   	 return $this->$fvc;
   }

   public function getFev1() {
   	 return $this->$fev1;
   }

   public function getFev1VCMax() {
   	 return $this->$fev1VCMax;
   }

   public function getPef() {
   	 return $this->$PEF;
   }

   public function getMEF75() {
   	 return $this->MEF75;
   }

   public function getMEF50() {
   	 return $this->MEF50;
   }

   public function getMEF25() {
   	 return $this->MEF25;
   }

   // Setters
   
   public function setVcIN($vcIN) {
   	 $this->$vcIN = $vcIN;
   }

   public function setFvc($fvc) {
   	 $this->$fvc = $fvc;
   }

   public function setFev1($fev1) {
   	 $this->$fev1 = $fev1;
   }

   public function setFev1VCMax($fev1VCMax) {
   	 $this->$fev1VCMax = $fev1VCMax;
   }

   public function setPef($PEF) {
   	 $this->$PEF = $PEF;
   }

   public function setMEF75($MEF75) {
   	 $this->MEF75 = $MEF75;
   }

   public function setMEF50($MEF50) {
   	 $this->MEF50 = $MEF50;
   }

   public function setMEF25($MEF25) {
   	 $this->MEF25 = $MEF25;
   }


}

?>