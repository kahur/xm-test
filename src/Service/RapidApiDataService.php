<?php

namespace App\Service;

use App\Http\Request\RapidApi\FinanceHistoryRequest;

class RapidApiDataService
{
    public function __construct(private FinanceHistoryRequest $request) {}

    public function getData(string $symbol, string $startDate, string $endDate, string $region = 'US'): iterable
    {
        $data = $this->request->execute(['symbol' => $symbol, 'region' => $region]);

        $data = json_decode($data, true);

        if (!$data || empty($data)) {
            return [];
        }

        return $this->filterDataByDateRange($data['prices'], $startDate, $endDate);
    }

    protected function filterDataByDateRange(array $data, string $startDate, string $endDate): iterable
    {
        $startTimestamp = new \DateTime($startDate);
        $endTimestamp = new \DateTime($endDate);

        $filter = [];

        foreach ($data as $historyItem) {
            if ($this->isWithinRange($startTimestamp, $endTimestamp, $historyItem['date'])) {
                 array_push($filter, $historyItem);
            }
        }

        return $filter;
    }

    protected function isWithinRange(\DateTime $start, \DateTime $end, int $timestamp): bool
    {
        return $timestamp >= $start->getTimestamp() && $timestamp <= $end->getTimestamp();
    }
}
