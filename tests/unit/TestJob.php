<?php

namespace App\Tests\unit;

use App\Entity\Job;
use PHPUnit\Framework\TestCase;

class TestJob extends TestCase
{

    public function testJobVerifyStatusCorrectlyValidates()
    {
        $status = Job::SCHEDULED_STATUS;
        $this->assertTrue(Job::validateStatus($status));

        $this->assertFalse(Job::validateStatus('Invalid Status'));
    }
}