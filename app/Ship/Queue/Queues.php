<?php

namespace App\Ship\Queue;

abstract class Queues
{
    const Default            = 'default';
    const Logging            = 'logging';
    const SubjectLevelAttack = 'subject_level_attack';
}