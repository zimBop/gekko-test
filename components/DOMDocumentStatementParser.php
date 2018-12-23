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
                    || $operationTypeTd->nodeValue !== 'buy') {
                continue;
            }
           
            date_default_timezone_set('UTC');
            $dateTime = \DateTime::createFromFormat('Y.m.d H:i:s', $cells->item(1)->nodeValue);
            if ($dateTime === false) {
                continue;
            }
            $profit += floatval($row->lastChild->nodeValue);
            $result[] = [
               'time' => $dateTime->format(\DateTime::ATOM),
               'profit' => $profit
            ];
        }
        
        return $result;
    }

}