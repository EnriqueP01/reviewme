<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Karma Levels & Privileges
    |--------------------------------------------------------------------------
    |
    | Définition des paliers de Karma et des capacités associées.
    | Ces valeurs sont utilisées pour protéger les actions et les routes.
    |
    */

    'levels' => [
        'unranked' => [
            'min_score' => 0,
            'label' => 'Apprenti',
            'color' => 'text-on-surface-variant',
            'permissions' => [
                'post.view',
                'post.vote_up',
                'review.vote_up',
                'comment.like',
            ],
        ],
        'contributor' => [
            'min_score' => 10,
            'label' => 'Contributeur',
            'color' => 'text-blue-400',
            'permissions' => [
                'post.comment',
                'post.vote_down',
                'review.vote_down',
                'review.publish',
            ],
        ],
        'reviewer' => [
            'min_score' => 100,
            'label' => 'Reviewer Certifié',
            'color' => 'text-emerald-400',
            'permissions' => [
                'group.create',
                'suggestion.inline',
            ],
        ],
        'expert' => [
            'min_score' => 500,
            'label' => 'Expert ReviewMe',
            'color' => 'text-amber-400',
            'permissions' => [
                'post.pin_comments',
                'lab.early_access',
            ],
        ],
        'elite' => [
            'min_score' => 2000,
            'label' => 'Elite Architect',
            'color' => 'text-rose-400',
            'permissions' => [
                'platform.moderation',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Rewards
    |--------------------------------------------------------------------------
    */
    'rewards' => [
        'post_upvote' => 10,
        'post_downvote' => -2,
        'review_upvote' => 15, // Une review est plus valorisée qu'un post
        'review_bonus' => 5,  // Bonus pour avoir publié une review
        'comment_like' => 1,
    ],
];
