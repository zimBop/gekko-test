<?php

namespace app\components;

use \app\components\StatementParser;

class DOMDocumentStatementParser implements StatementParser
{
    
    public function getTransactions(string $html): array
    {
        $dom = new \DOMDocument();
        $dom->loadHTML($html);
        $tables = $dom->getElementsByTagName('table'); 
        $rows = $tables->item(0)->getElementsByTagName('tr');
        $result = [];
        $profit = 0;
        foreach ($rows as $row) {
            $cells = $row->getElementsByTagName('td');
            $operationTypeTd = $cells->item(2);
            if ($operationTypeTd === null
                    || !in_array($operationTypeTd->nodeValue, ['buy', 'balance', 'sell'])) {
                continue;
            }
           
            date_default_timezone_set('UTC');
            $dateTime = $this->makeDateTime($cells->item(1)->nodeValue);
            if ($dateTime === false) {
                continue;
            }
            $profit += floatval(str_replace(' ', '', $row->lastChild->nodeValue));
            $result[] = [
               'time' => $dateTime->format(\DateTime::ATOM),
               'profit' => $profit
            ];
        }

        return $result;
    }
    
    protected function makeDateTime($dateString) {
        $dateTime = \DateTime::createFromFormat('Y.m.d H:i:s', $dateString);
        if ($dateTime === false) {
            $dateTime = \DateTime::createFromFormat('Y.m.d H:i', $dateString);
        }
        
        return $dateTime;
    }

}