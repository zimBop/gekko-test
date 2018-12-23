<?php

namespace app\components;

interface Parser {
    public function getTransactions(string $html): array;
}