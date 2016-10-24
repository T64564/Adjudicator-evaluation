<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Http\Models\Team;

class TeamTest extends TestCase {
    public function testCreateEdit() {
        $names = ['AAA', 'BBB', 'CCC'];
        $actives = [true, true, false];
        for ($i = 0; $i < count($names); $i++) {
            $this->visit('/teams/create')
                ->type($names[$i], 'name');
            if ($actives[$i]) {
                $this->check('active');
            } else {
                $this->uncheck('active');
            }
            $this->press('Create')
                ->seePageIs('/teams');

            $this->seeInDatabase('teams', 
                ['name' => $names[$i], 
                'active' => $actives[$i]]);
        }

        $names = ['AAA', 'BBB', 'DDD'];
        $actives = [true, false, true];
        $teams = Team::all();
        for ($i = 0; $i < count($names); $i++) {
            $this->visit('/teams/'. $teams[$i]->id .'/edit/')
                ->type($names[$i], 'name');
            if ($actives[$i]) {
                $this->check('active');
            } else {
                $this->uncheck('active');
            }
            $this->press('Edit')
                ->seePageIs('/teams');

            $this->seeInDatabase('teams', 
                ['name' => $names[$i],
                'active' => $actives[$i]]);
        }
    }

    public function testValidationCreate() {
        $names = [''];
        $actives = [true];

        for ($i = 0; $i < count($names); $i++) {
            $this->visit('/teams/create')
                ->type($names[$i], 'name');
            if ($actives[$i]) {
                $this->check('active');
            } else {
                $this->uncheck('active');
            }
            $this->press('Create')
                ->seePageIs('/teams/create');
            $this->dontSeeInDatabase('teams',
                ['name' => $names[$i], 
                'active' => $actives[$i]]);
        }
    }

    public function testValidationEdit() {
        Team::create(['name' => 'AAA', 'test_score' => 5, 'active' => true]);
        Team::create(['name' => 'BBB', 'test_score' => 5, 'active' => true]);
        $names = ['', 'BBB'];
        $actives = [true, true];

        for ($i = 0; $i < count($names); $i++) {
            $this->visit('/teams/1/edit')
                ->type($names[$i], 'name');
            if ($actives[$i]) {
                $this->check('active');
            } else {
                $this->uncheck('active');
            }
            $this->press('Edit')
                ->seePageIs('/teams/1/edit');
            $this->dontSeeInDatabase('teams',
                ['id' => 1,
                'name' => $names[$i], 
                'active' => $actives[$i]]);
        }
    }
}
