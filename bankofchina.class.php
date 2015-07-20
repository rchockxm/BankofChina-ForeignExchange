<?php
/**
 * BankofChina-ForeignExchange
 * Created by Silence Unlimited
 * Developer: Rchockxm (rchockxm.silver@gmail.com)
 * FileName: bankofchina.class.php
 */
namespace ForeignExchangeCls;

/**
 * BankofChina
 */
class BankofChina {
    
    private $m_Url = "http://srh.bankofchina.com/search/whpj/search.jsp";
    
    private $m_Symbols = array(
        "GBP" => "1314",
        "HKD" => "1315",
        "USD" => "1316",
        "CHF" => "1317",
        "DEM" => "1318",
        "FRF" => "1319",
        "SGD" => "1375",
        "SEK" => "1320",
        "DKK" => "1321",
        "NOK" => "1322",
        "JPY" => "1323",
        "CAD" => "1324",
        "AUD" => "1325",
        "EUR" => "1326",
        "MOP" => "1327",
        "PHP" => "1328",
        "THB" => "1329",
        "NZD" => "1330",
        "KRW" => "1331",
        "RUB" => "1843",
        "MYR" => "2890",
        "TWD" => "2895",
        "ESP" => "1370",
        "ITL" => "1371",
        "NLG" => "1372",
        "BEF" => "1373",
        "FIM" => "1374",
        "IDR" => "3030",
        "BRL" => "3253"
    );
    
    /**
     * getSymbolID()
     * @type public
     * @args $symbolName
     * @return Success - string
     *         Fail - empty string
     */
    public function getSymbolID($symbolName) {
        return (array_key_exists(strtoupper($symbolName), $this->m_Symbols) ? $this->m_Symbols[$symbolName] : "");
    }
    
    /**
     * getMinCent()
     * @type public
     * @args $value
     * @return Success - double
     *         Fail - double
     */
    public function getMinCent($value) {
        return (!empty($value)) ? round($value / 100, 2) : 0.00;
    }
    
    /**
     * getExchangeRate()
     * @type public
     * @args $dateStart, $dateEnd, $symbolName
     * @return Success - array
     *         Fail - null
     */
    public function getExchangeRate($dateStart, $dateEnd, $symbolName) {
        $result = null;
        
        $postData = http_build_query(
            array(
                "erectDate" => date("Y-m-d", strtotime($dateStart)),
                "nothing" => date("Y-m-d", strtotime($dateEnd)),
                "pjname" => $this->getSymbolID($symbolName)
            )
        );

        $opts = array(  
            "http" => array(  
                "method" => "POST",
                "timeout" => 60,
                "content" => $postData
            )  
        );  
        
        $context = stream_context_create($opts); 
        
        $html = "";
        
        try {
            $html = file_get_contents($this->m_Url, false, $context);
            $html = utf8_encode($html);
        } 
        catch (Exception $e) {
            throw new Exception("Caught exception: " . $e->getMessage() . "\n");
        }
        
        $dom = null;
        $rows = null;
        
        if (!empty($html)) {
            $dom = new \domDocument;
            $dom->loadHTML($html);
            $dom->preserveWhiteSpace = false;
        }
        
        if (is_object($dom)) {
            $tables = $dom->getElementsByTagName("table");
            
            if (is_object($tables) && $tables->length == 2) {
                $rows = $tables->item(1)->getElementsByTagName("tr");
            }
            
            $tables = null;
        }
        
        if (is_object($rows) && $rows->length > 0) {
            $cols = $rows->item(1)->getElementsByTagName("td");
            
            if (is_object($cols) && $cols->length > 0) {
                $result = array(
                    "BuyingRate" => $this->getMinCent($cols->item(1)->nodeValue),
                    "CashBuyingRate" => $this->getMinCent($cols->item(2)->nodeValue),
                    "SellingRate" => $this->getMinCent($cols->item(3)->nodeValue),
                    "CashSellingRate" => $this->getMinCent($cols->item(4)->nodeValue),
                    "StandardPrice" => $this->getMinCent($cols->item(5)->nodeValue),
                    "CenterLinePrice" => $this->getMinCent($cols->item(6)->nodeValue),
                    "DateTime" => $cols->item(7)->nodeValue
                );
            }
            
            $cols = null;
        }
        
        $rows = null;
        $dom = null;
        $html = null;
        
        $context = null;
        $opts = null;
        $postData = null;
        
        return $result;
    }
    
    /**
     * getExchangeRates()
     * @type public
     * @args $dateStart, $dateEnd, $symbolName, [optional $page]
     * @return Success - array
     *         Fail - null
     */
    public function getExchangeRates($dateStart, $dateEnd, $symbolName, $page = 1) {
        $result = null;
        
        $postData = http_build_query(
            array(
                "erectDate" => date("Y-m-d", strtotime($dateStart)),
                "nothing" => date("Y-m-d", strtotime($dateEnd)),
                "pjname" => $this->getSymbolID($symbolName),
                "page" => (int)$page
            )
        );

        $opts = array(  
            "http" => array(  
                "method" => "POST",
                "timeout" => 60,
                "content" => $postData
            )  
        );  
        
        $context = stream_context_create($opts); 
        
        $html = "";
        
        try {
            $html = file_get_contents($this->m_Url, false, $context);
            $html = utf8_encode($html);
        } 
        catch (Exception $e) {
            throw new Exception("Caught exception: " . $e->getMessage() . "\n");
        }
        
        $dom = null;
        $rows = null;
        
        if (!empty($html)) {
            $dom = new \domDocument;
            $dom->loadHTML($html);
            $dom->preserveWhiteSpace = false;
        }
        
        if (is_object($dom)) {
            $tables = $dom->getElementsByTagName("table");
            
            if (is_object($tables) && $tables->length == 2) {
                $rows = $tables->item(1)->getElementsByTagName("tr");
            }
            
            $tables = null;
        }
        
        if (is_object($rows) && $rows->length > 0) {
            $result = array();
            
            foreach ($rows as $row) {
                $cols = $row->getElementsByTagName("td");
                
                if (is_object($cols) && $cols->length > 0) {
                    if (empty($cols->item(1)->nodeValue)) {
                        continue;
                    }
                    
                    $result[] = array(
                        "BuyingRate" => $this->getMinCent($cols->item(1)->nodeValue),
                        "CashBuyingRate" => $this->getMinCent($cols->item(2)->nodeValue),
                        "SellingRate" => $this->getMinCent($cols->item(3)->nodeValue),
                        "CashSellingRate" => $this->getMinCent($cols->item(4)->nodeValue),
                        "StandardPrice" => $this->getMinCent($cols->item(5)->nodeValue),
                        "CenterLinePrice" => $this->getMinCent($cols->item(6)->nodeValue),
                        "DateTime" => $cols->item(7)->nodeValue
                    );
                }
                
                $cols = null;
            }
        }
        
        $rows = null;
        $dom = null;
        $html = null;
        
        $context = null;
        $opts = null;
        $postData = null;
        
        return $result;
    }
}
?>
