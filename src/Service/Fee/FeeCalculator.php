<?php

namespace Lendable\Interview\Interpolation\Service\Fee;

use Lendable\Interview\Interpolation\Model\LoanApplication;
use Lendable\Interview\Interpolation\Error\PublicErrorMessage;
use Lendable\Interview\Interpolation\Exception\BadRequestException;

/**
 * This class calculates fees using Linear Interpolation.
 * Different classes implementing the FeeCalculatorInterface can follow different interpolation implementations.
 *
 * This class should ideally be named as LinearFeeCalculator.
 * But since README mentions FeeCalculator, going ahead with the same.
 *
 * Class FeeCalculator
 *
 * @package Lendable\Interview\Interpolation\Service\Fee
 */
class FeeCalculator implements FeeCalculatorInterface
{
    /**
     * @param LoanApplication $application
     *
     * @return float
     */
    public function calculate(LoanApplication $application): float
    {
        $this->validateLoanApplication($application);

        $feeStructure = $this->getFeeStructure($application);

        $amount = $application->getAmount();

        $fee = $this->compute($feeStructure, $amount);

        return $fee;
    }

    /**
     * These validations should ideally be a part of the Loan Application.
     *
     * @param LoanApplication $application
     *
     * @throws BadRequestException
     */
    protected function validateLoanApplication(LoanApplication $application)
    {
        $term = $application->getTerm();

        if (Structure::isValidTerm($term) === false)
        {
            throw new BadRequestException(PublicErrorMessage::TERM_INVALID);
        }

        $amount = $application->getAmount();

        if (($amount < Structure::MIN_AMOUNT_ALLOWED) or ($amount > Structure::MAX_AMOUNT_ALLOWED))
        {
            throw new BadRequestException(PublicErrorMessage::AMOUNT_INVALID);
        }
    }

    /**
     * @param LoanApplication $application
     *
     * @return array
     */
    protected function getFeeStructure(LoanApplication $application): array
    {
        $term = $application->getTerm();

        $feeStructure = Structure::getStructure($term);

        return $feeStructure;
    }

    protected function compute(array $dataSet, float $x)
    {
        $intx = (int) $x;

        if (array_key_exists($intx, $dataSet) === true)
        {
            return $dataSet[$intx];
        }

        $min = $max = null;

        foreach ($dataSet as $dataValue => $fee)
        {
            if (($x < $dataValue) and (($min === null) or ($dataValue < $min)))
            {
                $min = $dataValue;
            }

            if (($dataValue < $x) and (($max === null) or ($max < $dataValue)))
            {
                $max= $dataValue;
            }
        }

        if (($min === null) or ($max === null))
        {
            throw new \LogicException(PublicErrorMessage::INTERPOLATION_FAILURE);
        }

        // Linear Interpolation -
        //
        //            (x - x0) * (y1 - y0)
        //  Y = y0 +  ____________________
        //
        //                  (x1 - x0)
        //

        $base = $dataSet[$min];

        $value = (($x - $min) * ($dataSet[$max] - $dataSet[$min])) / ($max - $min);

        return ($base + $value);
    }

}
