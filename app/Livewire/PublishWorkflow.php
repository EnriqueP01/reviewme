<?php

namespace App\Livewire;

use App\Actions\Posts\CreatePostAction;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

class PublishWorkflow extends Component
{
    public int $step = 1;

    // Step 1: Introspection & Essence
    public string $title = '';

    public string $short_description = '';

    public string $review_goals = '';

    public string $improvement_goals = '';

    public string $description = ''; // Full description

    // Step 2: Artifacts
    public array $files = [];

    public function mount()
    {
        $this->files = [
            ['id' => (string) str()->uuid(), 'name' => '', 'content' => '', 'language' => 'php', 'description' => '', 'is_duplicate' => false, 'is_content_duplicate' => false],
        ];
    }

    protected array $extensionMap = [
        'php' => 'php',
        'js' => 'javascript',
        'ts' => 'typescript',
        'py' => 'python',
        'css' => 'css',
        'html' => 'html',
        'sql' => 'sql',
        'md' => 'markdown',
        'json' => 'json',
        'yaml' => 'yaml',
        'yml' => 'yaml',
        'xml' => 'xml',
        'c' => 'c',
        'cpp' => 'cpp',
        'h' => 'cpp',
        'hpp' => 'cpp',
        'java' => 'java',
        'go' => 'go',
        'rs' => 'rust',
        'rb' => 'ruby',
        'cs' => 'csharp',
        'swift' => 'swift',
        'kt' => 'kotlin',
        'dart' => 'dart',
        'sh' => 'bash',
        'vue' => 'vue',
        'blade' => 'blade',
    ];

    public function getSupportedLanguages(): array
    {
        return [
            'php', 'javascript', 'typescript', 'python', 'css', 'html', 'sql', 'markdown',
            'json', 'yaml', 'xml', 'c', 'cpp', 'java', 'go', 'rust', 'ruby', 'csharp',
            'swift', 'kotlin', 'dart', 'bash', 'vue', 'blade',
        ];
    }

    // Step 3: Global Focus & Distribution
    public array $selectedLens = ['clarity'];

    public string $visibility = 'public';

    public ?int $groupId = null;

    public string $groupSearch = '';

    #[Computed]
    public function groups()
    {
        $user = Auth::user();
        if (! $user) {
            return collect();
        }

        $query = $user->groups();

        if (! empty($this->groupSearch)) {
            $query->where('name', 'like', '%'.$this->groupSearch.'%');
        }

        return $query->get();
    }

    public function updatedFiles($value, $key)
    {
        if (str_ends_with($key, '.name')) {
            $parts = explode('.', $key);
            $index = $parts[count($parts) - 2];
            $this->detectLanguage($index);
        }
    }

    protected function detectLanguage($index)
    {
        $filename = $this->files[$index]['name'] ?? '';
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (isset($this->extensionMap[$extension])) {
            $this->files[$index]['language'] = $this->extensionMap[$extension];
        } else {
            // Default to plaintext or keep existing if manually set
            // but for a new file with generic extension, we keep it as it is
        }
    }

    public function importFile($index, $name, $content)
    {
        $this->files[$index]['name'] = $name;
        $this->files[$index]['content'] = $content;
        $this->detectLanguage($index);
        $this->checkDuplicates();
    }

    public function importMultipleFiles($filesData)
    {
        // If the first file is empty, replace it, otherwise append
        if (count($this->files) === 1 && empty($this->files[0]['name']) && empty($this->files[0]['content'])) {
            $this->files = [];
        }

        foreach ($filesData as $data) {
            $this->files[] = [
                'id' => (string) str()->uuid(),
                'name' => $data['name'],
                'content' => $data['content'],
                'language' => $this->getLanguageByExtension($data['name']),
                'description' => '',
                'is_duplicate' => false,
                'is_content_duplicate' => false,
            ];
        }
        $this->checkDuplicates();
    }

    protected function checkDuplicates()
    {
        $names = [];
        $contents = [];

        foreach ($this->files as $index => &$file) {
            $file['is_duplicate'] = false;
            $file['is_content_duplicate'] = false;

            if (! empty($file['name'])) {
                if (isset($names[$file['name']])) {
                    $file['is_duplicate'] = true;
                    $this->files[$names[$file['name']]]['is_duplicate'] = true;
                }
                $names[$file['name']] = $index;
            }

            if (! empty($file['content'])) {
                $hash = md5($file['content']);
                if (isset($contents[$hash])) {
                    $file['is_content_duplicate'] = true;
                    $this->files[$contents[$hash]]['is_content_duplicate'] = true;
                }
                $contents[$hash] = $index;
            }
        }
    }

    protected function getLanguageByExtension($filename)
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        return $this->extensionMap[$extension] ?? 'php';
    }

    public function getFileStats($index)
    {
        $file = $this->files[$index] ?? null;
        if (! $file) {
            return ['lines' => 0, 'size' => '0 B', 'is_duplicate' => false];
        }

        $content = $file['content'] ?? '';
        $lines = empty($content) ? 0 : count(explode("\n", $content));
        $bytes = strlen($content);
        $kb = round($bytes / 1024, 1);

        return [
            'lines' => $lines,
            'size' => $kb > 0 ? $kb.' KB' : $bytes.' B',
            'is_duplicate' => $file['is_duplicate'] ?? false,
            'is_content_duplicate' => $file['is_content_duplicate'] ?? false,
            'complexity' => $this->calculateComplexity($content),
        ];
    }

    protected function calculateComplexity($content)
    {
        if (empty($content)) {
            return 0;
        }
        // Simple heuristic for UI visualization: count of keywords / lines
        $keywords = ['if', 'else', 'for', 'while', 'foreach', 'switch', 'case', 'function', 'class', 'public', 'private', 'protected'];
        $count = 0;
        foreach ($keywords as $kw) {
            $count += substr_count($content, $kw);
        }
        $lines = count(explode("\n", $content));

        return min(100, round(($count / max(1, $lines)) * 100));
    }

    public function addFile()
    {
        $this->files[] = [
            'id' => (string) str()->uuid(),
            'name' => '',
            'content' => '',
            'language' => 'php',
            'description' => '',
            'is_duplicate' => false,
            'is_content_duplicate' => false,
        ];
    }

    public function removeFile($index)
    {
        unset($this->files[$index]);
        $this->files = array_values($this->files);
        $this->checkDuplicates();
    }

    public function moveUp($index)
    {
        if ($index > 0) {
            $temp = $this->files[$index - 1];
            $this->files[$index - 1] = $this->files[$index];
            $this->files[$index] = $temp;
            $this->files = array_values($this->files);
        }
    }

    public function moveDown($index)
    {
        if ($index < count($this->files) - 1) {
            $temp = $this->files[$index + 1];
            $this->files[$index + 1] = $this->files[$index];
            $this->files[$index] = $temp;
            $this->files = array_values($this->files);
        }
    }

    public function reorderFiles($orderedIndices)
    {
        $newFiles = [];
        foreach ($orderedIndices as $index) {
            if (isset($this->files[$index])) {
                $newFiles[] = $this->files[$index];
            }
        }
        $this->files = $newFiles;
    }

    public function nextStep()
    {
        if ($this->step === 1) {
            $this->validate([
                'title' => 'required|min:5|max:255',
                'short_description' => 'required|min:10|max:255',
                'review_goals' => 'required|min:10',
                'improvement_goals' => 'required|min:10',
            ]);
        } elseif ($this->step === 2) {
            $this->validate([
                'files' => 'required|array|min:1',
                'files.*.name' => 'required|string',
                'files.*.content' => 'required|string|max:524288',
            ]);
        }

        $this->step++;
    }

    public function prevStep()
    {
        $this->step--;
    }

    public function submit(CreatePostAction $createPost)
    {
        $this->validate([
            'visibility' => 'required|in:public,private',
            'groupId' => 'required_if:visibility,private',
        ]);

        $createPost->execute(Auth::user(), [
            'title' => $this->title,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'review_goals' => $this->review_goals,
            'improvement_goals' => $this->improvement_goals,
            // Si c'est 'private' mais avec un groupe, on stocke 'group' en DB
            'visibility' => ($this->visibility === 'private' && $this->groupId) ? 'group' : $this->visibility,
            'group_id' => ($this->visibility === 'private') ? $this->groupId : null,
            'lens' => implode(',', $this->selectedLens),
            'files' => $this->files,
        ]);

        session()->flash('success', 'Artefact déployé avec succès !');

        return redirect()->to(route('dashboard'));
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.publish-workflow');
    }
}
