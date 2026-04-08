<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-10">
        @csrf
        @method('put')

        <div class="space-y-3">
            <x-input-label for="update_password_current_password" :value="__('Current Secret')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" />
        </div>

        <div class="space-y-3">
            <x-input-label for="update_password_password" :value="__('New Protocol Path')" />
            <x-text-input id="update_password_password" name="password" type="password" class="block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" />
        </div>

        <div class="space-y-3">
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Path')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" />
        </div>

        <div class="flex items-center gap-6 pt-4">
            <x-ui.button variant="secondary" size="md">
                {{ __('Update Secrets') }}
            </x-ui.button>

            @if (session('status') === 'password-updated')
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="flex items-center gap-2 text-secondary"
                >
                   <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-[10px] font-black uppercase tracking-widest">{{ __('Secrets Rotated') }}</span>
                </div>
            @endif
        </div>
    </form>
</section>
