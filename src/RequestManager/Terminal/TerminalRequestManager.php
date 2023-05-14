<?php

namespace App\RequestManager\Terminal;

use App\Dto\Request\TerminalFilterDto;
use App\RequestManager\AbstractRequestManager;
use GuzzleHttp\Exception\GuzzleException;
use Phos\Exception\ApiException;

class TerminalRequestManager extends AbstractRequestManager
{
    protected const SERVICE = 'TERMINAL_SERVICE';

    /**
     * @param array $filter
     *
     * @return bool|false|mixed|string
     *
     * @throws ApiException
     * @throws \JsonException
     * @throws GuzzleException
     */
    public function find(array $filter)
    {
        $filter['groups'] = 'config';
        $filter['secure'] = 1;

        return $this->get('/api/terminals', [], $filter);
    }

    /**
     * @param int $page
     * @param int $limit
     * @param TerminalFilterDto|null $filters
     *
     * @return mixed
     *
     * @throws ApiException
     * @throws GuzzleException
     */
    public function getAll(int $page, int $limit, ?TerminalFilterDto $filters = null)
    {
        $filters = $this->normalize($filters);
        $filters['groups'] = 'config';

        return $this->get('/api/terminals/list/{page}/{limit}?', compact('page', 'limit'), $filters);
    }

    public function findByUser(array $filters)
    {
        $filters['groups'] = 'store,mcc,show';
        $filters['secure'] = 1;

        return $this->get('/api/terminals', [], $filters);
    }
}
