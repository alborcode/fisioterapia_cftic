<?php

// src/Components/RandomNumberComponent.php
namespace App\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('random_number')]
class RandomNumberComponent
{
    use DefaultActionTrait;
    public function getRandomNumber(): int
    {
        return rand(0, 1000);
    }
}
