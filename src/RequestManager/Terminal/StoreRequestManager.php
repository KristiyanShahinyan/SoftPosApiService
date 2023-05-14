<?php

namespace App\RequestManager\Terminal;

use App\Dto\Request\StoreFilterDto;
use App\RequestManager\AbstractRequestManager;
use GuzzleHttp\Exception\GuzzleException;
use Phos\Exception\ApiException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class StoreRequestManager
 * @package App\Controller\V2\Terminal
 */
class StoreRequestManager extends AbstractRequestManager
{
    /**
     *
     */
    protected const SERVICE = 'TERMINAL_SERVICE';

    /**
     * @param int $page
     * @param int $limit
     * @param StoreFilterDto|null $filter
     *
     * @return mixed|ResponseInterface
     *
     * @throws GuzzleException
     * @throws ApiException
     */
    public function getAll(int $page, int $limit, ?StoreFilterDto $filter)
    {
        return $this->get('/api/stores/list/{page}/{limit}', compact('page', 'limit'), $this->normalize($filter) ?? []);
    }

    /**
     * @param string $token
     * @return bool|false|mixed|string
     */
    public function find(string $token)
    {
        return $this->cache->get('stores:' . $token, function () use ($token) {
            return $this->get('/api/stores/{token}', compact('token'));
        });
    }
}
