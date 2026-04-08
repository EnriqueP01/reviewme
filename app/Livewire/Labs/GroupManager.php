<?php

declare(strict_types=1);

namespace App\Livewire\Labs;

use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

final class GroupManager extends Component
{
    public string $name = '';
    public string $description = '';
    public bool $isCreating = false;

    // Membership management
    public ?int $selectedGroupId = null;
    public string $userSearch = '';
    public array $searchResults = [];

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
        session()->flash('success', 'Lab créé avec succès !');
    }

    public function deleteGroup(int $id)
    {
        $group = Group::findOrFail($id);
        $this->authorize('delete', $group);

        $group->delete();
        session()->flash('success', 'Lab supprimé.');
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

        $this->searchResults = User::where('name', 'like', '%' . $this->userSearch . '%')
            ->where('id', '!=', Auth::id())
            ->limit(5)
            ->get()
            ->toArray();
    }

    public function addMember(int $userId)
    {
        if (!$this->selectedGroupId) return;

        $group = Group::findOrFail($this->selectedGroupId);
        $this->authorize('addMember', $group);

        if (!$group->members()->where('user_id', $userId)->exists()) {
            $group->members()->attach($userId, ['role' => 'member']);
        }

        $this->reset('userSearch', 'searchResults');
    }

    public function removeMember(int $userId)
    {
        if (!$this->selectedGroupId) return;

        $group = Group::findOrFail($this->selectedGroupId);
        $memberToRemove = User::findOrFail($userId);
        
        $this->authorize('removeMember', [$group, $memberToRemove]);

        $group->members()->detach($userId);
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
            $selectedGroup = Group::with('members')->findOrFail($this->selectedGroupId);
            $this->authorize('view', $selectedGroup);
        }

        return view('livewire.labs.group-manager', [
            'ownedGroups' => $ownedGroups,
            'memberGroups' => $memberGroups,
            'selectedGroup' => $selectedGroup,
        ]);
    }
}
