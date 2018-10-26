<?php
/**
 * Created by PhpStorm.
 * User: veronaprogramista
 * Date: 15.10.18
 * Time: 10:31
 */

namespace App\Utilities\NumbersProcessing;


class Normalizer
{
    /**
     * @param float|int|mixed $score
     * @param array $score_range
     * @param array $normalize_range
     * @return float|int|mixed
     * @throws \Exception
     */
    public static function normalize($score, $score_range, $normalize_range = [0,1]){
        if($score_range[1]-$score_range[0] == 0){
            throw new \DivisionByZeroError();
        }
        return (($score-$score_range[0])/($score_range[1] - $score_range[0]))*($normalize_range[1]-$normalize_range[0])+$normalize_range[0];
    }
}