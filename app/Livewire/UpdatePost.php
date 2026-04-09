<?php

namespace App\Livewire;

use App\Actions\Posts\AddPostVersionAction;
use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Component;

class UpdatePost extends Component
{
    use AuthorizesRequests;

    public Post $post;

    public array $files = [];

    protected array $extensionMap = [
        'php' => 'php', 'js' => 'javascript', 'ts' => 'typescript', 'py' => 'python',
        'css' => 'css', 'html' => 'html', 'sql' => 'sql', 'md' => 'markdown',
        'json' => 'json', 'yaml' => 'yaml', 'yml' => 'yaml', 'xml' => 'xml',
        'c' => 'c', 'cpp' => 'cpp', 'h' => 'cpp', 'hpp' => 'cpp',
        'java' => 'java', 'go' => 'go', 'rs' => 'rust', 'rb' => 'ruby',
        'cs' => 'csharp', 'swift' => 'swift', 'kt' => 'kotlin', 'dart' => 'dart',
        'sh' => 'bash', 'vue' => 'vue', 'blade' => 'blade',
    ];

    public function mount(int $postId)
    {
        $this->post = Post::findOrFail($postId);
        $this->authorize('update', $this->post);

        // Load the latest version's snippets
        $latestVersion = $this->post->snippets()->max('version_number') ?? 1;
        $snippets = $this->post->snippets()->where('version_number', $latestVersion)->orderBy('sort_order')->get();

        foreach ($snippets as $s) {
            $this->files[] = [
                'id' => (string) str()->uuid(),
                'name' => $s->filename,
                'content' => html_entity_decode($s->code_content, ENT_QUOTES),
                'language' => $s->language,
                'description' => $s->description ?? '',
                'is_duplicate' => false,
                'is_content_duplicate' => false,
            ];
        }
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

        $this->files[$index]['language'] = $this->extensionMap[$extension] ?? 'none';
    }

    public function addFile()
    {
        $this->files[] = [
            'id' => (string) str()->uuid(),
            'name' => '',
            'content' => '',
            'language' => 'none',
            'description' => '',
            'is_duplicate' => false,
            'is_content_duplicate' => false,
        ];
    }

    public function removeFile($index)
    {
        if (count($this->files) > 1) {
            unset($this->files[$index]);
            $this->files = array_values($this->files);
        }
    }

    public function submit(AddPostVersionAction $addVersion)
    {
        $this->authorize('update', $this->post);

        $rules = ['files' => 'required|array|min:1'];
        $messages = [];

        foreach ($this->files as $index => $file) {
            $fileName = ! empty($file['name']) ? $file['name'] : __('Untitled_Source');
            $rules["files.$index.name"] = 'required|string';
            $rules["files.$index.content"] = 'required|string|max:524288';
            $messages["files.$index.name.required"] = __('The file name is required for snippet #:index', ['index' => $index + 1]);
            $messages["files.$index.content.required"] = __('Source code is missing for file: :filename', ['filename' => $fileName]);
        }
        $this->validate($rules, $messages);

        // Pre-process for AddPostVersionAction
        $payloadFiles = [];
        foreach ($this->files as $f) {
            $payloadFiles[] = [
                'filename' => $f['name'],
                'content' => $f['content'],
                'language' => $f['language'],
                'description' => ! empty($f['description']) ? $f['description'] : null,
            ];
        }

        $addVersion->execute($this->post, ['files' => $payloadFiles]);

        session()->flash('success', __('New code version created and deployed!'));

        return redirect()->to(route('posts.detail', $this->post->id));
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.update-post');
    }
}
