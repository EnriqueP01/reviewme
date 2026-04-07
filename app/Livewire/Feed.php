<?php

namespace App\Livewire;

use Livewire\Component;

class Feed extends Component
{
    /**
     * The feed items (mock data for now).
     * In a production app, this would be fetched via an Action or Repository.
     */
    public array $perspectives = [
        [
            'id' => 1,
            'author' => 'Alex Rivera',
            'title' => 'Optimizing SVG rendering in React',
            'snippet' => "const MemoizedSVG = useMemo(() => (\n  <svg viewBox='0 0 100 100'>\n    {paths.map(p => <path d={p} />)}\n  </svg>\n), [paths]);",
            'type' => 'performance',
            'points' => 1240,
            'time_ago' => '2h ago',
        ],
        [
            'id' => 2,
            'author' => 'Sarah Chen',
            'title' => 'Clean API handling with Result types',
            'snippet' => "public function handle(): Result\n{\n    return match(true) {\n        \$this->isValid() => Success::make(\$data),\n        default => Failure::make('Invalid state'),\n    };\n}",
            'type' => 'elegant',
            'points' => 850,
            'time_ago' => '5h ago',
        ],
        [
            'id' => 3,
            'author' => 'Marco V.',
            'title' => 'Recursive CTEs for hierarchy traversal',
            'snippet' => "WITH RECURSIVE tree AS (\n  SELECT id, parent_id FROM nodes WHERE id = 1\n  UNION ALL\n  SELECT n.id, n.parent_id FROM nodes n\n  JOIN tree t ON n.parent_id = t.id\n) SELECT * FROM tree;",
            'type' => 'readability',
            'points' => 2100,
            'time_ago' => '1d ago',
        ],
    ];

    public function render()
    {
        return view('livewire.feed');
    }
}
