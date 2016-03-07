<?php

return [
    'adjudicators' => [
        'name' => 'required|unique:adjudicators,name',
        'test_score' => 'required|between:0,10',
        'active' => 'required|boolean',
    ],

    'teams' => [
        'name' => 'required|unique:teams,name',
        'active' => 'required|boolean',
    ],

    'rounds' => [
        'name' => 'required|unique:rounds,name',
        'silent' => 'required|boolean',
    ],

    'feedbacks' => [
        'score' => 'required|numeric|between:0,10',
    ],
];
