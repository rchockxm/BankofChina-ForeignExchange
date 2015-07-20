<?php
require("bankofchina.class.php");

$BankofChina = new \ForeignExchangeCls\BankofChina();

$startDate = date("Y-m-d");
$endDate = date("Y-m-d");
$symbol = "USD";

print_r( $BankofChina->getExchangeRate($startDate, $endDate, $symbol) );
?>
