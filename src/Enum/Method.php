<?php declare(strict_types=1);

namespace Restnova\Enum;

/**
 * Method.
 *
 * @copyright Copyright (c) 2024, Robert Durica
 * @since     2024-04-15
 */
enum Method
{
    case GET;

    case POST;

    case PUT;

    case DELETE;

    case HEAD;

    case PATCH;
}
