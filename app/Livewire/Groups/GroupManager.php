<?php

declare(strict_types=1);

namespace App\Livewire\Groups;

use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

final class GroupManager extends Component
{
    use WithFileUploads;

    public string $name = '';

    public string $description = '';

    public bool $isCreating = false;

    // Membership management
    public ?int $selectedGroupId = null;

    public string $userSearch = '';

    public array $searchResults = [];

    public string $activeTab = 'feed';

    public $logo;

    protected $rules = [
        'name' => 'required|min:3|max:255|unique:groups,name',
        'description' => 'nullable|string',
    ];

    public function createGroup()
    {
        $this->validate();

        $group = Group::create([
            'name' => $this->name,
            'description' => $this->description,
            'owner_id' => Auth::id(),
        ]);

        // Add owner as moderator in pivot
        $group->members()->attach(Auth::id(), ['role' => 'moderator']);

        $this->reset(['name', 'description', 'isCreating']);
        session()->flash('success', __('Group created successfully !'));
    }

    public function deleteGroup(int $id)
    {
        $group = Group::findOrFail($id);
        $this->authorize('delete', $group);

        $group->delete();
        session()->flash('success', __('Group deleted.'));
    }

    public function selectGroup(int $id)
    {
        $this->selectedGroupId = $id;
    }

    public function updatedUserSearch()
    {
        if (strlen($this->userSearch) < 3) {
            $this->searchResults = [];

            return;
        }

        $this->searchResults = User::where('name', 'like', '%'.$this->userSearch.'%')
            ->where('id', '!=', Auth::id())
            ->limit(5)
            ->get()
            ->toArray();
    }

    public function addMember(int $userId)
    {
        if (! $this->selectedGroupId) {
            return;
        }

        $group = Group::findOrFail($this->selectedGroupId);
        $this->authorize('addMember', $group);

        if (! $group->members()->where('user_id', $userId)->exists()) {
            $group->members()->attach($userId, ['role' => 'member']);
        }

        $this->reset('userSearch', 'searchResults');
    }

    public function removeMember(int $userId)
    {
        if (! $this->selectedGroupId) {
            return;
        }

        $group = Group::findOrFail($this->selectedGroupId);
        $memberToRemove = User::findOrFail($userId);

        $this->authorize('removeMember', [$group, $memberToRemove]);

        $group->members()->detach($userId);
    }

    public function updatedLogo()
    {
        $this->validate([
            'logo' => 'image|max:1024', // 1MB Max
        ]);

        if (! $this->selectedGroupId) {
            return;
        }

        $group = Group::findOrFail($this->selectedGroupId);
        $this->authorize('manage', $group);

        // Delete old logo if exists
        if ($group->logo_path) {
            Storage::disk('public')->delete($group->logo_path);
        }

        $path = $this->logo->store('groups/logos', 'public');
        $group->update(['logo_path' => $path]);

        $this->reset('logo');
        session()->flash('success', __('Logo updated successfully !'));
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $ownedGroups = Auth::user()->ownedGroups()->withCount('members')->get();
        $memberGroups = Auth::user()->groups()
            ->where('owner_id', '!=', Auth::id())
            ->withCount('members')
            ->get();

        $selectedGroup = null;
        if ($this->selectedGroupId) {
            $selectedGroup = Group::with('members')->withCount(['members', 'posts'])->findOrFail($this->selectedGroupId);
            $this->authorize('view', $selectedGroup);
        }

        return view('livewire.groups.group-manager', [
            'ownedGroups' => $ownedGroups,
            'memberGroups' => $memberGroups,
            'selectedGroup' => $selectedGroup,
        ]);
    }
}
