<?php

namespace Lendable\Interview\Interpolation\Error;

use Lendable\Interview\Interpolation\Service\Fee\Structure as Struct;

class PublicErrorMessage
{
    const TERM_INVALID      = 'The term provided is invalid';
    const AMOUNT_INVALID    = 'The amount provided should be between ' . Struct::MIN_AMOUNT_ALLOWED . ' and ' . Struct::MAX_AMOUNT_ALLOWED;
}
