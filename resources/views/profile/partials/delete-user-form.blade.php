<section class="space-y-10">
    <x-ui.button
        variant="danger"
        size="md"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Delete Account') }}</x-ui.button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <div class="glass-panel p-12 rounded-[3rem] border border-error/20 bg-surface">
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <h2 class="text-3xl font-display font-black text-on-surface tracking-tighter">
                    {{ __('Delete Account') }}
                </h2>

                <p class="mt-6 text-sm text-on-surface-variant leading-relaxed">
                    {{ __('Once your account is deleted, all your posts and data will be permanently removed. Please enter your password to confirm.') }}
                </p>

                <div class="mt-8">
                    <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                    <x-text-input
                        id="password"
                        name="password"
                        type="password"
                        class="block w-full"
                        placeholder="{{ __('Password...') }}"
                    />

                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-3" />
                </div>

                <div class="mt-12 flex justify-end gap-6">
                    <x-ui.button variant="ghost" size="sm" type="button" x-on:click="$dispatch('close')">
                        {{ __('Cancel') }}
                    </x-ui.button>

                    <x-ui.button variant="danger" size="sm">
                        {{ __('Delete Account') }}
                    </x-ui.button>
                </div>
            </form>
        </div>
    </x-modal>
</section>
