<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Http\Models\Round;

class RoundTest extends TestCase {
    public function testCreateEdit() {
        $names = ['R1', 'R2', 'R3'];
        $silents = [true, true, false];
        for ($i = 0; $i < count($names); $i++) {
            $this->visit('/rounds/create')
                ->type($names[$i], 'name');
            if ($silents[$i]) {
                $this->check('silent');
            } else {
                $this->uncheck('silent');
            }
            $this->press('Create')
                ->seePageIs('/rounds');

            $this->seeInDatabase('rounds', 
                ['name' => $names[$i], 
                'silent' => $silents[$i]]);
        }

        $names = ['AAA', 'BBB', 'DDD'];
        $silents = [true, false, true];
        $rounds = Round::all();
        for ($i = 0; $i < count($names); $i++) {
            $this->visit('/rounds/'. $rounds[$i]->id .'/edit/')
                ->type($names[$i], 'name');
            if ($silents[$i]) {
                $this->check('silent');
            } else {
                $this->uncheck('silent');
            }
            $this->press('Edit')
                ->seePageIs('/rounds');

            $this->seeInDatabase('rounds', 
                ['name' => $names[$i],
                'silent' => $silents[$i]]);
        }
    }

    public function testValidationCreate() {
        $names = [''];
        $silents = [true, false];
        for ($i = 0; $i < count($names); $i++) {
            $this->visit('/rounds/create')
                ->type($names[$i], 'name')
                ->check('silent')
                ->press('Create')
                ->seePageIs('/rounds/create');
            $this->dontSeeInDatabase('rounds', 
                ['name' => $names[$i],
                'silent' => $silents[$i]]);
        }
    }

    public function testValidationEdit() {
        Round::create(['name' => 'AAAA', 'silent' => false]);
        Round::create(['name' => 'BBBB', 'silent' => false]);
        $names = ['', 'BBBB'];
        $silents = [false, false];

        for ($i = 0; $i < count($names); $i++) {
            $this->visit('/rounds/1/edit')
                ->type($names[$i], 'name')
                ->check('silent')
                ->press('Edit')
                ->seePageIs('/rounds/1/edit');
            $this->dontSeeInDatabase('rounds', 
                ['id' => 1,
                'name' => $names[$i],
                'silent' => $silents[$i]]);
        }
    }
}
