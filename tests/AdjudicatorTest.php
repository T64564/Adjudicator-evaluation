<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Http\Models\Adjudicator;

class AdjudicatorTest extends TestCase {
    public function testCreateEdit() {
        $names = ['AAA', 'BBB', 'CCC'];
        $scores = [1, 2, 3];
        $actives = [true, true, false];
        for ($i = 0; $i < count($names); $i++) {
            $this->visit('/adjudicators/create')
                ->type($names[$i], 'name')
                ->type($scores[$i], 'test_score');
            if ($actives[$i]) {
                $this->check('active');
            }
            $this->press('Create')
                ->seePageIs('/adjudicators');

            $this->seeInDatabase('adjudicators', 
                ['name' => $names[$i], 
                'test_score' => $scores[$i], 
                'active' => $actives[$i]]);
        }

        $names = ['AAA', 'BBB', 'DDD'];
        $scores = [1, 3, 4];
        $actives = [true, false, true];
        for ($i = 0; $i < count($names); $i++) {
            $this->visit('/adjudicators/'. (Adjudicator::first()->id) .'/edit/')
                ->type($names[$i], 'name')
                ->type($scores[$i], 'test_score');
            if ($actives[$i]) {
                $this->check('active');
            }
            $this->press('Edit')
                ->seePageIs('/adjudicators');

            $this->seeInDatabase('adjudicators', 
                ['name' => $names[$i],
                'test_score' => $scores[$i],
                'active' => $actives[$i]]);
        }
    }

    public function testValidationCreate() {
        $names = ['', 'AAAA', 'AAAA'];
        $scores = [1, -1, 'a'];

        for ($i = 0; $i < count($names); $i++) {
            $this->visit('/adjudicators/create')
                ->type($names[$i], 'name')
                ->type($scores[$i], 'test_score')
                ->check('active')
                ->press('Create')
                ->seePageIs('/adjudicators/create');
            $this->dontSeeInDatabase('adjudicators',
                ['name' => $names[$i], 'test_score' => $scores[$i]]);
        }
    }

    public function testValidationEdit() {
        Adjudicator::create(['name' => 'AAAA', 'test_score' => 5, 'active' => true]);
        $names = ['', 'AAAA', 'AAAA'];
        $scores = [1, -1, 'a'];

        for ($i = 0; $i < count($names); $i++) {
            $this->visit('/adjudicators/1/edit')
                ->type($names[$i], 'name')
                ->type($scores[$i], 'test_score')
                ->check('active')
                ->press('Edit')
                ->seePageIs('/adjudicators/1/edit');
            $this->dontSeeInDatabase('adjudicators',
                ['name' => $names[$i], 'test_score' => $scores[$i]]);
        }
    }
}
