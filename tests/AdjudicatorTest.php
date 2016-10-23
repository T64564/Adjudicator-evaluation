<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Support\Facades\Artisan;

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
        $name = '';
        $score = 5;
        $this->visit('/adjudicators/create')
            ->type('', 'name')
            ->type($score, 'test_score')
            ->check('active')
            ->press('Create')
            ->assertRedirectedTo('/adjudicators/create');
        $this->dontSeeInDatabase('adjudicators', ['name' => $name, 'test_score' => $score]);
        // $this->seeInDatabase('adjudicators', ['name' => $name, 'test_score' => $score]);

        $this->visit('/adjudicators/create')
            ->type('hoge', 'name')
            ->type(-1, 'test_score')
            ->check('active')
            ->press('Create')
            ->seePageIs('/adjudicator/create');

        $this->visit('/adjudicators/create')
            ->type('hoge', 'name')
            ->type('a', 'test_score')
            ->check('active')
            ->press('Create')
            ->seePageIs('/adjudicators/edit');
    }
}
