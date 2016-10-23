<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Http\Models\Adjudicator;

class AdjudicatorTest extends TestCase {
    public function testCreateEdit() {
        $name = 'AAAA';
        $score = 5;
        $this->visit('/adjudicators/create')
            ->type($name, 'name')
            ->type($score, 'test_score')
            ->check('active')
            ->press('Create')
            ->seePageIs('/adjudicators');

        $this->seeInDatabase('adjudicators', ['name' => $name, 'test_score' => $score, 'active' => true]);

        $name = 'XXXX';
        $score = 8;
        $this->visit('/adjudicators/1/edit/')
            ->type($name, 'name')
            ->type($score, 'test_score')
            ->check('active')
            ->press('Edit')
            ->seePageIs('/adjudicators');
        $this->seeInDatabase('adjudicators', ['name' => $name, 'test_score' => $score, 'active' => true]);
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
