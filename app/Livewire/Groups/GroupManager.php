<?php

declare(strict_types=1);

namespace App\Livewire\Groups;

use App\Livewire\Traits\HasVibeNotifications;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

final class GroupManager extends Component
{
    use HasVibeNotifications, WithFileUploads;

    public string $name = '';

    public string $description = '';

    public bool $isCreating = false;

    // Membership management
    public ?int $selectedGroupId = null;

    public string $userSearch = '';

    public array $searchResults = [];

    public string $activeTab = 'feed';

    public $logo;

    public function mount(?string $slug = null)
    {
        if ($slug) {
            $group = Group::where('slug', $slug)->first();
            if ($group) {
                $this->selectedGroupId = $group->id;
            }
        }
    }

    /**
     * Bascule l'état d'affichage du formulaire de création de groupe.
     */
    public function toggleCreating(): void
    {
        $this->isCreating = ! $this->isCreating;
    }

    protected $rules = [
        'name' => 'required|min:3|max:255|unique:groups,name',
        'description' => 'nullable|string',
    ];

    public function createGroup()
    {
        if (! Auth::user()->hasKarmaPermission('group.create')) {
            $this->notifyError(__('Niveau de karma insuffisant pour créer un groupe.'));

            return;
        }

        $this->validate();

        $group = Group::create([
            'name' => $this->name,
            'description' => $this->description,
            'owner_id' => Auth::id(),
        ]);

        // Add owner as moderator in pivot
        $group->members()->attach(Auth::id(), ['role' => 'moderator']);

        \App\Models\UserContribution::record(Auth::id());

        $this->reset(['name', 'description', 'isCreating']);
        $this->notifySuccess(__('Le groupe a été forgé avec succès !'));
    }

    public function deleteGroup(int $id)
    {
        $group = Group::findOrFail($id);
        $this->authorize('delete', $group);

        $group->delete();
        $this->selectedGroupId = null;
        $this->isCreating = false;

        $this->notifySuccess(__('Groupe supprimé du système.'));
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
        $this->notifySuccess(__('Nouveau contributeur ajouté au groupe !'));
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
        $this->notifySuccess(__('Contributeur retiré du groupe.'));
    }

    public function leaveGroup(int $groupId)
    {
        $group = Group::findOrFail($groupId);
        if ($group->owner_id === Auth::id()) {
            $this->notifyError(__('Le propriétaire ne peut pas quitter le groupe.'));

            return;
        }

        $group->members()->detach(Auth::id());
        $this->selectedGroupId = null;
        $this->notifySuccess(__('Vous avez quitté le groupe.'));
    }

    public function updatedLogo()
    {
        $this->validate([
            'logo' => 'image|max:2048', // 2MB Max
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
        $this->notifySuccess(__('Identité visuelle mise à jour.'));
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
            // Optimisation : Limitation à 30 membres pour l'affichage initial et le panneau latéral
            $selectedGroup = Group::with(['members' => fn($q) => $q->take(30)])
                ->withCount(['members', 'posts'])
                ->findOrFail($this->selectedGroupId);
            $this->authorize('view', $selectedGroup);
        }

        return view('livewire.groups.group-manager', [
            'ownedGroups' => $ownedGroups,
            'memberGroups' => $memberGroups,
            'selectedGroup' => $selectedGroup,
        ]);
    }
}
