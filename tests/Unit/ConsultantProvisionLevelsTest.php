<?php
/**
 * Created by PhpStorm.
 * User: veronaprogramista
 * Date: 25.10.18
 * Time: 11:47
 */

namespace Tests\Unit;

use App\Utilities\Salary\ProvisionLevels\ConsultantProvisionLevels;
use PHPUnit\Framework\TestCase;

class ConsultantProvisionLevelsTest extends TestCase
{

    public function testGet()
    {
        $testingDataEquals = [
            [41,1, 40],
            [40,1, 40],
            [39,1, 35],
            [36,1, 35],
            [35,1, 35],
            [34,1, 30],
            [31,1, 30],
            [30,1, 30],
            [29,1, 25],
            [26,1, 25],
            [25,1, 25],
            [24,1, 20],
            [21,1, 20],
            [20,1, 20],
            [19,1, 0],
            [17,1, 0],
            [16,1, 0],
            [15,1, -60],
            [13,1, -60],
            [12,1, -60],
            [11,1, -180],
            [0,2, 50],
            [1,2, 0],
            [-1,2, 0],
                ];

        foreach ($testingDataEquals as $testingData){
            $level = $testingData[0];
            $subtype = $testingData[1];
            $expectedValue = $testingData[2];
            $this->assertEquals($expectedValue, ConsultantProvisionLevels::get($level, $subtype));
        }
    }

    public function testExceptionFirst()
    {
        $this->expectException(\Exception::class);
        ConsultantProvisionLevels::get(-1, 0);
    }

    public function testExceptionSecond(){
        $this->expectException(\Exception::class);
        ConsultantProvisionLevels::get(-1,3);
    }
}
