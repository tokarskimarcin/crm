<?php
/**
 * Created by PhpStorm.
 * User: veronaprogramista
 * Date: 25.10.18
 * Time: 12:39
 */

namespace Tests\Unit;

use App\Utilities\Salary\ProvisionLevels\TrainerProvisionLevels;
use PHPUnit\Framework\TestCase;

class TrainerProvisionLevelsTest extends TestCase
{

    public function testGet(){
        $testingDataEquals = [
            [96, 2, 1, null, 350],
            [95, 2, 1, null, 350],
            [94, 2, 1, null, 300],
            [91, 2, 1, null, 300],
            [90, 2, 1, null, 300],
            [89, 2, 1, null, 250],
            [86, 2, 1, null, 250],
            [85, 2, 1, null, 250],
            [81, 2, 1, null, 200],
            [80, 2, 1, null, 200],
            [79, 2, 1, null, 0],
            [81, 2, 5, null, 0],
            [80, 2, 5, null, 0],
            [5,3, null, null, 0],
            [6,3, null, null, 0],
            [4,3, 99, 'avg', 0],
            [4,3, 100, 'avg', 150],
            [4,3, 101, 'avg', 150],
            [4,3, 99, 'ammount', 0],
            [4,3, 100, 'ammount', 150],
            [4,3, 101, 'ammount', 150]
        ];
        foreach ($testingDataEquals as $testingData){
            $level = $testingData[0];
            $subtype = $testingData[1];
            $subsubtype = $testingData[2];
            $subsubsubtype = $testingData[3];
            $expectedvalue = $testingData[4];
            $this->assertEquals($expectedvalue, TrainerProvisionLevels::get($level, $subtype, $subsubtype, $subsubsubtype));
        }
    }

    public function testException(){
        $this->expectException(\Exception::class);
        TrainerProvisionLevels::get(3,3,null, 1);
    }
}
