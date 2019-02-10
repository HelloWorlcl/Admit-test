<?php

namespace App\Controllers;

abstract class AbstractController
{
    const CONTROLLER_METHOD_GET_ALL = 'index';
    const CONTROLLER_METHOD_GET = 'show';
    const CONTROLLER_METHOD_POST = 'new';
    const CONTROLLER_METHOD_PUT = 'updatePUT';
    const CONTROLLER_METHOD_PATCH = 'updatePATCH';
    const CONTROLLER_METHOD_DELETE = 'delete';
}
