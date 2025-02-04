<?php

namespace App\Enum;

enum RoleEnum: int
{
    case Admin          = 1;
    case Receptionist   = 2;
    case Doctor         = 3;
    case Nurse          = 4;
    case Patient        = 5;

}
