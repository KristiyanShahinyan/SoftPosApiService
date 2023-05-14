<?php

namespace App\Controller\V1;

use App\EntityManager\AppUpdateManager;
use Phos\Controller\AbstractApiCrudController;

class AppUpdateController extends AbstractApiCrudController
{
    protected const INDEX_GROUPS  = ['index','update'];

    public function __construct(AppUpdateManager $updateManager)
    {
        parent::__construct($updateManager);
    }
}