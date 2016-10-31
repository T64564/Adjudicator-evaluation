<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Http\Models\Round;

class RoundTest extends TestCase {
    public function testCreateEditDelete() {
        $names = ['AAA', 'BBB', 'CCC'];
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

            $this->seeInDatabase('rounds', [
                'name' => $names[$i], 
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

            $this->seeInDatabase('rounds', [
                'name' => $names[$i],
                'silent' => $silents[$i]]);
        }

        for ($i = 0; $i < count($names); $i++) {
            $this->visit('/rounds')
                ->press('Delete')
                ->seePageIs('/rounds');

            $this->dontSeeInDatabase('rounds', [
                'name' => $names[$i],
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
            $this->dontSeeInDatabase('rounds', [
                'name' => $names[$i],
                'silent' => $silents[$i]]);
        }
    }

    public function testValidationEdit() {
        Round::create(['name' => 'AAA', 'silent' => false]);
        Round::create(['name' => 'BBB', 'silent' => false]);
        $names = ['', 'BBB'];
        $silents = [false, false];

        for ($i = 0; $i < count($names); $i++) {
            $this->visit('/rounds/1/edit')
                ->type($names[$i], 'name')
                ->check('silent')
                ->press('Edit')
                ->seePageIs('/rounds/1/edit');
            $this->dontSeeInDatabase('rounds', [
                'id' => 1,
                'name' => $names[$i],
                'silent' => $silents[$i]]);
        }
    }
}
