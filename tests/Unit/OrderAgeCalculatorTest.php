<?php

namespace Tests\Unit;

use App\Models\VacationPeriod;
use App\Services\OrderAgeCalculator;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrderAgeCalculatorTest extends TestCase
{
    use RefreshDatabase;

    private OrderAgeCalculator $calculator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calculator = new OrderAgeCalculator;
    }

    #[Test]
    public function countsWeekdaysOnly(): void
    {
        // Monday 2026-03-16 to Friday 2026-03-20 = 4 business days (Mon, Tue, Wed, Thu)
        $from = Carbon::parse('2026-03-16'); // Monday
        $to = Carbon::parse('2026-03-20');   // Friday

        $this->assertSame(4, $this->calculator->calculateBusinessDays($from, $to));
    }

    #[Test]
    public function excludesWeekends(): void
    {
        // Monday 2026-03-16 to Monday 2026-03-23 = 5 business days (Mon-Fri, skip Sat+Sun)
        $from = Carbon::parse('2026-03-16');
        $to = Carbon::parse('2026-03-23');

        $this->assertSame(5, $this->calculator->calculateBusinessDays($from, $to));
    }

    #[Test]
    public function returnsZeroWhenFromEqualsTo(): void
    {
        $date = Carbon::parse('2026-03-18');

        $this->assertSame(0, $this->calculator->calculateBusinessDays($date, $date));
    }

    #[Test]
    public function returnsZeroWhenFromIsAfterTo(): void
    {
        $from = Carbon::parse('2026-03-20');
        $to = Carbon::parse('2026-03-18');

        $this->assertSame(0, $this->calculator->calculateBusinessDays($from, $to));
    }

    #[Test]
    public function excludesVacationPeriods(): void
    {
        // Create a vacation period covering Wed-Fri
        VacationPeriod::factory()->create([
            'name' => 'Test vacances',
            'start_date' => '2026-03-18', // Wednesday
            'end_date' => '2026-03-20',   // Friday
            'school_year' => 2025,
        ]);

        // Monday 2026-03-16 to Monday 2026-03-23
        // Without vacation: 5 days (Mon, Tue, Wed, Thu, Fri)
        // With vacation (Wed-Fri excluded): 2 days (Mon, Tue)
        $from = Carbon::parse('2026-03-16');
        $to = Carbon::parse('2026-03-23');

        $this->assertSame(2, $this->calculator->calculateBusinessDays($from, $to));
    }

    #[Test]
    public function excludesVacationPeriodsOverlappingWeekends(): void
    {
        // Vacation covering an entire week including weekend
        VacationPeriod::factory()->create([
            'name' => 'Full week vacation',
            'start_date' => '2026-03-16', // Monday
            'end_date' => '2026-03-22',   // Sunday
            'school_year' => 2025,
        ]);

        // Monday 2026-03-16 to Monday 2026-03-30
        // Week 1 (Mon-Sun): all in vacation = 0
        // Week 2 (Mon-Fri): 5 business days
        $from = Carbon::parse('2026-03-16');
        $to = Carbon::parse('2026-03-30');

        $this->assertSame(5, $this->calculator->calculateBusinessDays($from, $to));
    }

    #[Test]
    public function handlesMultipleVacationPeriods(): void
    {
        VacationPeriod::factory()->create([
            'name' => 'Vacances 1',
            'start_date' => '2026-03-17', // Tuesday
            'end_date' => '2026-03-18',   // Wednesday
            'school_year' => 2025,
        ]);

        VacationPeriod::factory()->create([
            'name' => 'Vacances 2',
            'start_date' => '2026-03-24', // Tuesday
            'end_date' => '2026-03-25',   // Wednesday
            'school_year' => 2025,
        ]);

        // Monday 2026-03-16 to Friday 2026-03-27
        // Week 1: Mon(ok), Tue(vac), Wed(vac), Thu(ok), Fri(ok) = 3
        // Weekend: skip
        // Week 2: Mon(ok), Tue(vac), Wed(vac), Thu(ok) = 2
        // Total: 5
        $from = Carbon::parse('2026-03-16');
        $to = Carbon::parse('2026-03-27');

        $this->assertSame(5, $this->calculator->calculateBusinessDays($from, $to));
    }

    #[Test]
    public function countsCorrectlyWhenNoVacationPeriods(): void
    {
        // Two full weeks: 10 business days
        $from = Carbon::parse('2026-03-16'); // Monday
        $to = Carbon::parse('2026-03-30');   // Monday (2 weeks later)

        $this->assertSame(10, $this->calculator->calculateBusinessDays($from, $to));
    }

    #[Test]
    public function startingOnWeekendSkipsToNextWeekday(): void
    {
        // Saturday to Monday = 0 business days (Saturday and Sunday are skipped)
        $from = Carbon::parse('2026-03-21'); // Saturday
        $to = Carbon::parse('2026-03-23');   // Monday

        $this->assertSame(0, $this->calculator->calculateBusinessDays($from, $to));
    }
}
