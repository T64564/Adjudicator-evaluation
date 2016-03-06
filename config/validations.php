<?php

return [
    'rounds' => [
        'name' => 'required|unique:rounds,name',
    ],

    'teams' => [
        'name' => 'required|unique:teams,name',
        'active' => 'boolean',
    ],

    'adjudicators' => [
        'name' => 'required|unique:adjudicators,name',
        'test_score' => 'required|between:0,10',
        'active' => 'boolean',
    ],
];
