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

   function getVcIN() {
   	 return $this->$vcIN;
   }

   function getFvc() {
   	 return $this->$fvc;
   }

   function getFev1() {
   	 return $this->$fev1;
   }

   function getFev1VCMax() {
   	 return $this->$fev1VCMax;
   }

   function getPef() {
   	 return $this->$PEF;
   }

   function getMEF75() {
   	 return $this->MEF75;
   }

   function getMEF50() {
   	 return $this->MEF50;
   }

   function getMEF25() {
   	 return $this->MEF25;
   }

   // Setters
   
   function setVcIN($vcIN) {
   	 $this->$vcIN = $vcIN;
   }

   function setFvc($fvc) {
   	 $this->$fvc = $fvc;
   }

   function setFev1($fev1) {
   	 $this->$fev1 = $fev1;
   }

   function setFev1VCMax($fev1VCMax) {
   	 $this->$fev1VCMax = $fev1VCMax;
   }

   function setPef($PEF) {
   	 $this->$PEF = $PEF;
   }

   function setMEF75($MEF75) {
   	 $this->MEF75 = $MEF75;
   }

   function setMEF50($MEF50) {
   	 $this->MEF50 = $MEF50;
   }

   function setMEF25($MEF25) {
   	 $this->MEF25 = $MEF25;
   }


}

?>