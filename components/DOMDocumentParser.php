<?php

namespace app\components;

use \app\components\Parser;

class DOMDocumentParser implements Parser
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
           
            $profit += floatval($row->lastChild->nodeValue);
            $result[] = [
               'time' => $cells->item(1)->nodeValue,
               'profit' => $profit
            ];
        }
        return $result;
    }

}