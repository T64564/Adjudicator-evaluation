<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Http\Models\Round;
use App\Http\Models\Adjudicator;
use App\Http\Models\Team;
use App\Http\Models\Feedback;

class FeedbackTest extends TestCase {
    public function setUp() {
        parent::setUp();
        $this->round = Round::create(['name' => 'Round1', 'silent' => false]);
        $this->team = Team::create(['name' => 'teamA', 'active' => true]);
        $this->adjudicator = 
            Adjudicator::create(['name' => 'AAAA', 'test_score' => 5, 'active' => true]);

        $this->adjudicator2 = 
            Adjudicator::create(['name' => 'BBBB', 'test_score' => 5, 'active' => true]);
    }

    public function testPostFeedback() {
        $this->visit('/feedbacks/1/create_team_to_adj')
            ->select($this->team->id, 'evaluator_id')
            ->select($this->adjudicator->id, 'evaluatee_id')
            ->type(10, 'score')
            ->press('Create')
            ->seePageIs('feedbacks/1/enter_results');
        $this->seeInDatabase('feedbacks', 
            ['type' => 0, 'round_id' => $this->round->id,
            'evaluator_id' => $this->team->id, 'evaluatee_id' => $this->adjudicator->id,
            'score' => 10]);
        $this->visit('/feedbacks/1/create_team_to_adj')
            ->select($this->team->id, 'evaluator_id')
            ->select($this->adjudicator->id, 'evaluatee_id')
            ->type(10, 'score')
            ->press('Create')
            ->seePageIs('feedbacks/1/create_team_to_adj');

        $this->visit('/feedbacks/1/create_adj_to_adj')
            ->select($this->adjudicator->id, 'evaluator_id')
            ->select($this->adjudicator2->id, 'evaluatee_id')
            ->type(10, 'score')
            ->press('Create')
            ->seePageIs('feedbacks/1/enter_results');
        $this->seeInDatabase('feedbacks', 
            ['type' => 1, 'round_id' => $this->round->id,
            'evaluator_id' => $this->adjudicator->id, 'evaluatee_id' => $this->adjudicator2->id,
            'score' => 10]);
        $this->visit('/feedbacks/1/create_adj_to_adj')
            ->select($this->adjudicator->id, 'evaluator_id')
            ->select($this->adjudicator2->id, 'evaluatee_id')
            ->type(10, 'score')
            ->press('Create')
            ->seePageIs('feedbacks/1/create_adj_to_adj');
    }
}
