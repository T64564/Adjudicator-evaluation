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
        $this->team1 = Team::create(['name' => 'team1', 'active' => true]);
        $this->team2 = Team::create(['name' => 'team2', 'active' => true]);
        $this->adjudicator1 = 
            Adjudicator::create(['name' => 'A1', 'test_score' => 5, 'active' => true]);

        $this->adjudicator2 = 
            Adjudicator::create(['name' => 'A2', 'test_score' => 5, 'active' => true]);
    }

    public function testCreateDelete() {
        // teamA -> A1
        $this->visit('/feedbacks/1/create_team_to_adj')
            ->select($this->team1->id, 'evaluator_id')
            ->select($this->adjudicator1->id, 'evaluatee_id')
            ->type(10, 'score')
            ->press('Create')
            ->seePageIs('feedbacks/1/enter_results');
        $this->seeInDatabase('feedbacks', [
            'type' => 0, 'round_id' => $this->round->id,
            'evaluator_id' => $this->team1->id, 
            'evaluatee_id' => $this->adjudicator1->id,
            'score' => 10]);

        // A1 -> A2
        $this->visit('/feedbacks/1/create_adj_to_adj')
            ->select($this->adjudicator1->id, 'evaluator_id')
            ->select($this->adjudicator2->id, 'evaluatee_id')
            ->type(10, 'score')
            ->press('Create')
            ->seePageIs('feedbacks/1/enter_results');
        $this->seeInDatabase('feedbacks', [
            'type' => 1, 'round_id' => $this->round->id,
            'evaluator_id' => $this->adjudicator1->id, 
            'evaluatee_id' => $this->adjudicator2->id,
            'score' => 10]);

        // Deletion 
        // TODO: push button on popup window
        // $this->visit('/feedbacks/1/enter_results')
        //     ->press('Delete')
        //     ->press('OK')
        //     ->seePageIs('feedbacks/1/enter_results');
        // $this->dontSeeInDatabase('feedbacks', [
        //     'type' => 0, 'round_id' => $this->round->id,
        //     'evaluator_id' => $this->team1->id, 
        //     'evaluatee_id' => $this->adjudicator1->id,
        //     'score' => 10]);
        // $this->seeInDatabase('feedbacks', [
        //     'type' => 1, 'round_id' => $this->round->id,
        //     'evaluator_id' => $this->adjudicator1->id, 
        //     'evaluatee_id' => $this->adjudicator2->id,
        //     'score' => 10]);
    }

    public function testValidation() {
        Feedback::create([
            'type' => 0, 'round_id' => $this->round->id, 
            'evaluator_id' => $this->team1->id,
            'evaluatee_id' => $this->adjudicator1->id,
            'score' => 5]);
        $this->seeInDatabase('feedbacks', [
            'type' => 0, 'round_id' => $this->round->id,
            'evaluator_id' => $this->team1->id,
            'evaluatee_id' => $this->adjudicator1->id,
            'score' => 5]);

        /* a team submit twice */
        $this->visit('/feedbacks/' . $this->round->id . '/create_team_to_adj')
            ->select($this->team1->id, 'evaluator_id')
            ->select($this->adjudicator1->id, 'evaluatee_id')
            ->type(10, 'score')
            ->press('Create')
            ->seePageIs('feedbacks/' . $this->round->id . '/create_team_to_adj');
        $this->assertEquals(1, Feedback::count());

        Feedback::create([
            'type' => 1, 'round_id' => $this->round->id,
            'evaluator_id' => $this->adjudicator1->id,
            'evaluatee_id' => $this->adjudicator2->id, 'score' => 5]);

        $this->visit('/feedbacks/' . $this->round->id . '/create_adj_to_adj')
            ->select(1, 'type')
            ->select($this->adjudicator1->id, 'evaluator_id')
            ->select($this->adjudicator2->id, 'evaluatee_id')
            ->type(10, 'score')
            ->press('Create')
            ->seePageIs('feedbacks/' . $this->round->id . '/create_adj_to_adj');
        $this->assertEquals(2, Feedback::count());

        $this->visit('/feedbacks/' . $this->round->id . '/create_adj_to_adj')
            ->select(2, 'type')
            ->select($this->adjudicator1->id, 'evaluator_id')
            ->select($this->adjudicator2->id, 'evaluatee_id')
            ->type(10, 'score')
            ->press('Create')
            ->seePageIs('feedbacks/' . $this->round->id . '/create_adj_to_adj');
        $this->assertEquals(2, Feedback::count());

        $this->visit('/feedbacks/' . $this->round->id . '/create_adj_to_adj')
            ->select(3, 'type')
            ->select($this->adjudicator1->id, 'evaluator_id')
            ->select($this->adjudicator2->id, 'evaluatee_id')
            ->type(10, 'score')
            ->press('Create')
            ->seePageIs('feedbacks/' . $this->round->id . '/create_adj_to_adj');
        $this->assertEquals(2, Feedback::count());
    }
}
