<?php

namespace app\components;

interface StatementParser {
    public function getTransactions(string $html): array;
}