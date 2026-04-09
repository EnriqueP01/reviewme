<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-10" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Profile Photo -->
        <div class="flex items-center gap-8 p-6 rounded-3xl bg-surface-container-low border border-outline-variant/10 group/photo-upload">
            <div class="relative">
                <div class="w-24 h-24 rounded-2xl bg-solid-container-blur border-2 border-primary/20 flex items-center justify-center overflow-hidden transition-all duration-500 group-hover/photo-upload:border-primary shadow-xl">
                    <img id="photo-preview" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                </div>
                <label for="photo" class="absolute -bottom-2 -right-2 w-8 h-8 bg-primary rounded-xl flex items-center justify-center cursor-pointer shadow-lg hover:scale-110 transition-transform border-4 border-surface">
                    <svg class="w-4 h-4 text-surface" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                </label>
                <input type="file" id="photo" name="photo" class="hidden" accept="image/*" onchange="previewImage(this)">
            </div>
            <div class="space-y-1">
                <h4 class="text-sm font-black text-on-surface uppercase tracking-tighter">{{ __('Profile Photo') }}</h4>
                <p class="text-[10px] text-on-surface-variant opacity-60 font-medium">{{ __('Update your profile photo image.') }}</p>
                <x-input-error :messages="$errors->get('photo')" class="mt-2" />
            </div>
        </div>

        <script>
            function previewImage(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('photo-preview').src = e.target.result;
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>

        <!-- Display Name -->
        <div class="space-y-3">
            <x-input-label for="name" :value="__('Display Name')" />
            <x-text-input 
                id="name" 
                name="name" 
                type="text" 
                class="block w-full" 
                :value="old('name', $user->name)" 
                required 
                autofocus 
                autocomplete="name" 
            />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <!-- Unique Handle -->
        <div class="space-y-3">
            <x-input-label for="handle" :value="__('Unique Handle')" />
            <div class="relative group/handle flex items-center">
                <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none z-10">
                    <span class="text-primary font-mono font-black text-lg group-focus-within/handle:scale-110 transition-transform">@</span>
                </div>
                <x-text-input 
                    id="handle" 
                    name="handle" 
                    type="text" 
                    class="block w-full !pl-12 font-mono !text-primary !bg-primary/[0.02] border-primary/20 focus:border-primary/50 focus:ring-primary/10 tracking-tight" 
                    :value="old('handle', $user->handle)" 
                    required 
                />
            </div>
            <p class="text-[10px] text-on-surface-variant opacity-60 italic">{{ __('This will be used for your profile URL: ') }} {{ config('app.url') }}/profile/<b>your-handle</b></p>
            <x-input-error :messages="$errors->get('handle')" />
        </div>

        <!-- Biography -->
        <div class="space-y-3">
            <x-input-label for="bio" :value="__('Biography')" />
            <textarea 
                id="bio" 
                name="bio" 
                rows="4"
                class="block w-full rounded-[1.5rem] bg-surface-container border-outline-variant/10 text-on-surface focus:border-primary focus:ring-primary/10 transition-all text-sm py-4 px-6 italic"
                placeholder="{{ __('Tell us about yourself...') }}"
            >{{ old('bio', $user->bio) }}</textarea>
            <x-input-error :messages="$errors->get('bio')" />
        </div>

        <div class="space-y-3">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" name="email" type="email" class="block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="p-4 rounded-2xl bg-error/5 border border-error/20 mt-4">
                    <p class="text-xs font-bold text-error uppercase tracking-widest">
                        {{ __('Email not verified.') }}

                        <button form="send-verification" class="ml-4 underline hover:text-white transition-colors">
                            {{ __('Resend verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-[10px] font-black uppercase text-secondary">
                            {{ __('Verification email sent.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-6 pt-4">
            <x-ui.button variant="primary" size="md">
                {{ __('Save Changes') }}
            </x-ui.button>

            @if (session('status') === 'profile-updated')
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-x-4"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-init="setTimeout(() => show = false, 3000)"
                    class="flex items-center gap-2 text-secondary"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-[10px] font-black uppercase tracking-widest">{{ __('Saved') }}</span>
                </div>
            @endif
        </div>
    </form>
</section>
