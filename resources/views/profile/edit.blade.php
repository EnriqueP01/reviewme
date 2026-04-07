<x-app-layout>
    <x-slot name="header">
        Curator Configuration
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto px-6">
        <div class="space-y-12">
            <!-- Profile Info -->
            <x-ui.card tonal="container" padding="p-8" class="border border-outline-variant/10">
                <div class="max-w-xl">
                    <div class="mb-8">
                        <h3 class="font-display font-bold text-xl text-on-surface italic">Identity & Essence</h3>
                        <p class="text-on-surface-variant text-sm mt-1">Update your personal metadata and digital signature.</p>
                    </div>
                    @include('profile.partials.update-profile-information-form')
                </div>
            </x-ui.card>

            <!-- Password -->
            <x-ui.card tonal="container" padding="p-8" class="border border-outline-variant/10">
                <div class="max-w-xl">
                    <div class="mb-8">
                        <h3 class="font-display font-bold text-xl text-on-surface italic">Security Protocol</h3>
                        <p class="text-on-surface-variant text-sm mt-1">Ensure your secrets are rotated and hardened.</p>
                    </div>
                    @include('profile.partials.update-password-form')
                </div>
            </x-ui.card>

            <!-- Danger Zone -->
            <x-ui.card tonal="low" padding="p-8" class="border border-secondary/20 bg-secondary/5">
                <div class="max-w-xl">
                    <div class="mb-8">
                        <h3 class="font-display font-bold text-xl text-secondary italic">Terminal Action</h3>
                        <p class="text-secondary/60 text-sm mt-1">Permanently remove your identity from the collective.</p>
                    </div>
                    @include('profile.partials.delete-user-form')
                </div>
            </x-ui.card>
        </div>
    </div>
</x-app-layout>
