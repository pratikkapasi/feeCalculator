<?php

use \PHPUnit\Framework\TestCase;
use Lendable\Interview\Interpolation\Model\LoanApplication;
use Lendable\Interview\Interpolation\Error\PublicErrorMessage;
use Lendable\Interview\Interpolation\Service\Fee\FeeCalculator;
use Lendable\Interview\Interpolation\Exception\BadRequestException;

class LoanApplicationTest extends TestCase
{
    public function testLinearInterpolation()
    {
        $calculator = new FeeCalculator();

        $application = new LoanApplication(24, 2750);

        $fee = $calculator->calculate($application);
        
        $this->assertEquals(115.0, $fee);
    }

    public function testLinearInterpolation1DecimalPoint()
    {
        $calculator = new FeeCalculator();

        $application = new LoanApplication(24, 2750.4);

        $fee = $calculator->calculate($application);

        // Fee = 119.6, Amount = 2750.4, Total = 2870
        $this->assertEquals(119.6, $fee);
    }

    public function testLinearInterpolation2DecimalPoints()
    {
        $calculator = new FeeCalculator();

        $application = new LoanApplication(24, 2750.56);

        $fee = $calculator->calculate($application);

        // Fee = 119.44, Amount = 2750.56, Total = 2870
        $this->assertEquals(119.44, $fee);
    }

    public function testLinearInterpolationInvalidTerm()
    {
        $calculator = new FeeCalculator();

        $application = new LoanApplication(240, 2750);

        try
        {
            $calculator->calculate($application);
        }
        catch (BadRequestException $e)
        {
            $this->assertEquals($e->getMessage(), PublicErrorMessage::TERM_INVALID);
        }
        finally
        {
            if (isset($e) === false)
            {
                $this->fail('Exception BadRequestException expected. None caught');
            }
        }
    }

    public function testLinearInterpolationInvalidAmount()
    {
        $calculator = new FeeCalculator();

        $application = new LoanApplication(24, 27500);

        try
        {
            $calculator->calculate($application);
        }
        catch (BadRequestException $e)
        {
            $this->assertEquals($e->getMessage(), PublicErrorMessage::AMOUNT_INVALID);
        }
        finally
        {
            if (isset($e) === false)
            {
                $this->fail('Exception BadRequestException expected. None caught');
            }
        }
    }

    // A test for the logic exception can also be added, which has not been included here.
}
