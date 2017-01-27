<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UITest extends TestCase {
    public function testAdjudicatorUI() {
        # Adjudicators
        $this->visit('/adjudicators')->see('Adjudicators');
        $this->click('Add new')->seePageIs('/adjudicators/create');
        $this->click('Back')->seePageIs('/adjudicators');
        $this->click('Import .csv')->seePageIs('/adjudicators/import_csv');
        $this->click('Back')->seePageIs('/adjudicators');

        # Teams
        $this->visit('/teams')->see('Teams');
        $this->click('Add new')->seePageIs('/teams/create');
        $this->click('Back')->seePageIs('/teams');
        $this->click('Import .csv')->seePageIs('/teams/import_csv');
        $this->click('Back')->seePageIs('/teams');
    }
}
