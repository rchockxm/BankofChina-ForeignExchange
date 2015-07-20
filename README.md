# BankofChina-ForeignExchange
==========

PHP library for BankofChina ForeignExchange

<img src="https://img.shields.io/dub/l/vibe-d.svg" />

<h2><a name="about" class="anchor" href="#about"><span class="mini-icon mini-icon-link"></span></a>About</h2>

Foreign Exchange Rate from <a href="http://srh.bankofchina.com/search/whpj/search.jsp">here</a>.

<h2><a name="require" class="anchor" href="#require"><span class="mini-icon mini-icon-link"></span></a>Require</h2>

* PHP 5.3+
* DOMDocument Extensions

<h2><a name="usage" class="anchor" href="#usage"><span class="mini-icon mini-icon-link"></span></a>Usage</h2>

Single data

```php
require("bankofchina.class.php");

$BankofChina = new \ForeignExchangeCls\BankofChina();

$startDate = date("Y-m-d");
$endDate = date("Y-m-d");
$symbol = "USD";

print_r( $BankofChina->getExchangeRate($startDate, $endDate, $symbol) );
```

Multi data

```php
require("bankofchina.class.php");

$BankofChina = new \ForeignExchangeCls\BankofChina();

$startDate = date("Y-m-d");
$endDate = date("Y-m-d");
$symbol = "USD";
$page = 2;

print_r( $BankofChina->getExchangeRate($startDate, $endDate, $symbol, $page) );
```

<h2><a name="author" class="anchor" href="#author"><span class="mini-icon mini-icon-link"></span></a>Author</h2>
* 2015 rchockxm (rchockxm.silver@gmail.com)

<h2><a name="credits" class="anchor" href="#credits"><span class="mini-icon mini-icon-link"></span></a>Credits</h2>
