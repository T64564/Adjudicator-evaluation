<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Http\Models\Adjudicator;
use App\Http\Models\Team;
use App\Http\Models\Round;
use App\Http\Models\Feedback;

class UITest extends TestCase {
    public function testAdjudicatorUI() {
        Adjudicator::create([
            'name' => 'A',
            'test_score' => 5,
            'active' => true,
        ]);

        # Adjudicators
        $this->visit('/adjudicators')->see('Adjudicators');
        $this->click('Add New')->seePageIs('/adjudicators/create');
        $this->click('Back')->seePageIs('/adjudicators');
        $this->click('Import from CSV')->seePageIs('/adjudicators/import_csv');
        $this->click('Back')->seePageIs('/adjudicators');
        $this->click('Edit')->seePageIs('/adjudicators/1/edit');
        $this->click('Back')->seePageIs('/adjudicators');
    }

    public function testTeamUI() {
        Team::create([
            'name' => 'A',
            'active' => true,
        ]);
        # Teams
        $this->visit('/teams')->see('Teams');
        $this->click('Add New')->seePageIs('/teams/create');
        $this->click('Back')->seePageIs('/teams');
        $this->click('Import from CSV')->seePageIs('/teams/import_csv');
        $this->click('Back')->seePageIs('/teams');
        $this->click('Edit')->seePageIs('/teams/1/edit/');
        $this->click('Back')->seePageIs('/teams');
    }

    public function testRoundUI() {
        # Rounds
        $this->visit('/rounds')->see('Rounds');
        $this->click('Add New')->seePageIs('/rounds/create');
        $this->click('Back')->seePageIs('/rounds');
    }

    public function testFeedbackUI() {
        Adjudicator::create([
            'name' => 'A',
            'test_score' => 5,
            'active' => true,
        ]);
        Team::create([
            'name' => 'A',
            'active' => true]);
        Round::create([
            'name' => 'Round1',
            'silent' => false,
        ]);
        Feedback::create([
            'type' => 1,
            'round_id' => 1,
            'evaluatee_id' => 1,
            'evaluator_id' => 1,
            'score' => 5,
        ]);
        $this->visit('/feedbacks')->see('Feedback Management');
        $this->click('Enter')->seePageIs('/feedbacks/1/enter_results');
        $this->click('create_team_to_adj')
            ->seePageIs('/feedbacks/1/create_team_to_adj/');
        $this->click('Back')->seePageIs('/feedbacks/1/enter_results');
        $this->click('create_adj_to_adj')
            ->seePageIs('/feedbacks/1/create_adj_to_adj/');
        $this->click('Back')->seePageIs('/feedbacks/1/enter_results');
        $this->click('Edit')->seePageIs('/feedbacks/1/1/edit');
        $this->click('Back')->seePageIs('/feedbacks/1/enter_results');
    }
}
