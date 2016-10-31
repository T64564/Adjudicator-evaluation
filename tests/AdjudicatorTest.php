<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Http\Models\Adjudicator;

class AdjudicatorTest extends TestCase {
    public function testCreateEditDelete() {
        $names = ['AAA', 'BBB', 'CCC'];
        $scores = [1, 2, 3];
        $actives = [true, true, false];
        for ($i = 0; $i < count($names); $i++) {
            $this->visit('/adjudicators/create')
                ->type($names[$i], 'name')
                ->type($scores[$i], 'test_score');
            if ($actives[$i]) {
                $this->check('active');
            } else {
                $this->uncheck('active');
            }
            $this->press('Create')
                ->seePageIs('/adjudicators');

            $this->seeInDatabase('adjudicators', [
                'name' => $names[$i], 
                'test_score' => $scores[$i], 
                'active' => $actives[$i]]);
        }

        $names = ['AAA', 'BBB', 'DDD'];
        $scores = [1, 3, 4];
        $actives = [true, false, true];
        $adjudicators = Adjudicator::all();
        for ($i = 0; $i < count($names); $i++) {
            $this->visit('/adjudicators/'. $adjudicators[$i]->id .'/edit/')
                ->type($names[$i], 'name')
                ->type($scores[$i], 'test_score');
            if ($actives[$i]) {
                $this->check('active');
            } else {
                $this->uncheck('active');
            }
            $this->press('Edit')
                ->seePageIs('/adjudicators');

            $this->seeInDatabase('adjudicators', [
                'id' => $adjudicators[$i]->id,
                'name' => $names[$i], 
                'test_score' => $scores[$i], 
                'active' => $actives[$i]]);
        }

        for ($i = 0; $i < count($names); $i++) {
            $this->visit('/adjudicators')
                ->press('Delete')
                ->seePageIs('/adjudicators');

            $this->dontSeeInDatabase('adjudicators', [
                'id' => $adjudicators[$i]->id,
                'name' => $names[$i], 
                'test_score' => $scores[$i], 
                'active' => $actives[$i]]);
        }
    }

    public function testValidationCreate() {
        $names = ['', 'AAAA', 'AAAA'];
        $scores = [1, -1, 'a'];
        $actives = [true, false, true];

        for ($i = 0; $i < count($names); $i++) {
            $this->visit('/adjudicators/create')
                ->type($names[$i], 'name')
                ->type($scores[$i], 'test_score');
            if ($actives[$i]) {
                $this->check('active');
            } else {
                $this->uncheck('active');
            }
            $this->press('Create')
                ->seePageIs('/adjudicators/create');
            $this->dontSeeInDatabase('adjudicators', [
                'id' => 1,
                'name' => $names[$i], 
                'test_score' => $scores[$i], 
                'active' => $actives[$i]]);
        }
    }

    public function testValidationEdit() {
        Adjudicator::create(['name' => 'AAA', 'test_score' => 5, 'active' => true]);
        Adjudicator::create(['name' => 'BBB', 'test_score' => 5, 'active' => true]);
        $names = ['', 'BBB', 'AAA', 'AAA'];
        $scores = [1, 5, -1, 'a'];
        $actives = [true, true, false, true];

        for ($i = 0; $i < count($names); $i++) {
            $this->visit('/adjudicators/1/edit')
                ->type($names[$i], 'name')
                ->type($scores[$i], 'test_score');
            if ($actives[$i]) {
                $this->check('active');
            } else {
                $this->uncheck('active');
            }
            $this->press('Edit')
                ->seePageIs('/adjudicators/1/edit');
            $this->dontSeeInDatabase('adjudicators', [
                'id' => 1,
                'name' => $names[$i], 
                'test_score' => $scores[$i], 
                'active' => $actives[$i]]);
        }
    }
}
