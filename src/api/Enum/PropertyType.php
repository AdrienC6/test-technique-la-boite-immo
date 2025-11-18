<?php

namespace App\Enum;

enum PropertyType: string
{
    case APARTMENT = 'apartment';
    case HOUSE = 'house';
    case VILLA = 'villa';
    case LAND = 'land';
    case COMMERCIAL = 'commercial';
    case OFFICE = 'office';
    case GARAGE = 'garage';
    case OTHER = 'other';
}
