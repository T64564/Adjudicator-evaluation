<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Support\Facades\Artisan;
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
            ->see('Adjudicators');

        $this->seeInDatabase('adjudicators', ['name' => $name, 'test_score' => $score]);

        $name = 'XXXX';
        $score = 8;
        $this->visit('/adjudicators/1/edit/')
            ->type($name, 'name')
            ->type($score, 'test_score')
            ->check('active')
            ->press('Edit')
            ->see('Adjudicators');
        $this->seeInDatabase('adjudicators', ['name' => $name, 'test_score' => $score]);
    }

    public function testValidationCreate() {
        $name  = '';
        $score = 5;
        $this->visit('/adjudicators/create')
            ->type($name, 'name')
            ->type($score, 'test_score')
            ->check('active')
            ->press('Create')
            ->seePageIs('/adjudicators/create');
        $this->dontSeeInDatabase('adjudicators', ['name' => $name, 'test_score' => $score]);

        $name  = 'XXXX';
        $score = -1;
        $this->visit('/adjudicators/create')
            ->type($name, 'name')
            ->type($score, 'test_score')
            ->check('active')
            ->press('Create')
            ->seePageIs('/adjudicators/create');
        $this->dontSeeInDatabase('adjudicators', ['name' => $name, 'test_score' => $score]);

        $name  = 'XXXX';
        $score = 'a';
        $this->visit('/adjudicators/create')
            ->type($name, 'name')
            ->type($score, 'test_score')
            ->check('active')
            ->press('Create')
            ->seePageIs('/adjudicators/create');
        $this->dontSeeInDatabase('adjudicators', ['name' => $name, 'test_score' => $score]);
    }

    public function testValidationEdit() {
        Adjudicator::create(['name' => 'AAAA', 'test_score' => 5, 'active' => true]);
        $name  = '';
        $score = 5;
        $this->visit('/adjudicators/1/edit')
            ->type($name, 'name')
            ->type($score, 'test_score')
            ->check('active')
            ->press('Edit')
            ->seePageIs('/adjudicators/1/edit');
        $this->dontSeeInDatabase('adjudicators', ['name' => $name, 'test_score' => $score]);

        $name  = 'XXXX';
        $score = 'a';
        $this->visit('/adjudicators/1/edit')
            ->type($name, 'name')
            ->type($score, 'test_score')
            ->check('active')
            ->press('Edit')
            ->seePageIs('/adjudicators/1/edit');
        $this->dontSeeInDatabase('adjudicators', ['name' => $name, 'test_score' => $score]);
    }
}
