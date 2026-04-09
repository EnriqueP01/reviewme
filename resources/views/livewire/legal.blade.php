<div class="max-w-4xl mx-auto px-6 py-12">
    <div class="mb-16 space-y-4 animate-in fade-in slide-in-from-top-8 duration-1000">
        <h1 class="text-4xl lg:text-5xl font-black tracking-tighter uppercase">{{ $type === 'privacy' ? __('Privacy Policy') : __('Terms of Service') }}</h1>
        <p class="text-on-surface-variant italic">{{ __('Effective Date: April 9, 2026') }}</p>
    </div>

    <div class="glass-panel p-10 rounded-round-4 border border-white/5 shadow-2xl animate-in fade-in slide-in-from-bottom-8 duration-1000 delay-200">
        <div class="prose prose-invert prose-p:italic prose-p:text-on-surface-variant/80 max-w-none space-y-12">
            @if($type === 'privacy')
                <section class="space-y-4">
                    <h3 class="text-xl font-bold tracking-tight text-primary">{{ __('1. Data Collection') }}</h3>
                    <p>{{ __('We collect only essential data through your GitHub account (Username, Email, Avatar) to provide the service. We do not track your location or sell your data to third parties.') }}</p>
                </section>
                <section class="space-y-4">
                    <h3 class="text-xl font-bold tracking-tight text-primary">{{ __('2. Code Snippets') }}</h3>
                    <p>{{ __('Any code fragments you upload are stored in our encrypted database. If you use a "Private Lab", your fragments are only visible to the members you explicitly authorized.') }}</p>
                </section>
                <section class="space-y-4">
                    <h3 class="text-xl font-bold tracking-tight text-primary">{{ __('3. Your Rights') }}</h3>
                    <p>{{ __('You can request the permanent deletion of your account and all associated data at any time through your profile settings.') }}</p>
                </section>
            @else
                <section class="space-y-4">
                    <h3 class="text-xl font-bold tracking-tight text-secondary">{{ __('1. Acceptance of Terms') }}</h3>
                    <p>{{ __('By using ReviewMe, you agree to these terms. If you do not agree, please do not use the platform.') }}</p>
                </section>
                <section class="space-y-4">
                    <h3 class="text-xl font-bold tracking-tight text-secondary">{{ __('2. User Conduct') }}</h3>
                    <p>{{ __('You are responsible for the content you post. Harassment, hate speech, or the distribution of malicious code will result in immediate termination of access.') }}</p>
                </section>
                <section class="space-y-4">
                    <h3 class="text-xl font-bold tracking-tight text-secondary">{{ __('3. Educational Use') }}</h3>
                    <p>{{ __('ReviewMe is intended for educational and collective improvement purposes. Any commercial use of the infrastructure is prohibited without explicit consent.') }}</p>
                </section>
            @endif
        </div>
    </div>
</div>
